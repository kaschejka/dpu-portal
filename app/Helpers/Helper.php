<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Excel;
use App\Imports\parseFileImport;
use App\Models\parseFileModel;

class Helper
{
      public static function oprregop($num,$regop)
      {
        $result = array();
        $diap = count($regop);
        $t=0;
        $ngra=0;
        $vgra= $diap;
        while ($diap > 4) {
          $t = $vgra - intdiv(($vgra - $ngra), 2);
          if ($num<$regop[$t][0]) {
            $vgra = $ngra + intdiv(($vgra - $ngra), 2) + 1;
            $diap = $vgra - $ngra;
          }
          else {
            $ngra = $vgra - intdiv(($vgra - $ngra),2);
            $diap = $vgra - $ngra;
          }
        }
        $r=0;

        for ($j=$ngra; $j <$vgra ; $j++) {
          if ($regop[$j][0]<=$num && $num<=$regop[$j][1]) {
            $result[1]=$regop[$j][2];
            $result[2]=$regop[$j][3];
            $r=1;


          }

        }

        if ($r==0) {
          $result[1]="#Н/Д";
          $result[2]="#Н/Д";
        }


             return $result;
      }

public static function parseFile($id, $number, $type ){
  $progress = 0;
  Cache::put($id, ['Парсинг номеров в массив',$progress], now()->addMinutes(3));

if ($type == 'numberString') {
$inputArray= explode(" ",$number);
$onePersent = 100/count($inputArray);

for ($i=0; $i <count($inputArray) ; $i++) {
$progress = $progress + $onePersent;
$numberArray[]=['number'=>$inputArray[$i]];
 Cache::put($id, ['Парсинг номеров в массив',$progress], now()->addMinutes(3));
}

}

if ($type == 'numberFile') {

$data = Excel::toArray(new parseFileImport(), $number);

$onePersent = 100/(count($data[0]) - 1);

  for ($i=1;$i<count($data[0]);$i++){
    $inputArray = implode(",", $data[0][$i]);
    $progress = $progress + $onePersent;
    $numberArray[]=['number'=>$inputArray];
Cache::put($id, ['Парсинг номеров в массив',$progress], now()->addMinutes(3));

  }
}

return $numberArray;
}

}
