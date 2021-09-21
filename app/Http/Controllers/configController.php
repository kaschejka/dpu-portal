<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\CollectionstdClass;
use Illuminate\Support\Facades\DB;


class configController extends Controller
{




   public function config (Request $req)
    {

if ($req->method == 'editPodpisant') {
  $id = substr($req->id,1);
  DB::table('podpisantmtt')
            ->where('id', $id)
            ->update(['FIO' => $req->fio,
                    'dolzhnost' => $req->dolzhnost,
                  'podpiRP' => $req->dolzhnostRP,
                'osnpodpmtt' => $req->osnpodpmtt]);
  return $req;
}

if ($req->method == 'delPodpisant') {
  $id = substr($req->id,1);
  DB::table('podpisantmtt')
            ->where('id', $id)
            ->delete();
  return $req;
}

if ($req->method == 'addPodpisant') {
  DB::table('podpisantmtt')
            ->insert(['FIO' => $req->fio,
                    'dolzhnost' => $req->dolzhnost,
                  'podpiRP' => $req->dolzhnostRP,
                'osnpodpmtt' => $req->osnpodpmtt]);
  return $req;
}

if ($req->method == 'editRegion') {
  $id = substr($req->id,1);
  if ($req->abc_active) {
    $abc_active = 1;
  } else {
    $abc_active = 0;
  }
  if ($req->def_active) {
    $def_active = 1;
  } else {
    $def_active = 0;
  }
  DB::table('region')
            ->where('id', $id)
            ->update(['ru_name_abc' => $req->ru_name_abc,
                    'ru_name_def' => $req->ru_name_def,
                  'abc_activate' => $abc_active,
                'def_activate' => $def_active]);
  return $req;
}

if ($req->method == 'delRegion') {
  $id = substr($req->id,1);
  DB::table('region')
    ->where('id', $id)
            ->delete();
  return $req;
}

if ($req->method == 'addRegion') {
  if ($req->abc_active) {
    $abc_active = 1;
  } else {
    $abc_active = 0;
  }
  if ($req->def_active) {
    $def_active = 1;
  } else {
    $def_active = 0;
  }
  DB::table('region')
            ->insert(['id' => $req->id,
              'ru_name_abc' => $req->ru_name_abc,
                    'ru_name_def' => $req->ru_name_def,
                  'abc_activate' => $abc_active,
                'def_activate' => $def_active]);
  return $req;
}

    }
}
