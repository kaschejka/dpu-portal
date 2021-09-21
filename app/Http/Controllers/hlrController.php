<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\CollectionstdClass;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Storage;
use App\Exports\hlrExport;
use Illuminate\Support\Facades\Cache;


class hlrController extends Controller
{

   public function hlr(Request $req)
    {

$numberArray = Helper::parseFile($req->id, $req->number, $req->selectInputNum);

$hlrproject = [];
$rzbd = DB::table('hlrproject')->get();
foreach ($rzbd as $rzbd) {
$hlrproject[] = [
  'id'=>$rzbd->id,
  'project'=>$rzbd->name
];
}
$progress = 0;
$onePersent = 100/count($numberArray);
Cache::put($req->id, ['Выгрузка из HLR',$progress], now()->addMinutes(3));
for ($i=0; $i < count($numberArray) ; $i++) {
  $num = $numberArray[$i]['number'];
  $curl = curl_init();

 curl_setopt_array($curl, array(
  CURLOPT_URL => env('HLR_URL'),
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>
 "{\r\n    \"jsonrpc\":\"2.0\",
   \r\n    \"method\":\"get-profile\",
   \r\n    \"params\":{\r\n        \"profile\":{\"msisdn\":\"$num\"}  \r\n  },\r\n    \"id\":\"hlrtest1\"\r\n}",

  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    env('HLR_LOGIN')
  ),
 ));

 $response = curl_exec($curl);

 curl_close($curl);

      $tt=json_decode($response);
      if (isset($tt->error)) {
        $numberArray[$i]['imsi']='#Н/Д';
        $numberArray[$i]['Forwarding']='#Н/Д';
        $numberArray[$i]['project']='#Н/Д';
        $numberArray[$i]['SMS']='#Н/Д';
      } else {
      if (isset($tt->result->imsi)) {
        $numberArray[$i]['imsi']=$tt->result->imsi;
      }

        if (isset($tt->result->ssForw)) {
          $tt3= $tt->result->ssForw;

if ($tt3 == Null) {
  $numberArray[$i]['Forwarding']='#Н/Д';
} else {
  foreach ($tt3 as $tt3) {
    if (isset($tt3->forwardedToNumber)) {
      $forw = $tt3->forwardedToNumber;
    }
  }
$numberArray[$i]['Forwarding']=$forw;
}
  }
  if (isset($tt->result->groupId)) {
    $pri = array_search($tt->result->groupId,array_column($hlrproject, 'id'));
    $numberArray[$i]['project']=$hlrproject[$pri]['project'];
  }
     if (strpos($response,'roaming-info')=== false) {
        $numberArray[$i]['SMS']='НET';
     } else {
       $numberArray[$i]['SMS']='ДА';
     }
  }
  $progress = $progress + $onePersent;
Cache::put($req->id, ['Выгрузка из HLR',$progress], now()->addMinutes(3));
}

Cache::put($req->id, ['SUCCES',100], now()->addMinutes(3));

if ($req->resultOutput == 'web') {
  return $numberArray;
} else {
  $export = new hlrExport([$numberArray]);
  return Excel::download($export,'hlr.xlsx');
}

    }
}
