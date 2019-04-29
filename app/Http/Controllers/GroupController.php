<?php

namespace App\Http\Controllers;

use App\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    public function register() {
        $validator = Validator::make(request()->all(), [
            'name' => 'required',
            'no_of_members' => 'required|numeric|min:2'
        ]);

        $group_exists_message = 'A Chama with the same name already exists!';

        if (request()->has('group_id')) {
            $group = Group::find(request('group_id'));

            $validator->after(function ($validator) use($group_exists_message) {
                $group_exists = Group::where('id', '<>', request('group_id'))->where('name', request('name'))->count();

                if ($group_exists)
                    $validator->errors()->add('name', $group_exists_message);
            });
        } else {
            $validator->after(function($validator) use($group_exists_message) {
                $group_exists = Group::where('name', request('name'))->count();

                if ($group_exists)
                    $validator->errors()->add('name', $group_exists_message);
            });
        }

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
