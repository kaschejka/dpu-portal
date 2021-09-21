<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\bzRequest;
use Illuminate\Support\Facades\DB;

class parsebzController extends Controller
{

  public function parsebz(bzRequest $req) {
$j=0;
$kolbz=0;
$result_arr = array();
$word_arr = array();
$wa=0;
foreach ($req->filenum as $file){


$objReader = \PhpOffice\PhpWord\IOFactory::createReader('Word2007');
$phpWord = $objReader->load($file);
$kolbz++;
$kod='';
//$tt1 = $phpWord->getSections()[0]->getElements()[10]->getRows()[0]->getCells()[1]->getElements()[0]->getElements()[3]->getText();
$cell=$phpWord->getSections()[0]->getElements()[13]->getRows()[1]->getCells()[0]->getElements()[0];
foreach($cell->getElements() as $text) {
 $kod .= $text->getText();
 }
 $kod = trim($kod);
 $kod = substr($kod,-3);


$diap='';
//$tt1 = $phpWord->getSections()[0]->getElements()[10]->getRows()[0]->getCells()[1]->getElements()[0]->getElements()[3]->getText();
$cell=$phpWord->getSections()[0]->getElements()[10]->getRows()[0]->getCells()[1]->getElements()[0];
foreach($cell->getElements() as $text) {
 $diap .= $text->getText();
}
$diap = trim($diap);
$diap = str_replace(' ','',$diap);


$gorod='';
//$tt1 = $phpWord->getSections()[0]->getElements()[10]->getRows()[0]->getCells()[1]->getElements()[0]->getElements()[3]->getText();
$cell=$phpWord->getSections()[0]->getElements()[18]->getRows()[1]->getCells()[1]->getElements()[0];
foreach($cell->getElements() as $text) {
 $gorod .= $text->getText();
}

$word_arr[$wa][0]=$gorod;
$word_arr[$wa][1]=$kod;

$zap =substr_count($diap, ',');
$y=0;
$k=0;
$sn='';
$a=explode(',',$diap);
while ($y<=$zap) {
  if (substr_count($a[$y],'-')<>0) {
    $b = explode('-',$a[$y]);
    while ($b[0]<=$b[1]) {
      $result_arr[$j][0]='7'.$kod.$b[0];
      $k++;
      $sn=$sn.'7'.$kod.$b[0]."\r\n";
      $rzbd = DB::table('regop')->select('operator', 'region')->where([['kod', $kod],['ot','<=',$b[0]],['do','>=',$b[0]]])->first();

      if ($rzbd == null) {
        $result_arr[$j][1] ='Null';
        $result_arr[$j][2] ='Null';
        $result_arr[$j][3] =$gorod;
      } else {
        $result_arr[$j][1] =$rzbd->operator;
        $result_arr[$j][2] =$rzbd->region;
        $result_arr[$j][3] =$gorod;
      }
      $b[0]++;
      $j++;
    }
  }else {
    $result_arr[$j][0]='7'.$kod.$a[$y];
    $k++;
    $sn=$sn.'7'.$kod.$a[$y]."\r\n";
    $rzbd = DB::table('regop')->select('operator', 'region')->where([['kod', $kod],['ot','<=',$a[$y]],['do','>=',$a[$y]]])->first();

    if ($rzbd == null) {
      $result_arr[$j][1] ='Null';
      $result_arr[$j][2] ='Null';
      $result_arr[$j][3] =$gorod;
    } else {
      $result_arr[$j][1] =$rzbd->operator;
      $result_arr[$j][2] =$rzbd->region;
      $result_arr[$j][3] =$gorod;
    }
    $j++;
  }

  $y++;

}
$word_arr[$wa][2]=$sn;
$word_arr[$wa][3]=$k;
$wa++;
}

$i=0;
$k = count($result_arr[1]);
$chekbtn = $_POST['customRadioInline1'];
if ("rweb" == $chekbtn ) {

  echo "
<p><a href=/bz >Вернуться назад</a></p>
  <table border=1 >
    <tr> <td>Номер</td> <td>Оператор</td> <td>Регион</td><td>Регион из БЗ</td></tr>
";
for ($i=1; $i <count($result_arr) ; $i++) {
  $n = $result_arr[$i][0];
  $o = $result_arr[$i][1];
  $r= $result_arr[$i][2];
  $rbz= $result_arr[$i][3];
echo "<tr> <td>$n</td> <td>$o</td> <td>$r</td><td>$rbz</td></tr>";
}
echo "</table>
<p><a href=/bz >Вернуться назад</a></p>";

}
if ("rfile" == $chekbtn) {

  session(['bz_ar' => $result_arr]);
  return redirect()->action( [exportbzController::class, 'export']);
}

if ("rord" == $chekbtn) {
  $PHPWordORD = new \PhpOffice\PhpWord\TemplateProcessor(env('DOC_PATH').'generateddoc\shablonord\vimpelcom.docx');

  $planName = env('DOC_PATH').'generateddoc\generatedord\ord'.strtotime('now').'.docx';
$PHPWordORD->cloneRow('gorod', $kolbz);



  $gorodORD='';
$gor_arr = array_unique(array_column(@$result_arr,3));
  foreach ($gor_arr as $value) {
  $gorodORD=$gorodORD.$value.',';
  }
$i=0;
for ($i=0; $i < count($word_arr); $i++) {
  $PHPWordORD ->setValue('gorod#'.($i+1), $word_arr[$i][0]);
  $PHPWordORD ->setValue('kod#'.($i+1), $word_arr[$i][1]);
  $PHPWordORD ->setValue('num#'.($i+1), $word_arr[$i][2]);
  $PHPWordORD ->setValue('kolvo#'.($i+1), $word_arr[$i][3]);
}


  $PHPWordORD ->setValue('gorodORD', $gorodORD);

    $PHPWordORD ->setValue('data1',date("d.m.yy",strtotime("+1 week")));
    $PHPWordORD ->setValue('data2',date("d.m.yy",strtotime("first day of next month")));

  $PHPWordORD->saveas($planName);

return response()->download($planName);

}


  }
}
