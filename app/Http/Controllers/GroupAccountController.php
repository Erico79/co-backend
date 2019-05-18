<?php

namespace App\Http\Controllers;

use App\Group;
use App\GroupAccount;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

class GroupAccountController extends Controller
{
    public function store(Group $group) {
      $validator = Validator::make(request()->all(), [
        'accounts.*.name' => 'required|unique:group_accounts',
        'accounts.*.contribution_amount' => 'required|numeric|min:1',
      ]);

      if ($validator->fails()) {
        return response()->json($validator->getMessageBag()->toArray())->setStatusCode(400);
      }

      try {
        $group_accounts = request('accounts');

        $g_accs = [];
        foreach ($group_accounts as $key => $ga) {
          array_push($g_accs, new GroupAccount([
              'name' => $ga['name'],
              'contribution_amount' => $ga['contribution_amount'],
            ])
          );
        }

        $group->groupAccounts()->saveMany($g_accs);

        return response()->json($group_accounts);
      } catch(QueryException $qe) {
        return response()->json(['error' => $qe->getMessage()])->setStatusCode(500);
      }
    }
}
