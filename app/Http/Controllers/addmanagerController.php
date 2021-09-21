<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class addmanagerController extends Controller
{
  public function addmanager (Request $req)
  {


if (isset($req->mail)) {
  $man = DB::table('manager')->where('mail','=',$req->mail)->first();
  if ($man == Null) {
    DB::table('manager')->insert([
  'mail' => $req->mail,
  'FIO' => $req->fiom
]);
  }
}
if (isset($req->email)) {
  DB::table('users')
            ->where('email', $req->email)
            ->update(['role' => $req->role]);
}

    return redirect()->back();
  }


public function editReserveManager(Request $req)
{
  $id = substr($req->id,1);
  DB::table('manager')
            ->where('id', $id)
            ->update(['FIO' => $req->fio,
                    'mail' => $req->mail]);
            return $req;
}

public function delReserveManager(Request $req)
{
  $id = substr($req->id,1);
  DB::table('manager')
            ->where('id', $id)
            ->delete();
            return $req;
}

public function delUser(Request $req)
{
  DB::table('users')
            ->where('id', $req->id)
            ->delete();
            return $req;
}

}
