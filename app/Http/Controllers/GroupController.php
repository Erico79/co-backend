<?php

namespace App\Http\Controllers;

use App\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    public function register() {
        $validator = Validator::make(request()->all(), [
            'name' => 'required|unique:groups',
            'no_of_members' => 'required|numeric|min:2'
        ]);

        if (!$validator->fails()) {
            $group = Group::create(request()->all());

            return response(['success' => true, 'message' => 'Your Group has been registered!', 'group' => $group]);
        } else {
            return response()->json([
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray(),
            ]);
        }
    }
}
