<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\CollectionstdClass;
use Illuminate\Support\Facades\DB;
use Funct\Collection;
use Funct\Strings;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\createTask;


class taskdpuController extends Controller
{

   public function task (Request $req)
    {

      $selectnum = array();
      $subjtypeNum = array();
      array_push($selectnum, $req->company);
      array_push($selectnum, $req->typeTrafic);
      array_push($selectnum, 'Описание: '.$req->description);
      array_push($selectnum, 'SMS на DEF номерах: '.$req->defsms);
      array_push($selectnum, 'Регион и количество номеров: ');
      // $selectnum = implode("|",$selectnum);
      // return $selectnum;
      foreach ($req->selectnum as $id) {
        if ($id[0] == 'a495' || $id[0] == 'a499') {
          if ($id[0] == 'a495' ) {
            $t =
          array_push($selectnum, 'Москва 495 - '.$id[1]);
          array_push($subjtypeNum, 'ABC');
          }
          if ($id[0] == 'a499' ) {
           array_push($selectnum, 'Москва 499 - '.$id[1]);
           array_push($subjtypeNum, 'ABC');
          }

        }
        else {
          if (Strings\right($id[0],-1) == 'a') {
            $idVr = substr($id[0], 1);
            $g=DB::table('region')->where('id', $idVr)->first();
            $g=$g->ru_name_abc;
          array_push($selectnum, $g.' - '.$id[1]);
          array_push($subjtypeNum, 'ABC');
          }
          if (Strings\right($id[0],-1) == 'd') {
            if ($id[0] == 'd211') {
              array_push($selectnum, 'Москва (Московская область) - '.$id[1]);
              array_push($subjtypeNum, 'DEF');
            } else {
              $idVr = substr($id[0], 1);
              $g=DB::table('region')->where('id', $idVr)->first();
              $g=$g->ru_name_def;
            array_push($selectnum, $g.' - '.$id[1]);
            array_push($subjtypeNum, 'DEF');
            }

          }
        }

      }
      $subjtypeNum = array_unique($subjtypeNum);
      if (in_array("DEF", $subjtypeNum)) {
    if ($req->defsms == 'ДА') {
      array_push($subjtypeNum, '(СМС)');
    }
}
$subjtypeNum = implode("|",$subjtypeNum);
$selectnum = str_replace('|','\\n',implode("|",$selectnum));
$summary = "Портал: ".$req->company.' - '.$subjtypeNum;
$manager = explode('@',$req->manager);
$manager = $manager[0];
$pass = Cache::get($req->manager);
$jiraAuth = base64_encode($manager.':'.$pass);
$curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => env('URL_JIRA').'rest/api/2/issue/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>"{
          \"fields\": {
             \"project\":
             {
                \"key\": \"DPUNUM\"
             },
             \"summary\": \"$summary\",
             \"description\": \"$selectnum\",
             \"issuetype\": {
                \"name\": \"Task\"
             }
         }
      }",
        CURLOPT_HTTPHEADER => array(
          "Authorization: Basic $jiraAuth",
          'Content-Type: application/json',
        ),
      ));

      $response = json_decode(curl_exec($curl));
      curl_close($curl);

      if (!isset($response->key)) {
        Cache::forget($req->manager);
        return 'error_create_task';
      }
      $opoveshenie = "num-notif@mtt.ru";
$dpunum =  $response->key;
Mail::to($opoveshenie)->send(new createTask($dpunum));
if (!empty($req->watcher)) {
  $curl = curl_init();
  $url = env('URL_JIRA')."rest/api/2/issue/".$dpunum."/watchers";
  curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>"\"$req->watcher\"",
    CURLOPT_HTTPHEADER => array(
      "Authorization: Basic $jiraAuth",
      'Content-Type: application/json'
    ),
  ));

  $response = curl_exec($curl);

  curl_close($curl);
}



$temp = ['company' => $req->company,
          'typeTrafic' => $req->typeTrafic,
          'description' => $req->description,
          'defsms' => $req->defsms,
          'selectnum' => $req->selectnum
        ];

$historyrezerv[] = [
  'DPUNUM' => $dpunum,
  'manager'=>$req->manager,
  'worker' => 0,
  'state' => 'Открыто',
  'options'=>json_encode($temp)
];
 DB::table('task_dpunum')->insert($historyrezerv);

return $dpunum;

    }

  public function seeTask ($task)
  {
    $zapros = DB::table('task_dpunum')->where('DPUNUM',$task)->first();
    // $options = $zapros->options;

     return view('opentask',['task'=>json_decode($zapros->options),'manager'=>$zapros->manager,'DPUNUM'=>$task]);
  }

  public function closeTask (Request $req)
  {
    DB::table('task_dpunum')->where('DPUNUM',$req->task)->delete();
     return 'closed';
  }

  public function getSesionJira (Request $req)
  {
    $curl = curl_init();
$username = explode('@',$req->username);
    curl_setopt_array($curl, array(
      CURLOPT_URL => env('URL_JIRA').'rest/auth/1/session',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>"{
        \"username\": \"$username[0]\",
        \"password\": \"$req->password\"
    }",
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);

if (Strings\contains(json_encode($response), 'error')) {
  Cache::forget($req->username);
  return 'false';
} else {
  Cache::put($req->username, $req->password, now()->addMinutes(720));
  return 'true';
}

  }



}
