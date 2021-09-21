<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use ZipArchive;
use Illuminate\Support\Facades\Storage;
use DateTime;


class generatedocController extends Controller
{
    public function generatedoc(Request $req) {


// echo dd($req);

$zip = new ZipArchive();

if (!file_exists('storage/docVB/'.$req->ndog)) {
    mkdir('storage/docVB/'.$req->ndog, 0777, true);
}
$n = strtotime('now');
if (!file_exists('storage/docVB/'.$req->ndog.'/'.$n)) {
    mkdir('storage/docVB/'.$req->ndog.'/'.$n, 0777, true);
}

$zip->open('storage/temp/'.$req->ndog.'.zip', ZipArchive::CREATE);

    $polyapodpisantamtt = DB::table('podpisantmtt')->where('id', $req->podpisantmtt)->first();
    $_monthsList = array(
      ".01." => "января",
      ".02." => "февраля",
      ".03." => "марта",
      ".04." => "апреля",
      ".05." => "мая",
      ".06." => "июня",
      ".07." => "июля",
      ".08." => "августа",
      ".09." => "сентября",
      ".10." => "октября",
      ".11." => "ноября",
      ".12." => "декабря"
    );
     $date = date("d.m.Y", strtotime($req->datedog));
    //Наша задача - вывод русской даты,
    //поэтому заменяем число месяца на название:
    $_mD = date(".m.", strtotime($date)); //для замены
    $date = str_replace($_mD, " ".$_monthsList[$_mD]." ", $date);


        $dogovorvip = new \PhpOffice\PhpWord\TemplateProcessor(env('DOC_PATH').'generateddoc\shablondogdoc\voicebox\ip\dogovor.docx');
        $savedogovorvip = 'storage/docVB/'.$req->ndog.'/'.$n.'/Договор '.$req->ndog.'.docx';
        $dogovorvip ->setValue('podpiRP', $polyapodpisantamtt->podpiRP);
        $dogovorvip ->setValue('osnpodpmtt', $polyapodpisantamtt->osnpodpmtt);
        $dogovorvip ->setValue('dolzhnost', $polyapodpisantamtt->dolzhnost);
        $dogovorvip ->setValue('FIO', $polyapodpisantamtt->FIO);
        $dogovorvip ->setValue('datedog', $date);
        if ($req->sdg == 'endDate') {
          $enddatedog = date("d.m.Y", strtotime($req->srokdog));
          $_mD = date(".m.", strtotime($enddatedog)); //для замены
          $enddatedog = str_replace($_mD, " ".$_monthsList[$_mD]." ", $enddatedog);
          $dogovorvip ->setValue('sdd', "	Договор вступает в силу со дня подписания Сторонами и действует до ".$enddatedog."г. В случае если не позднее чем за 30 (тридцать) дней до истечения срока действия Договора ни одна из Сторон не заявит о своем нежелании продлить срок его действия, действие Договора каждый раз считается автоматически продленным на тот же срок и на тех же условиях, количество пролонгаций не ограничено.");
        } else {
          $dogovorvip ->setValue('sdd', "	Договор вступает в силу со дня подписания Сторонами и действует в течение неопределенного срока.");
        }
        $polevbip= DB::table('generatedoc_pole')->pluck('polevbip');
        foreach ($polevbip as $polevbip) {
          if ($req->$polevbip =='') {
            $dogovorvip ->setValue($polevbip, '_________');
          } else {
              $dogovorvip ->setValue($polevbip, $req->$polevbip);
          }

        }
        $dogovorvip->saveas($savedogovorvip);
         $zip->addFile($savedogovorvip, 'Договор.docx');


        $dsvip = new \PhpOffice\PhpWord\TemplateProcessor(env('DOC_PATH').'generateddoc\shablondogdoc\voicebox\ip\ds.docx');
        $savedsvip = 'storage/docVB/'.$req->ndog.'/'.$n.'/ДС '.$req->ndog.'.docx';
        $dsvip ->setValue('podpiRP', $polyapodpisantamtt->podpiRP);
        $dsvip ->setValue('osnpodpmtt', $polyapodpisantamtt->osnpodpmtt);
        $dsvip ->setValue('dolzhnost', $polyapodpisantamtt->dolzhnost);
        $dsvip ->setValue('FIO', $polyapodpisantamtt->FIO);
        $dsvip ->setValue('datedog', $date);
        $polevbip= DB::table('generatedoc_pole')->pluck('polevbip');
        foreach ($polevbip as $polevbip) {
          if ($req->$polevbip =='') {
            $dsvip ->setValue($polevbip, '_________');
          } else {
              $dsvip ->setValue($polevbip, $req->$polevbip);
          }
        }
        $dsvip->saveas($savedsvip);
        $zip->addFile($savedsvip, 'ДС.docx');

if ($req->sale_ip) {
  $dspvip = new \PhpOffice\PhpWord\TemplateProcessor(env('DOC_PATH').'generateddoc\shablondogdoc\voicebox\ip\ds_price_a.docx');
} else {
  $dspvip = new \PhpOffice\PhpWord\TemplateProcessor(env('DOC_PATH').'generateddoc\shablondogdoc\voicebox\ip\ds_price.docx');

        $savedspvip = 'storage/docVB/'.$req->ndog.'/'.$n.'/ДС со стоимостью'.$req->ndog.'.docx';
        $dspvip ->setValue('podpiRP', $polyapodpisantamtt->podpiRP);
        $dspvip ->setValue('osnpodpmtt', $polyapodpisantamtt->osnpodpmtt);
        $dspvip ->setValue('dolzhnost', $polyapodpisantamtt->dolzhnost);
        $dspvip ->setValue('FIO', $polyapodpisantamtt->FIO);
        $dspvip ->setValue('datedog', $date);
        $polevbip= DB::table('generatedoc_pole')->pluck('polevbip');
        foreach ($polevbip as $polevbip) {
          if ($req->$polevbip =='') {
            $dspvip ->setValue($polevbip, '_________');
          } else {
              $dspvip ->setValue($polevbip, $req->$polevbip);
          }
        }
        $dspvip->saveas($savedspvip);
        $zip->addFile($savedspvip, 'ДС со стоимостью.docx');

if (isset($req->tarif)) {

      if (array_search('shablon', $req->tarif) > -1) {
        $abcvip = new \PhpOffice\PhpWord\TemplateProcessor(env('DOC_PATH').'generateddoc\shablondogdoc\voicebox\ip\shablon_call\abc.docx');
        if (!file_exists('storage/docVB/'.$req->ndog.'/'.$n.'/Шаблонный разговор')) {
            mkdir('storage/docVB/'.$req->ndog.'/'.$n.'/Шаблонный разговор', 0777, true);
        }
        $zip->addemptydir('Шаблонный разговор');
        $saveabcvip = 'storage/docVB/'.$req->ndog.'/'.$n.'/Шаблонный разговор/Приложение ABC'.'.docx';
        if ($req->trp == 'min') {
          $abcvip ->setValue('trp', "Минута");
          $abcvip ->setValue('trpprim', "поминутная, каждая неполная оплачивается как полная.");
        } else {
          $abcvip ->setValue('trp', "Секунда");
          $abcvip ->setValue('trpprim', "посекундная, стоимость соединения округляется до 2 знаков после запятой.");
        }
        $abcvip ->setValue('podpiRP', $polyapodpisantamtt->podpiRP);
        $abcvip ->setValue('osnpodpmtt', $polyapodpisantamtt->osnpodpmtt);
        $abcvip ->setValue('dolzhnost', $polyapodpisantamtt->dolzhnost);
        $abcvip ->setValue('FIO', $polyapodpisantamtt->FIO);
        $abcvip ->setValue('datedog', $date);
        $polevbip= DB::table('generatedoc_pole')->pluck('polevbip');
        foreach ($polevbip as $polevbip) {
          if ($req->$polevbip =='') {
            $abcvip ->setValue($polevbip, '_________');
          } else {
              $abcvip ->setValue($polevbip, $req->$polevbip);
          }

        }
        $abcvip->saveas($saveabcvip);
        $zip->addFile($saveabcvip, 'Шаблонный разговор/Приложение ABC.docx');


        $defvip = new \PhpOffice\PhpWord\TemplateProcessor(env('DOC_PATH').'generateddoc\shablondogdoc\voicebox\ip\shablon_call\def.docx');
        $savedefvip = 'storage/docVB/'.$req->ndog.'/'.$n.'/Шаблонный разговор/Приложение DEF'.'.docx';
        $defvip ->setValue('podpiRP', $polyapodpisantamtt->podpiRP);
        $defvip ->setValue('osnpodpmtt', $polyapodpisantamtt->osnpodpmtt);
        $defvip ->setValue('dolzhnost', $polyapodpisantamtt->dolzhnost);
        $defvip ->setValue('FIO', $polyapodpisantamtt->FIO);
        $defvip ->setValue('datedog', $date);
        if ($req->trp == 'min') {
          $defvip ->setValue('trp', "Минута");
          $defvip ->setValue('trpprim', "поминутная, каждая неполная оплачивается как полная.");
        } else {
          $defvip ->setValue('trp', "Секунда");
          $defvip ->setValue('trpprim', "посекундная, стоимость соединения округляется до 2 знаков после запятой.");
        }
        $polevbip= DB::table('generatedoc_pole')->pluck('polevbip');
        foreach ($polevbip as $polevbip) {
          if ($req->$polevbip =='') {
            $defvip ->setValue($polevbip, '_________');
          } else {
              $defvip ->setValue($polevbip, $req->$polevbip);
          }

        }
        $defvip->saveas($savedefvip);
        $zip->addFile($savedefvip, 'Шаблонный разговор/Приложение DEF.docx');


        unset($saveabcvip, $savedefvip, $defvip, $abcvip);
      }

      if (array_search('personal', $req->tarif) > -1) {
        $abcvip = new \PhpOffice\PhpWord\TemplateProcessor(env('DOC_PATH').'generateddoc\shablondogdoc\voicebox\ip\personal_notification\abc.docx');
        if (!file_exists('storage/docVB/'.$req->ndog.'/'.$n.'/Персональное уведомление')) {
            mkdir('storage/docVB/'.$req->ndog.'/'.$n.'/Персональное уведомление', 0777, true);
        }
        $zip->addemptydir('Персональное уведомление');
        $saveabcvip = 'storage/docVB/'.$req->ndog.'/'.$n.'/Персональное уведомление/Приложение ABC'.'.docx';
        $abcvip ->setValue('podpiRP', $polyapodpisantamtt->podpiRP);
        $abcvip ->setValue('osnpodpmtt', $polyapodpisantamtt->osnpodpmtt);
        $abcvip ->setValue('dolzhnost', $polyapodpisantamtt->dolzhnost);
        $abcvip ->setValue('FIO', $polyapodpisantamtt->FIO);
        $abcvip ->setValue('datedog', $date);
        if ($req->trp == 'min') {
          $abcvip ->setValue('trp', "Минута");
          $abcvip ->setValue('trpprim', "поминутная, каждая неполная оплачивается как полная.");
        } else {
          $abcvip ->setValue('trp', "Секунда");
          $abcvip ->setValue('trpprim', "посекундная, стоимость соединения округляется до 2 знаков после запятой.");
        }
        $polevbip= DB::table('generatedoc_pole')->pluck('polevbip');
        foreach ($polevbip as $polevbip) {
          if ($req->$polevbip =='') {
            $abcvip ->setValue($polevbip, '_________');
          } else {
              $abcvip ->setValue($polevbip, $req->$polevbip);
          }

        }
        $abcvip->saveas($saveabcvip);
        $zip->addFile($saveabcvip, 'Персональное уведомление/Приложение ABC.docx');


        $defvip = new \PhpOffice\PhpWord\TemplateProcessor(env('DOC_PATH').'generateddoc\shablondogdoc\voicebox\ip\personal_notification\def.docx');
        $savedefvip = 'storage/docVB/'.$req->ndog.'/'.$n.'/Персональное уведомление/Приложение DEF'.'.docx';
        $defvip ->setValue('podpiRP', $polyapodpisantamtt->podpiRP);
        $defvip ->setValue('osnpodpmtt', $polyapodpisantamtt->osnpodpmtt);
        $defvip ->setValue('dolzhnost', $polyapodpisantamtt->dolzhnost);
        $defvip ->setValue('FIO', $polyapodpisantamtt->FIO);
        $defvip ->setValue('datedog', $date);
        if ($req->trp == 'min') {
          $defvip ->setValue('trp', "Минута");
          $defvip ->setValue('trpprim', "поминутная, каждая неполная оплачивается как полная.");
        } else {
          $defvip ->setValue('trp', "Секунда");
          $defvip ->setValue('trpprim', "посекундная, стоимость соединения округляется до 2 знаков после запятой.");
        }
        $polevbip= DB::table('generatedoc_pole')->pluck('polevbip');
        foreach ($polevbip as $polevbip) {
          if ($req->$polevbip =='') {
            $defvip ->setValue($polevbip, '_________');
          } else {
              $defvip ->setValue($polevbip, $req->$polevbip);
          }

        }
        $defvip->saveas($savedefvip);
        $zip->addFile($savedefvip, 'Персональное уведомление/Приложение DEF.docx');
                unset($saveabcvip, $savedefvip, $defvip, $abcvip);
      }

      if (array_search('interact', $req->tarif) > -1) {
        $abcvip = new \PhpOffice\PhpWord\TemplateProcessor(env('DOC_PATH').'generateddoc\shablondogdoc\voicebox\ip\interactive_conversation\abc.docx');
        if (!file_exists('storage/docVB/'.$req->ndog.'/'.$n.'/Интерактивный разговор')) {
            mkdir('storage/docVB/'.$req->ndog.'/'.$n.'/Интерактивный разговор', 0777, true);
        }
        $zip->addemptydir('Интерактивный разговор');
        $saveabcvip = 'storage/docVB/'.$req->ndog.'/'.$n.'/Интерактивный разговор/Приложение ABC'.'.docx';
        $abcvip ->setValue('podpiRP', $polyapodpisantamtt->podpiRP);
        $abcvip ->setValue('osnpodpmtt', $polyapodpisantamtt->osnpodpmtt);
        $abcvip ->setValue('dolzhnost', $polyapodpisantamtt->dolzhnost);
        $abcvip ->setValue('FIO', $polyapodpisantamtt->FIO);
        $abcvip ->setValue('datedog', $date);
        if ($req->trp == 'min') {
          $abcvip ->setValue('trp', "Минута");
          $abcvip ->setValue('trpprim', "поминутная, каждая неполная оплачивается как полная.");
        } else {
          $abcvip ->setValue('trp', "Секунда");
          $abcvip ->setValue('trpprim', "посекундная, стоимость соединения округляется до 2 знаков после запятой.");
        }
        $polevbip= DB::table('generatedoc_pole')->pluck('polevbip');
        foreach ($polevbip as $polevbip) {
          if ($req->$polevbip =='') {
            $abcvip ->setValue($polevbip, '_________');
          } else {
              $abcvip ->setValue($polevbip, $req->$polevbip);
          }

        }
        $abcvip->saveas($saveabcvip);
        $zip->addFile($saveabcvip, 'Интерактивный разговор/Приложение ABC.docx');


        $defvip = new \PhpOffice\PhpWord\TemplateProcessor(env('DOC_PATH').'generateddoc\shablondogdoc\voicebox\ip\interactive_conversation\def.docx');
        $savedefvip = 'storage/docVB/'.$req->ndog.'/'.$n.'/Интерактивный разговор/Приложение DEF'.'.docx';
        $defvip ->setValue('podpiRP', $polyapodpisantamtt->podpiRP);
        $defvip ->setValue('osnpodpmtt', $polyapodpisantamtt->osnpodpmtt);
        $defvip ->setValue('dolzhnost', $polyapodpisantamtt->dolzhnost);
        $defvip ->setValue('FIO', $polyapodpisantamtt->FIO);
        $defvip ->setValue('datedog', $date);
        if ($req->trp == 'min') {
          $defvip ->setValue('trp', "Минута");
          $defvip ->setValue('trpprim', "поминутная, каждая неполная оплачивается как полная.");
        } else {
          $defvip ->setValue('trp', "Секунда");
          $defvip ->setValue('trpprim', "посекундная, стоимость соединения округляется до 2 знаков после запятой.");
        }
        $polevbip= DB::table('generatedoc_pole')->pluck('polevbip');
        foreach ($polevbip as $polevbip) {
          if ($req->$polevbip =='') {
            $defvip ->setValue($polevbip, '_________');
          } else {
              $defvip ->setValue($polevbip, $req->$polevbip);
          }

        }
        $defvip->saveas($savedefvip);
        $zip->addFile($savedefvip, 'Интерактивный разговор/Приложение DEF.docx');
        unset($saveabcvip, $savedefvip, $defvip, $abcvip);
      }

      if (array_search('kdu', $req->tarif) > -1) {
        if (!file_exists('storage/docVB/'.$req->ndog.'/'.$n.'/Вызовы на 8800')) {
            mkdir('storage/docVB/'.$req->ndog.'/'.$n.'/Вызовы на 8800', 0777, true);
        }
        $kduvip = new \PhpOffice\PhpWord\TemplateProcessor(env('DOC_PATH').'generateddoc\shablondogdoc\voicebox\ip\call_8800\kdu.docx');
        $savekduvip = 'storage/docVB/'.$req->ndog.'/'.$n.'/Вызовы на 8800/Приложение KDU'.'.docx';
        $zip->addemptydir('Вызовы на 8800');
        $kduvip ->setValue('podpiRP', $polyapodpisantamtt->podpiRP);
        $kduvip ->setValue('osnpodpmtt', $polyapodpisantamtt->osnpodpmtt);
        $kduvip ->setValue('dolzhnost', $polyapodpisantamtt->dolzhnost);
        $kduvip ->setValue('FIO', $polyapodpisantamtt->FIO);
        $kduvip ->setValue('datedog', $date);
        if ($req->trp == 'min') {
          $kduvip ->setValue('trp', "Минута");
          $kduvip ->setValue('trpprim', "поминутная, каждая неполная оплачивается как полная.");
        } else {
          $kduvip ->setValue('trp', "Секунда");
          $kduvip ->setValue('trpprim', "посекундная, стоимость соединения округляется до 2 знаков после запятой.");
        }
        $polevbip= DB::table('generatedoc_pole')->pluck('polevbip');
        foreach ($polevbip as $polevbip) {
          if ($req->$polevbip =='') {
            $kduvip ->setValue($polevbip, '_________');
          } else {
              $kduvip ->setValue($polevbip, $req->$polevbip);
          }

        }
        $kduvip->saveas($savekduvip);
        $zip->addFile($savekduvip, 'Вызовы на 8800/Приложение KDU.docx');
      }


    }

$zip->close();

return response()->download('storage/temp/'.$req->ndog.'.zip','Документы по '.$req->ndog.'.zip')->deleteFileAfterSend(true);


    }
}
