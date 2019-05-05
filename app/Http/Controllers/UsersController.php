<?php

namespace App\Http\Controllers;

use App\Otp;
use App\Repositories\UserRepository;
use App\Role;
use App\User;
use App\Group;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Events\GroupAdminRegistered;

class UsersController extends Controller
{
    public function store() {
        $validator = Validator::make(request()->all(), [
          'first_name' => 'required',
          'last_name' => 'required',
          'email' => 'required|email|unique:users',
          'mobile_phone' => 'required|min:12|unique:users',
          'password' => 'required|confirmed',
          'group_id' => 'required|numeric'
        ]);

        $otp = rand(1000, 9999);

        if ($validator->fails()) {
          return response()->json([
              'errors' => $validator->getMessageBag()->toArray(),
          ])->setStatusCode(400);
        } else {
          $user = null;

          try {
//            if (User::emailExists(request('email')) ||
//              User::mobileNoExists(request('mobile_phone'))) {
//              $user = User::where('email', request('email'))
//                ->orWhere('mobile_phone', request('mobile_phone'))
//                ->first();
//
//              if ($user->email !== request('email')) {
//                $user->email = request('email');
//                $user->save();
//              }
//
//              if ($user->mobile_phone !== request('mobile_phone')) {
//                $user->mobile_phone = request('mobile_phone');
//                $user->save();
//              }
//
//              event(new GroupAdminRegistered($user, $otp));
//              return response()->json(['error_code' => 'ADMIN_EXISTS']);
//            }

            if ($user_id = request('user_id')) {
              $user = User::find($user_id);
              $user->first_name = request('first_name');
              $user->last_name = request('last_name');
              $user->email = request('email');
              $user->mobile_phone = request('mobile_phone');
              $user->password = bcrypt(request('password'));
              $user->group_id = request('group_id');
              $user->save();
            } else {
              $user = new User([
                'first_name' => request('first_name'),
                'last_name' => request('last_name'),
                'email' => request('email'),
                'mobile_phone' => request('mobile_phone'),
                'password' => bcrypt(request('password')),
                'role_id' => Role::groupAdmin()->id,
              ]);

              Group::find(request('group_id'))->users()->save($user);
            }
          } catch (QueryException $qe) {
            return response()->json($qe)->setStatusCode(500);
          }

          if ($user) {
            event(new GroupAdminRegistered($user, $otp));
            return response()->json(['user' => $user, 'otp' => $otp]);
          }
        }
    }

    public function validateOTP() {
      $validator = Validator::make(request()->all(), ['otp' => 'required']);

      if ($validator->fails()) {
        return response()->json([
          'errors' => $validator->getMessageBag()->toArray()
        ])->setStatusCode(400);
      }

      $otp = request()->user()->otps()->latest()->first();
      $expired = time() > strtotime($otp->expires_on);

      if ($expired) {
        return response()->json([
          'success' => false,
          'message' => 'Otp has expired.',
          'otp_status_code' => 'OTP_EXPIRED',
        ]);
      }

      if (request('otp') === $otp->code) {
        $otp->retries = 0;
        $otp->save();
        return response()->json([
          'success' => true,
          'retries' => $otp->retries,
          'otp_status_code' => 'OTP_IS_VALID'
        ]);
      }

      $otp = Otp::find($otp->id);
      if ($otp->retries) {
        $otp->retries = $otp->retries - 1;
      } else {
        $otp->retries = 0;
      }
      $otp->save();

      return response()
        ->json([
          'retries' => $otp->retries,
          'otp' => $otp->code,
          'otp_status_code' => 'OTP_IS_INVALID'
        ])
        ->setStatusCode(400);
    }

    public function resendOTP($mobile_no) {
      $new_otp = rand(1000, 9999);
      $user = User::mobileNoExists($mobile_no)->otps()->delete();
      (new UserRepository($user, $new_otp))->sendOTP();
    }
}
