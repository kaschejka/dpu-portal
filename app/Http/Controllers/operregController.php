<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Exports\operregExport;
use Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\CollectionstdClass;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Cache;


class operregController extends Controller
{



   public function operregsub(Request $req)
    {

$numberArray = Helper::parseFile($req->id, $req->number, $req->selectInputNum);
$regop = array("ot","do","operator","region");
$i=0;
      $rzbd = DB::table('regop')->get();
$onePersent = 100/count($rzbd);
$progress = 0;
Cache::put($req->id, ['Получение массива данных из ФАС',$progress], now()->addMinutes(3));
      foreach ($rzbd as $rzbd) {
      $regop[$i][0]='7'.$rzbd->kod.$rzbd->ot;
      $regop[$i][1]='7'.$rzbd->kod.$rzbd->do;
      $regop[$i][2]=$rzbd->operator;
      $regop[$i][3]=$rzbd->region;
      $i++;
      $progress = $progress + $onePersent;
      }
 Cache::put($req->id, ['Получение массива данных из ФАС',$progress], now()->addMinutes(3));
 $progress = 0;
 $onePersent = 100/count($numberArray);
 Cache::put($req->id, ['Определение региона и оператора',$progress], now()->addMinutes(3));


    for ($i=0; $i < count($numberArray) ; $i++) {
      $nm = Helper::oprregop($numberArray[$i]['number'],$regop);
      $numberArray[$i]['operator'] = $nm[1];
      $numberArray[$i]['region'] = $nm[2];
      $progress = $progress + $onePersent;
      Cache::put($req->id, ['Определение региона и оператора',$progress], now()->addMinutes(3));
      }

 Cache::put($req->id, ['SUCCES',100], now()->addMinutes(3));

if ($req->resultOutput == 'web') {
  return $numberArray;
} else {
  $export = new operregExport([$numberArray]);
  return Excel::download($export,'regop.xlsx');
}

    }
}
