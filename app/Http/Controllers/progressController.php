<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class progressController extends Controller
{
  public function progress(request $req)
  {
      return json_encode(Cache::get($req->id));
  }

}
