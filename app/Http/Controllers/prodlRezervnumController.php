<?php

namespace App\Http\Controllers;
use App\Http\Requests\rezervnumRequest;
use Illuminate\Http\Request;
use App\Imports\rezervnumImport;
use App\Models\rezervnumModel;
use Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\CollectionstdClass;
use App\Helpers\Helper;
use App\Exports\rezervnumExport;
use Funct\Strings;
use Funct\Collection;

class prodlRezervnumController extends Controller
{



public function frezerv ($rnum,$rezdate_sec,$description)
{
  $curl = curl_init();
       curl_setopt_array($curl, array(
         CURLOPT_URL => env('NMS_URL'),
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => "",
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 0,
         CURLOPT_FOLLOWLOCATION => true,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => "POST",
         CURLOPT_POSTFIELDS =>"{\r\n    \"jsonrpc\":\"2.0\",\r\n    \"method\":\"scriptExecute\",\r\n    \"params\":{\r\n        \"projectName\":\"NMS API\",\r\n        \"reportName\":\"lockNumbers\",\r\n        \"jsonOperParams\":[\r\n            {\"paramName\":\"pDescription\",\"paramValue\":\"$description\"},
         \r\n    {\"paramName\":\"pNumerics_List\",\"paramValue\":\"$rnum\"},  \r\n            {\"paramName\":\"pCount_Seconds\",\"paramValue\":$rezdate_sec}  \r\n        ]\r\n    },\r\n    \"id\":\"1\"\r\n}",
         CURLOPT_HTTPHEADER => array(
           "Content-Type: application/json"
         ),
       ));

       $response = curl_exec($curl);

       curl_close($curl);

       $tt = json_decode($response);
       if (Strings\contains($response, 'error')) {
         return 'error';
       } else {
         return $tt->result[0]->reserved_uid;
       }
}


public function droprezerv ($rnum)
{
  $curl = curl_init();
       curl_setopt_array($curl, array(
         CURLOPT_URL => env('NMS_URL'),
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => "",
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 0,
         CURLOPT_FOLLOWLOCATION => true,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => "POST",
         CURLOPT_POSTFIELDS =>"{\"jsonrpc\":\"2.0\",
\"method\":\"scriptExecute\",
\"params\":{
    \"projectName\":\"NMS - смена состояний\",
    \"reportName\":\"Изменения состояния у номера/выбранных номеров\",
    \"jsonOperParams\":[
        {\"paramName\":\"pNumerics_List\",\"paramValue\":\"$rnum\"},
        {\"paramName\":\"pStatus_To\",\"paramValue\":\"FREE\"}
    ]
},
\"id\":1}",
         CURLOPT_HTTPHEADER => array(
           "Content-Type: application/json"
         ),
       ));

       $response = curl_exec($curl);

       curl_close($curl);
       return $response;
}

function getNumberInfo ($rnum){
  $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => env('NMS_URL'),
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>"{
    \"jsonrpc\":\"2.0\",
    \"method\":\"scriptExecute\",
    \"params\":{
        \"projectName\":\"NMS API\",
        \"reportName\":\"getNumberInfo\",
        \"jsonOperParams\":[
            {
                \"paramName\":\"pNum_Code\",
                \"paramValue\":$rnum
            }
        ]
    },
    \"id\":\"1\"
}",
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
$tt = json_decode($response);
if (empty($tt->result[0])) {
  return 'Not_found';
} else {
  return $tt->result[0]->state_name;
}

}

    public function reznumsub (Request $req)
    {

 // echo dd($req);

$numh=DB::table('historyrezerv')->where([['description','=', $req->description],
  ['end_date','=',$req->olddate]])->get();
  $manager = $numh[0]->manager;
 // echo dd($manager);


$rezdate_sec=strtotime($req->end_date)-strtotime('Now');
  $regop = array("ot","do","operator","region");
   $i=0;
         $rzbd = DB::table('regop')->get();
         foreach ($rzbd as $rzbd) {
         $regop[$i][0]='7'.$rzbd->kod.$rzbd->ot;
         $regop[$i][1]='7'.$rzbd->kod.$rzbd->do;
         $regop[$i][2]=$rzbd->operator;
         $regop[$i][3]=$rzbd->region;
         $i++;
         }

     foreach ($numh as $numh) {
       $nm = Helper::oprregop($numh->number,$regop);
       $reserv[] = ['number'=>$numh->number,
       'imsi'=>'',
       'operator'=>$nm[1],
       'region'=>$nm[2],
       'end_date' =>$req->end_date,
      'uid' =>''];
     }

$reserv_chunk = array_chunk($reserv,100);

foreach ($reserv_chunk as $reserv_chunk) {
  $number = array_column($reserv_chunk, 'number');
  $number = implode(',', $number);
  $drop_r = $this->droprezerv($number);
  if (Strings\contains($drop_r, 'error')) {
      foreach ($reserv_chunk as $reserv_chunk) {
        $state = $this->getNumberInfo($reserv_chunk['number']);
        if ($state == 'FREE' || $state == 'RESERVED') {
          if ($state == 'FREE') {
            $uid[] = ['uid' => $this->frezerv($reserv_chunk['number'], $rezdate_sec, $req->description)];
          }
          if ($state == 'RESERVED') {
            $drop_r = $this->droprezerv($reserv_chunk['number']);
            $uid[] = ['uid' => $this->frezerv($reserv_chunk['number'], $rezdate_sec, $req->description)];
          }
        } else {
          $uid[] = ['uid' => 'error'];
        }
      }
  } else {
    $response = $this->frezerv($number, $rezdate_sec, $req->description);
    foreach ($reserv_chunk as $reserv_chunk) {
      $uid[] = ['uid' => $response];
    }
  }
}
$i=0;
for ($i=0; $i < count($reserv); $i++) {
  $reserv[$i]['uid'] = $uid[$i]['uid'];
}


$gen = array_filter($reserv, fn($user) => is_int($user['uid']));
foreach ($gen as $gen) {
  $historyrezerv[] = [
    'number' => $gen['number'],
    'description' => $req->description,
    'date_issue' =>date("Y-m-d", strtotime("Now")),
    'end_date'=>$req->end_date,
    'author'=>auth()->user()->name,
    'manager'=>$manager,
    'uid'=>$gen['uid']
  ];
}

if (count($gen)>0) {

  DB::table('napominalka')
  ->where([
    ['description', '=', $req->description],
    ['end_date' , '=', $req->olddate]
  ])
  ->update(['end_date'=>$req->end_date]);

  foreach (array_chunk($historyrezerv,5000) as $historyrezerv)
  {
       DB::table('historyrezerv')->insert($historyrezerv);
        sleep(1);
  }
}

$export = new rezervnumExport([$reserv]);

if (in_array('error',$reserv)) {
  return view('err',['alarm'=>'Не на всех номерах продлен резерв', 'rezerv'=>$reserv, 'descr'=>$req->description.'.xlsx']);
} else {
      return Excel::download($export,$req->description.'.xlsx');
}

    }
}
