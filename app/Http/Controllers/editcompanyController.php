<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class editcompanyController extends Controller
{
  public function editcompany (Request $req)
  {


    DB::table('napominalka')
              ->where('description', $req->description)
              ->update(['company' => $req->company]);
     return 'OK';
  }
}
