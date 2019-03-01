<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    public function store() {
        $validator = Validator::make(request()->all(), [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'mobile_phone' => ['required', 'numeric', 'max:255'],
            'group_id' => ['required', 'numeric'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $validator->after(function($validator) {
            $admin_email_exists = User::where('group_id', request('group_id')->where('email', request('email')))->count();
            if ($admin_email_exists) $validator->error()->add('email', 'The email address already exists!');

            $admin_mobile_phone_exists = User::where('group_id', request('group_id')->where('mobile_phone', request('mobile_phone')))->count();
            if ($admin_mobile_phone_exists) $validator->error()->add('mobile_phone', 'The Mobile Phone No already exists!');
        });

        if (!$validator->fails()) {
            User::create(request()->all());

            return response()->json(['success' => true]);
        } else {
            return response()->json([
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            ]);
        }
    }
}
