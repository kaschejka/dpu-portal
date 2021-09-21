<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Imports\rezervnumImport;
use App\Models\rezervnumModel;
use Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\CollectionstdClass;
use App\Helpers\Helper;
use App\Exports\getFollowMeExport;
use App\Exports\reservUidExport;
use App\Exports\nmsExport;
use Funct;

class GnezdoController extends Controller
{
    public function getfollowMe (Request $req)
    {

      if (empty($req->rs)) {
         return redirect()->back()->withErrors(['Не выбран вариант перадчи номеров списком или через файл!']);
      }
      if (empty($req->fl) && $req->rs == 'fnum') {
       return redirect()->back()->withErrors(['Не выбран файл с номерами!']);
      }
      if (empty($req->number) && $req->rs == 'num') {
       return redirect()->back()->withErrors(['Не выбраны номера!']);
      }

      DB::table('log')->insert([
        ['FIO' => auth()->user()->email, 'operation' => 'getfollowme','date'=>date('Y-m-d H:i:s',strtotime("Now"))],
    ]);

       $result = [];

       if ($req->rs == 'fnum') {
         $data = Excel::toArray(new rezervnumImport(), $req->fl);
         $size = count($data[0]);
         if ($size == 1) {
           return redirect()->back()->withErrors(['В файле нет номеров!']);
         }
         for ($i=1;$i<$size;$i++){
           $ra = implode(",", $data[0][$i]);
           $num[]=['number'=>$ra];
         }
       }
       if ($req->rs == 'num') {
         $ra= explode(" ",$req->number);
          for ($i=0; $i <count($ra) ; $i++) {
            $num[]=['number'=>$ra[$i]];
          }
       }
$auth = base64_encode($req->login.":".$req->pass);
$num = Funct\Collection\firstN($num,100);
foreach ($num as $num) {
  $number = $num['number'];
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => env('WEB_API_MTT'),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => false,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>"{
      \"id\": \"1\",
      \"jsonrpc\": \"2.0\",
      \"method\": \"getFollowme\",
      \"params\": {
          \"sip_id\":\"$number\"
      }
  }",
    CURLOPT_HTTPHEADER => array(
      "Authorization: Basic $auth",
      'Content-Type: application/json',
    ),
  ));
  $response = curl_exec($curl);
  curl_close($curl);
if (json_decode($response) == '') {
  return redirect()->back()->withErrors(['Неверный логин или пароль агента!']);
}

if (Funct\Strings\contains(json_encode($response), 'error')) {
  $result[] = array_merge($num,(array) 'Ошибочный номер');
} else {
  $tt = json_decode($response);
  $tt = $tt->result->followme_struct[1][0];
  $result[] = array_merge($num,(array)$tt);
}
sleep(2);
}

$export = new getFollowMeExport([$result]);
return Excel::download($export,'getFollowMe.xlsx');
    }

public function reservuid(Request $req)
{
  if (empty($req->rs1)) {
     return redirect()->back()->withErrors(['Не выбран вариант перадчи номеров списком или через файл!']);
  }
  if (empty($req->fl1) && $req->rs1 == 'fnum1') {
   return redirect()->back()->withErrors(['Не выбран файл с номерами!']);
  }
  if (empty($req->number1) && $req->rs1 == 'num1') {
   return redirect()->back()->withErrors(['Не выбраны номера!']);
  }

  $result = [];

  if ($req->rs1 == 'fnum1') {
    $data = Excel::toArray(new rezervnumImport(), $req->fl1);
    $size = count($data[0]);
    if ($size == 1) {
      return redirect()->back()->withErrors(['В файле нет номеров!']);
    }
    for ($i=1;$i<$size;$i++){
      $ra = implode(",", $data[0][$i]);
      $num[]=['number'=>$ra];
    }
  }
  if ($req->rs1 == 'num1') {
    $ra= explode(" ",$req->number1);
     for ($i=0; $i <count($ra) ; $i++) {
       $num[]=['number'=>$ra[$i]];
     }
  }
 $result = [];
  foreach ($num as $num) {
      $number = $num['number'];
      $temp = DB::table('historyrezerv')
            ->select('number', 'description', 'manager', 'uid')
            ->where('number', '=', $number)
            ->latest('uid')
            ->first();
    if ($temp == null) {
      $temp['number'] = $number;
      $temp['description'] = '';
      $temp['manager'] = '';
      $temp['uid'] = 'error';
      $result[] = $temp;
    } else {
      $result[] = (array)$temp;
    }
    unset($temp);
  }

  $export = new reservUidExport([$result]);
  return Excel::download($export,'UID.xlsx');
}

public function markerKarusel (Request $req)
{

  if (empty($req->rs2)) {
     return redirect()->back()->withErrors(['Не выбран вариант перадчи номеров списком или через файл!']);
  }
  if (empty($req->fl2) && $req->rs2 == 'fnum2') {
   return redirect()->back()->withErrors(['Не выбран файл с номерами!']);
  }
  if (empty($req->number2) && $req->rs2 == 'num2') {
   return redirect()->back()->withErrors(['Не выбраны номера!']);
  }
  if (!Funct\Strings\contains($req->login2, '126')) {
    return redirect()->back()->withErrors(['Неверный логин! Логин должен быть от 126 env.']);
  }

  DB::table('log')->insert([
    ['FIO' => auth()->user()->email, 'operation' => 'markerKarusel','date'=>date('Y-m-d H:i:s',strtotime("Now"))],
]);

   $result = [];

   if ($req->rs2 == 'fnum2') {
     $data = Excel::toArray(new rezervnumImport(), $req->fl2);
     $size = count($data[0]);
     if ($size == 1) {
       return redirect()->back()->withErrors(['В файле нет номеров!']);
     }
     for ($i=1;$i<$size;$i++){
       $ra = implode(",", $data[0][$i]);
       $num[]=['number'=>$ra];
     }
   }
   if ($req->rs2 == 'num2') {
     $ra= explode(" ",$req->number2);
      for ($i=0; $i <count($ra) ; $i++) {
        $num[]=['number'=>$ra[$i]];
      }
   }
   if ($req->item_id == 'yes') {
    $db_val = 1;
  } else {
    $db_val = 0;
  }
  $curl = curl_init();
foreach ($num as $num) {



  curl_setopt_array($curl, array(
    CURLOPT_URL => env('Porta_URL').'Account/get_account_info',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => false,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => 'auth_info=%7B%22login%22%3A%20%22'.$req->login2.'%22%2C%20%22password%22%3A%20%22'.$req->pass2.'%22%7D&params=%7B%22id%22%3A%22'.$num['number'].'%22%7D',
    CURLOPT_HTTPHEADER => array(
      'Content-Type: application/x-www-form-urlencoded'
    ),
  ));

  $response = curl_exec($curl);


  if (Funct\Strings\contains($response, 'Login or password is incorrect')) {
    return redirect()->back()->withErrors(['Неверный логин или пароль!']);
  }
  if ($response == '{}') {
    $result[] = ['number' => $num['number'], 'result' =>'error'];
  } else {
    $i_acc = (array)json_decode($response);
    $i_acc = $i_acc['account_info']->i_account;


    curl_setopt_array($curl, array(
      CURLOPT_URL => env('Porta_URL').'Account/update_custom_fields_values',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => false,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => 'auth_info=%7B%22login%22%3A%20%22'.$req->login2.'%22%2C%20%22password%22%3A%20%22'.$req->pass2.'%22%7D&params=%7B%22i_account%22%3A%20'.$i_acc.'%2C%20%22custom_fields_values%22%3A%20%5B%0A%20%20%20%20%20%20%20%20%7B%0A%20%20%20%20%20%20%20%20%20%20%20%20%22i_custom_field%22%3A%208577%2C%0A%20%20%20%20%20%20%20%20%20%20%20%20%22text_value%22%3A%20%22'.$req->item_id.'%22%2C%0A%20%20%20%20%20%20%20%20%20%20%20%20%22name%22%3A%20%22Carousel%22%2C%0A%20%20%20%20%20%20%20%20%20%20%20%20%22db_value%22%3A%20%22'.$db_val.'%22%0A%20%20%20%20%20%20%20%20%7D%5D%7D',
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/x-www-form-urlencoded'
      ),
    ));

    $response = curl_exec($curl);


    $result[] = ['number' => $num['number'], 'result' =>'OK'];
  }

}
curl_close($curl);
$export = new nmsExport([$result]);
return Excel::download($export,'result.xlsx');

}

}
