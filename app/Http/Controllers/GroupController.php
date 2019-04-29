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
            if ($group_id = request('group_id')) {
                $group = Group::find(request('group_id'));
                $group->name = request('name');
                $group->no_of_members = request('no_of_members');
                $group->save();
            } else {
                $group = Group::create(request()->all());
            }

            return response()->json(['message' => 'Your Group has been registered!', 'group' => $group]);
        } else {
            return response()->json([
                'errors' => $validator->getMessageBag()->toArray(),
            ])->setStatusCode(400);
        }
    }
}
