<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Excel;
use Illuminate\Support\Facades\DB;
use App\Imports\upnmsImport;
use App\Imports\updImsiImport;
use App\Exports\nmsExport;
use Illuminate\Support\CollectionstdClass;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Storage;
use RollingCurlService\RollingCurl;
use RollingCurlService\RollingCurlRequest;


class upnmsController extends Controller
{


   public function upnms(Request $req)
    {

$urls=[];
if ($req->outrez == 'upl') {
  $data = Excel::toArray(new upnmsImport(), $req->fl);
if (array_key_exists('pnum_code',$data[0][0]) && array_key_exists('pimsi',$data[0][0])
    && array_key_exists('pproject_name',$data[0][0]) && array_key_exists('pdistrib_channel',$data[0][0])
    && array_key_exists('pregion',$data[0][0]) && array_key_exists('powner',$data[0][0])) {

   $ownernms = [];
   $rzbd = DB::table('owner_nms')->get();
   foreach ($rzbd as $rzbd) {
   $ownernms[] = [
     'id'=>$rzbd->id,
     'name'=>$rzbd->name
   ];
   }
   unset($rzbd);

   $projectnms = [];
   $rzbd = DB::table('project_nms')->get();
   foreach ($rzbd as $rzbd) {
   $projectnms[] = [
     'id'=>$rzbd->id,
     'name'=>$rzbd->name
   ];
   }
   unset($rzbd);

   $regionnms = [];
   $rzbd = DB::table('region_nms')->get();
   foreach ($rzbd as $rzbd) {
   $regionnms[] = [
     'id'=>$rzbd->id,
     'name'=>$rzbd->name
   ];
   }
   unset($rzbd);

$rollingCurl = new \RollingCurlService\RollingCurl();

for ($i=0; $i < count($data[0]) ; $i++) {
  $num = $data[0][$i]['pnum_code'];

$imsi = $data[0][$i]['pimsi'];


  if (substr($data[0][$i]['pnum_code'],0,2) == '79') {
    $typeID = 1104;
  } else {
    $typeID = 1105;
  }

  if (substr($data[0][$i]['pnum_code'],0,4) == '7800') {
    $typeID = 1106;
  }
  if (substr($data[0][$i]['pnum_code'],0,4) == '7804') {
    $typeID = 1727;
  }

  if (substr($data[0][$i]['pnum_code'],0,6) == '883140') {
    $typeID = 1107;
  }
$ind = array_search($data[0][$i]['powner'],array_column($ownernms, 'name'));
if ($ind === false) {
  $ownerID = '';
} else {
    $ownerID = $ownernms[$ind]['id'];
}

$ind = array_search($data[0][$i]['pproject_name'],array_column($projectnms, 'name'));
if ($ind === false) {
  $projectID = '';
} else {
    $projectID = $projectnms[$ind]['id'];
}

$ind = array_search($data[0][$i]['pregion'],array_column($regionnms, 'name'));
if ($ind === false) {
  $regionID = '';
} else {
    $regionID = $regionnms[$ind]['id'];
}

$urls += [$num  => [ CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => '',
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 0,
CURLOPT_FOLLOWLOCATION => false,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => 'POST',
CURLOPT_POSTFIELDS =>"{\"jsonrpc\":\"2.0\",
\"method\":\"scriptExecute\",
\"params\":
{\"projectName\":\"NMS - Операции\",
\"reportName\":\"Загрузить номер\",
\"jsonOperParams\":[
  {\"paramName\":\"pNum_Code\",\"paramValue\":\"$num\"},
  {\"paramName\":\"pIMSI\",\"paramValue\":\"$imsi\"},
  {\"paramName\":\"pNum_Type_ID\",\"paramValue\":$typeID},
  {\"paramName\":\"pOwner_ID\",\"paramValue\":$ownerID},
  {\"paramName\":\"pProject_ID\",\"paramValue\":$projectID},
  {\"paramName\":\"pRegion_ID\",\"paramValue\":\"$regionID\"},
  {\"paramName\":\"pState_ID\",\"paramValue\":7},
  {\"paramName\":\"pDescription\",\"paramValue\":\"\"},
  {\"paramName\":\"pUser_Name\",\"paramValue\":\"DKOZLOV\"}
]},\"id\":1}",
CURLOPT_HTTPHEADER => array(
  'Content-Type: application/json'
)
]];


}

     // echo dd($urls);
$rollingCurl = new \RollingCurlService\RollingCurl();

foreach ($urls as $url => $options) {
    $request = new \RollingCurlService\RollingCurlRequest(env('NMS_URL'));
    $request->setOptions($options,TRUE);
    $request->setAttributes([
        'requestId'   => $url // Some ID for the request
    ]);
    $rollingCurl->addRequest($request);
}
$curlResult = [];

$rollingCurl->execute(function ($output, $info, $request) use (& $curlResult)
{
    $requestAttributes = $request->getAttributes();
    // If request response was OK
    if ($info['http_code'] == 200) {
      $tt=json_decode($output);
      if (isset($tt->error)) {

        $curlResult[]=[
      'number' => $requestAttributes['requestId'],
      'status' => 'error'
      ];

      } else {
        $curlResult[]=[
      'number' => $requestAttributes['requestId'],
      'status' => 'succes'
      ];
      }
    // If request response was KO
    } elseif ($info['http_code'] != 200) {
        $curlResult[$requestAttributes['requestId']] = 'KO response';
    }
});

// echo dd($curlResult);
$rollingCurl->clear();
$export = new nmsExport([$curlResult]);
return Excel::download($export,'upload_num_nms.xlsx');
} else {
  $result_arr[0][0]='error';
  $result_arr[0][1]='Неверный формат файла';
  $export = new nmsExport([$result_arr]);
  return Excel::download($export,'error.xlsx');
}
} else {
  $data = Excel::toArray(new updImsiImport(), $req->fl);

for ($i=0; $i < count($data[0]) ; $i++) {
  $num=$data[0][$i]['num'];
  $imsi=$data[0][$i]['imsi'];
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => env('NMS_URL'),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => false,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>"{\"jsonrpc\":\"2.0\",
    \"method\":\"scriptExecute\",
    \"params\":{
        \"projectName\":\"NMS - DPU\",
        \"reportName\":\"Set IMSI By Number Code\",
        \"jsonOperParams\":[
            {\"paramName\":\"pNum_Code\",\"paramValue\":$num},
            {\"paramName\":\"pIMSI\",\"paramValue\":$imsi}
        ]
    },
    \"id\":\"1\"}",
    CURLOPT_HTTPHEADER => array(
      'Content-Type: application/json'
    ),
  ));

  $response = curl_exec($curl);

  curl_close($curl);

  $tt=json_decode($response);
  if (isset($tt->error)) {
    $result_arr[$i][0]=$num;
    $result_arr[$i][1]='error';

  } else {
    $result_arr[$i][0]=$num;
    $result_arr[$i][1]='succes';
  }

}

$export = new nmsExport([$result_arr]);
return Excel::download($export,'update_IMSI.xlsx');

}

    }
}
