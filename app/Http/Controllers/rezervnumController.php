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
use App\Exports\naviborExport;
use Funct\Collection;
use Funct\Strings;

class rezervnumController extends Controller
{

  public function vibor(Request $req) {
    $response = [];
    $temp = Collection\without($req->id, 'abcCh', 'defCh');
    foreach ($temp as $id) {
      if ($id == 'a495' || $id == 'a499') {
        if ($id == 'a495' ) {
        $response =array_merge($response, $this->getPrefix('7495%', 211, 'ABC'));
        }
        if ($id == 'a499' ) {
         $response =array_merge($response, $this->getPrefix('7499%', 211, 'ABC'));
        }

      } else {
        if (Strings\right($id,-1) == 'a') {
          $idVr = substr($id, 1);
        $response =array_merge($response, $this->getPrefix('', $idVr, 'ABC'));
        }
        if (Strings\right($id,-1) == 'd') {
          $idVr = substr($id, 1);
          $response =array_merge($response, $this->getPrefix('', $idVr, 'DEF'));
        }
      }

    }

    $number  = array_column($response,'number_code');
    $region = array_column($response,'region_name');
    $export = new naviborExport([array_map(null, $number, $region)]);
    return Excel::download($export,'navibor.xlsx');
  }

  public function getPrefix($prefix, $gorod, $typeNum)
  {
    $curl = curl_init();
    if ($typeNum == 'ABC') {
      curl_setopt_array($curl, array(
        CURLOPT_URL => env('NMS_URL'),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>"{\"jsonrpc\":\"2.0\",
          \"method\":\"scriptExecute\",\"params\":
          {\"projectName\":\"NMS\",\"reportName\":\"Список номерной емкости со связанными параметрами\",
          \"jsonOperParams\":[{\"paramName\":\"pPhone_Number\",\"paramValue\":\"$prefix\"},
          {\"paramName\":\"mDistr_List\",\"paramValue\":\"8373\"},
          {\"paramName\":\"mRegion_List\",\"paramValue\":\"$gorod\"},
          {\"paramName\":\"mCategory_List\",\"paramValue\":\"1113\"},
          {\"paramName\":\"mProject_List\",\"paramValue\":\"33047\"},
          {\"paramName\":\"mState_List\",\"paramValue\":\"7\"},
          {\"paramName\":\"mNum_Type_List\",\"paramValue\":\"1105\"}]},\"id\":1}",
        CURLOPT_HTTPHEADER => array(
          'Content-Type: application/json'
        ),
      ));
    } else {
      curl_setopt_array($curl, array(
        CURLOPT_URL => env('NMS_URL'),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>"{\"jsonrpc\":\"2.0\",
          \"method\":\"scriptExecute\",\"params\":
          {\"projectName\":\"NMS\",\"reportName\":\"Список номерной емкости со связанными параметрами\",
          \"jsonOperParams\":[{\"paramName\":\"pPhone_Number\",\"paramValue\":\"$prefix\"},
          {\"paramName\":\"mCategory_List\",\"paramValue\":\"1113\"},
          {\"paramName\":\"mRegion_List\",\"paramValue\":\"$gorod\"},
          {\"paramName\":\"mProject_List\",\"paramValue\":\"23784145\"},
          {\"paramName\":\"mState_List\",\"paramValue\":\"7\"},
          {\"paramName\":\"mNum_Type_List\",\"paramValue\":\"1104\"}]},\"id\":1}",
        CURLOPT_HTTPHEADER => array(
          'Content-Type: application/json'
        ),
      ));
    }

    $response = curl_exec($curl);
    curl_close($curl);
    $tt=json_decode($response);
    return $tt->result;
  }

public function getReserveArray($arr, $kol, $daterezerv, $regop, $typeNum)
{
   $arr = json_decode(json_encode($arr), true);
  for ($i=0; $i < $kol; $i++) {

    if ($typeNum == 'ABC') {
      $result[$i]['number'] = $arr[$i]['number_code'];
      $result[$i]['imsi'] = '';
    } else {
      $result[$i]['number'] = $arr[$i]['number_code'];
      $result[$i]['imsi'] = $arr[$i]['imsi'];
    }
    $nm = Helper::oprregop($result[$i]['number'],$regop);
    $result[$i]['operator'] = $nm[1];
    $result[$i]['region'] = $nm[2];
    $result[$i]['daterezerv'] = $daterezerv;
  }
  return $result;
}

public function frezerv ($rnum,$rezdate_sec,$description,$iter)
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
       if ($iter == 0) {
         $pos_er = strripos(json_encode($response),'error');
         $tt = json_decode($response);
         if ($pos_er === false) {
           $uid = $tt->result[0]->reserved_uid;
           $result = [];
           $rnum = explode(",",$rnum);
           for ($i=0; $i < count($rnum); $i++) {
             $result[$i] = $uid;
           }
           return $result;
         } else {
           $result = [];
           $rnum = explode(",",$rnum);
           for ($i=0; $i < count($rnum); $i++) {
             $result[$i] = $this->frezerv($rnum[$i], $rezdate_sec,$description,'1');
           }
           return $result;
         }
       } else {
         $pos_er = strripos(json_encode($response),'error');
         $tt = json_decode($response);
         if ($pos_er === false) {
           return $tt->result[0]->reserved_uid;
         } else {
           $pos_er = strripos(json_encode($response),'free');
           if ($pos_er === false) {
             return 'Number not found!';
           } else {
             return 'Number not free!';
           }
         }
       }
}

    public function reznumsub (Request $req)
    {

$alarmGorod = [];
if (empty($req->dpunum) || empty($req->manager)) {
  return ['alarm'=>'Не заполнены поля DESCRIPTION или ФИО менеджера!'];
}
if (empty($req->number)) {
  return ['alarm'=>'Не выбраны номера'];
}
if ($req->selectInputNum <> 'numberRandom') {
  $numberArray = Helper::parseFile($req->id, $req->number, $req->selectInputNum);
     $result = [];
     $i=0;
    $rzbd = DB::table('regop')->get();
     foreach ($rzbd as $rzbd) {
     $regop[$i][0]='7'.$rzbd->kod.$rzbd->ot;
     $regop[$i][1]='7'.$rzbd->kod.$rzbd->do;
     $regop[$i][2]=$rzbd->operator;
     $regop[$i][3]=$rzbd->region;
     $i++;
     }

      for ($i=0; $i < count($numberArray); $i++) {
        $result[$i]['number'] = $numberArray[$i]['number'];
        $result[$i]['imsi'] = '';
        $nm = Helper::oprregop($numberArray[$i]['number'],$regop);
        $result[$i]['operator'] = $nm[1];
        $result[$i]['region'] = $nm[2];
        $result[$i]['daterezerv'] = $req->daterezerv;
      }
      unset($regop);
      unset($rzbd);
} else {

  $i =0;
  $rzbd = DB::table('regop')->get();
   foreach ($rzbd as $rzbd) {
   $regop[$i][0]='7'.$rzbd->kod.$rzbd->ot;
   $regop[$i][1]='7'.$rzbd->kod.$rzbd->do;
   $regop[$i][2]=$rzbd->operator;
   $regop[$i][3]=$rzbd->region;
   $i++;
   }
   $result= [];

//подготовка массива для резерва рандомно
  foreach ($req->number as $id) {

    if ($id[0] == 'a495' || $id[0] == 'a499') {
      if ($id[0] == 'a495' ) {
      $temp = $this->getPrefix('7495%','211','ABC');
      shuffle($temp);
      if (count($temp) < $id[1]) {
        array_push($alarmGorod, 'МСК495');
      }
        else {
          $result = array_merge($result, $this->getReserveArray($temp, $id[1], $req->daterezerv, $regop, 'ABC'));
        }

      }
      if ($id[0] == 'a499' ) {
       $temp = $this->getPrefix('7499%','211','ABC');
       shuffle($temp);
       if (count($temp) < $id[1]) {
         array_push($alarmGorod, 'МСК499');
       } else {
        $result = array_merge($result, $this->getReserveArray($temp, $id[1], $req->daterezerv, $regop, 'ABC'));
       }

      }
    } else {
      if (Strings\right($id[0],-1) == 'a') {
        $idVr = substr($id[0], 1);
      $temp = $this->getPrefix('',$idVr,'ABC');
      shuffle($temp);
      if (count($temp) < $id[1]) {
        $ag = DB::table('region')->where('id','=', $idVr)->first();
        array_push($alarmGorod, $ag->ru_name_abc);
      } else {
        $temp = json_decode(json_encode($temp), true);
       $filter1 = array_filter($temp, fn($user) => $user['owner_name'] == 'MTT');
       $filter2 = array_filter($temp, fn($user) => $user['owner_name'] <> 'MTT');
         $temp = array_merge($filter1, $filter2);
         $result = array_merge($result, $this->getReserveArray($temp, $id[1], $req->daterezerv, $regop, 'ABC'));
      }

      }
      if (Strings\right($id[0],-1) == 'd') {
        $idVr = substr($id[0], 1);

        if (strstr($id[1], '-')) {
          $prefCol = explode(" ",$id[1]);
          foreach ($prefCol as $prefCol) {
            [$pref, $kol] = explode("-",$prefCol);
            $temp = $this->getPrefix($pref,$idVr,'DEF');

            if ($kol <= count($temp)) {
              shuffle($temp);
             $temp = json_decode(json_encode($temp), true);
            $filter1 = array_filter($temp, fn($user) => $user['owner_name'] == 'MTT');
            $filter2 = array_filter($temp, fn($user) => $user['owner_name'] <> 'MTT');
              $temp = array_merge($filter1, $filter2);
              $result = array_merge($result, $this->getReserveArray($temp, $kol, $req->daterezerv, $regop, 'DEF'));
            } else {
              $ag = DB::table('region')->where('id','=', $idVr)->first();
              array_push($alarmGorod, $ag->ru_name_def.' - '.$pref);
            }
          }

        } else {
          $temp = $this->getPrefix('',$idVr,'DEF');
          if ($id[1] <= count($temp)) {
            shuffle($temp);
           $temp = json_decode(json_encode($temp), true);
          $filter1 = array_filter($temp, fn($user) => $user['owner_name'] == 'MTT');
          $filter2 = array_filter($temp, fn($user) => $user['owner_name'] <> 'MTT');
            $temp = array_merge($filter1, $filter2);
            $result = array_merge($result, $this->getReserveArray($temp, $id[1], $req->daterezerv, $regop, 'DEF'));
        } else {
          $ag = DB::table('region')->where('id','=', $idVr)->first();
          array_push($alarmGorod, $ag->ru_name_def);
        }
      }

      }
    }

  }
//конец подготовки массива для резерва

}
if (empty($result)) {
    return ['alarm'=>implode('|',$alarmGorod)];
}


  //резерв номеров
      $rezdate_sec=strtotime($req->daterezerv)-strtotime('Now');
      $num = '';
      $i = 0;
      $uid= [];
      foreach ($result as $res) {
        $num.=$res['number'].",";
        $i++;
        if ($i % 100 == 0 || $i+1 > count($result)) {
          $num = substr($num,0,-1);
          $uid = [...$uid,...$this->frezerv($num, $rezdate_sec, $req->dpunum,0)];
          $num = '';
        }
      }

      for ($i=0, $l=count($uid); $i < $l ; $i++) {
        if ($uid[$i] == 'Number not found!' || $uid[$i] == 'Number not free!') {
          $result[$i]['uid'] = $uid[$i];
          array_unshift($alarmGorod,'Некоторые номера не зарезервировались!');
        } else {
          $historyrezerv[] = [
            'number' => $result[$i]['number'],
            'description' => $req->dpunum,
            'date_issue' =>date("Y-m-d", strtotime("Now")),
            'end_date'=>$req->daterezerv,
            'author'=> $req->author,
            'manager'=>$req->manager,
            'uid'=>$uid[$i]
          ];
          $result[$i]['uid'] = $uid[$i];
        }
      }

      // запись в БД
      if (isset($historyrezerv)) {
        foreach (array_chunk($historyrezerv,5000) as $historyrezerv)
        {
             DB::table('historyrezerv')->insert($historyrezerv);
              sleep(1);
        }
        $tt=DB::table('manager')->where('FIO', $req->manager)->first();
        if (isset($tt)) {
          $mail=$tt->mail;
        } else {
          $mail=$req->manager;
        }

        $napt=DB::table('napominalka')->where('description','=', $req->dpunum)->first();

        if ($napt == Null) {
        DB::table('napominalka')->insert([
          ['description' => $req->dpunum, 'end_date' => $req->daterezerv,'mail'=>$mail,'company'=>$req->company],
        ]);
        } else {
        $napt=DB::table('napominalka')->where([['description','=', $req->dpunum],
          ['end_date','=',$req->daterezerv]])->first();
          if ($napt == Null) {
            DB::table('napominalka')->insert([
              ['description' => $req->dpunum, 'end_date' => $req->daterezerv,'mail'=>$mail,'company'=>$req->company],
          ]);
          }

        }
      }
$alarmGorod = array_unique($alarmGorod);

      //выдача результатов
        return ['alarm'=>$alarmGorod, 'result'=>$result];



    }

    public function exportFile(Request $req)
    {
      $export = new rezervnumExport([$req->result]);
      return Excel::download($export,$req->dpunum.'.xlsx');
    }
}
