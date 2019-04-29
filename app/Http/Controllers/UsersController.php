<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use App\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function store() {
        $validator = Validator::make(request()->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'mobile_phone' => 'required|unique:users',
            'password' => 'required|confirmed',
            'group_id' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()->toArray(),
            ])->setStatusCode(400);
        } else {
            $user = null;

            if ($user_id = request('user_id')) {
                $user = User::find($user_id);
                $user->first_name = request('first_name');
                $user->last_name = request('last_name');
                $user->email = request('email');
                $user->mobile_phone = request('mobile_phone');
                $user->password = bcrypt(request('password'));
                $user->group_id = request('group_id');
            } else {
                $user = new User([
                    'first_name' => request('first_name'),
                    'last_name' => request('last_name'),
                    'email' => request('email'),
                    'mobile_phone' => request('mobile_phone'),
                    'password' => bcrypt(request('password')),
                    'role_id' => Role::systemAdmin()->id,
                ]);

                Group::find(request('group_id'))->users()->save($user);
            }

            return response()->json($user);
        }
    }
}
