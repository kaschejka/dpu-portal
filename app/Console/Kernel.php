<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderShipped;
use App\Mail\OrderShipped1;
use App\Mail\vitrinamttb;
use App\Mail\vitrinavbabc;
use App\Mail\vitrinavbdef;
use Excel;
use App\Exports\mttbExport;
use Illuminate\Support\Facades\Storage;
use PHPHtmlParser\Dom;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */

     function updFas($urlFas)
          {
          $fp = [];
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => $urlFas,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_SSL_VERIFYPEER => false,
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $a = explode("\n",$response);
            for ($i=1; $i < count($a)-1 ; $i++) {
            $ad = explode(";",$a[$i]);
            $fp[]=[
            'kod'=>$ad[0],
            'ot'=>$ad[1],
            'do'=>$ad[2],
            'kolvo'=>$ad[3],
            'operator'=>$ad[4],
            'region'=>$ad[5]
            ];
            }
            foreach (array_chunk($fp,10000) as $t)
            {
                 DB::table('regop')->insert($t);
                  sleep(1);
            }
          }

          public function getPrefix($prefix, $gorod, $project, $category, $typeNum)
          {
            $curl = curl_init();
            if ($project == '33047') {
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
                  {\"paramName\":\"mCategory_List\",\"paramValue\":\"$category\"},
                  {\"paramName\":\"mProject_List\",\"paramValue\":\"$project\"},
                  {\"paramName\":\"mState_List\",\"paramValue\":\"7\"},
                  {\"paramName\":\"mNum_Type_List\",\"paramValue\":\"$typeNum\"}]},\"id\":1}",
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
                  {\"paramName\":\"mCategory_List\",\"paramValue\":\"$category\"},
                  {\"paramName\":\"mRegion_List\",\"paramValue\":\"$gorod\"},
                  {\"paramName\":\"mProject_List\",\"paramValue\":\"$project\"},
                  {\"paramName\":\"mState_List\",\"paramValue\":\"7\"},
                  {\"paramName\":\"mNum_Type_List\",\"paramValue\":\"$typeNum\"}]},\"id\":1}",
                CURLOPT_HTTPHEADER => array(
                  'Content-Type: application/json'
                ),
              ));
            }

            $response = curl_exec($curl);
            curl_close($curl);
            $tt=json_decode($response);
            return count($tt->result);
          }

    protected function schedule(Schedule $schedule)
    {
      //Напоминалка менеджерам
      $schedule->call(function () {

        $nap=  DB::table('napominalka')->get();
        foreach ($nap as $nap) {
          $interval = strtotime($nap->end_date)-strtotime(date("Y-m-d", strtotime("Now")));
        if ( $interval == 691200 ) {
        Mail::to($nap->mail)->send(new OrderShipped($nap->description));
      }
      if ($interval == 172800) {
          Mail::to($nap->mail)->send(new OrderShipped1($nap->description));

        }
          if (strtotime("Now")>strtotime($nap->end_date)) {
            DB::table('napominalka')->where('id','=', $nap->id)->delete();
          }

        }


      })->dailyAt('8:00')->runInBackground();

      //Обновление диапазонов по ФАС
      $schedule->call(function () {

        DB::table('regop')->truncate();
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://rossvyaz.gov.ru/deyatelnost/resurs-numeracii/vypiska-iz-reestra-sistemy-i-plana-numeracii',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'GET',
              CURLOPT_SSL_VERIFYPEER => false,
            ));

            $response = curl_exec($curl);
            curl_close($curl);
        $dom = new Dom;
            $dom->loadStr($response);
            $contents = $dom->find('.doc__download-list');
            foreach ($contents as $content)
        {

        $a = $content->find('a');
        $csv = $a->getAttribute('href');
        if (substr($csv, -3) == 'csv') {
          $this->updFas($csv);
        }
        }
  })->twiceMonthly(1, 16, '04:00')->runInBackground();

      //формирование отчет_витрина_МТТБ
      $schedule->call(function () {

        $result=[];
        DB::table('ostatok_num')->truncate();
            $rzbd = DB::table('region')->where('abc_activate','=', true)->get();
            $msk495 = $this->getPrefix('7495%', '211', '33047', '1113', '1105');
            $result[]=[
              'gorod' => 'MSK495',
              'regular' => $msk495,
              'bronze' => $this->getPrefix('7495%', '211', '33047', '1114', '1105'),
              'silver'=>$this->getPrefix('7495%', '211', '33047', '1115', '1105'),
              'gold'=>$this->getPrefix('7495%', '211', '33047', '1116', '1105'),
              'platinum'=>$this->getPrefix('7495%', '211', '33047', '1117', '1105'),
              'exclusive'=>$this->getPrefix('7495%', '211', '33047', '1118', '1105'),
            ];
            DB::table('ostatok_num')->insert([
              ['id_region' => '495', 'project' => 'mttb','col_abc' => $msk495],
          ]);
          $msk499 = $this->getPrefix('7499%', '211', '33047', '1113', '1105');
            $result[]=[
              'gorod' => 'MSK499',
              'regular' => $msk499,
              'bronze' => $this->getPrefix('7499%', '211', '33047', '1114', '1105'),
              'silver'=>$this->getPrefix('7499%', '211', '33047', '1115', '1105'),
              'gold'=>$this->getPrefix('7499%', '211', '33047', '1116', '1105'),
              'platinum'=>$this->getPrefix('7499%', '211', '33047', '1117', '1105'),
              'exclusive'=>$this->getPrefix('7499%', '211', '33047', '1118', '1105'),
            ];
            DB::table('ostatok_num')->insert([
              ['id_region' => '499', 'project' => 'mttb','col_abc' => $msk499],
          ]);
            foreach ($rzbd as $rzbd) {
              $regular = $this->getPrefix('', $rzbd->id, '33047', '1113', '1105');
              $result[]=[
                'gorod' => $rzbd->ru_name_abc,
                'regular' => $regular,
                'bronze' => $this->getPrefix('', $rzbd->id, '33047', '1114', '1105'),
                'silver'=>$this->getPrefix('', $rzbd->id, '33047', '1115', '1105'),
                'gold'=>$this->getPrefix('', $rzbd->id, '33047', '1116', '1105'),
                'platinum'=>$this->getPrefix('', $rzbd->id, '33047', '1117', '1105'),
                'exclusive'=>$this->getPrefix('', $rzbd->id, '33047', '1118', '1105'),
              ];
              DB::table('ostatok_num')->insert([
                ['id_region' => $rzbd->id, 'project' => 'mttb','col_abc' => $regular],
            ]);
            }
  $export = new mttbExport([$result]);
  Excel::store($export,'отчет_витрина_МТТБ.xlsx');
   Mail::to('monitoring_numbers@mtt.ru')->send(new vitrinamttb());

})->daily()->runInBackground();

//отчет_витрина_VB_ABC
 $schedule->call(function () {

   $result=[];
         $rzbd = DB::table('region')->where('abc_activate','=', true)->get();
         $result[]=[
           'gorod' => 'MSK495',
           'regular' => $this->getPrefix('7495%', '211', '30882722', '1113', '1105'),
           'bronze' => $this->getPrefix('7495%', '211', '30882722', '1114', '1105'),
           'silver'=>$this->getPrefix('7495%', '211', '30882722', '1115', '1105'),
           'gold'=>$this->getPrefix('7495%', '211', '30882722', '1116', '1105'),
           'platinum'=>$this->getPrefix('7495%', '211', '30882722', '1117', '1105'),
           'exclusive'=>$this->getPrefix('7495%', '211', '30882722', '1118', '1105'),
         ];
         $result[]=[
           'gorod' => 'MSK499',
           'regular' => $this->getPrefix('7499%', '211', '30882722', '1113', '1105'),
           'bronze' => $this->getPrefix('7499%', '211', '30882722', '1114', '1105'),
           'silver'=>$this->getPrefix('7499%', '211', '30882722', '1115', '1105'),
           'gold'=>$this->getPrefix('7499%', '211', '330882722', '1116', '1105'),
           'platinum'=>$this->getPrefix('7499%', '211', '30882722', '1117', '1105'),
           'exclusive'=>$this->getPrefix('7499%', '211', '30882722', '1118', '1105'),
         ];
         foreach ($rzbd as $rzbd) {
           $result[]=[
             'gorod' => $rzbd->ru_name_abc,
             'regular' => $this->getPrefix('', $rzbd->id, '30882722', '1113', '1105'),
             'bronze' => $this->getPrefix('', $rzbd->id, '30882722', '1114', '1105'),
             'silver'=>$this->getPrefix('', $rzbd->id, '30882722', '1115', '1105'),
             'gold'=>$this->getPrefix('', $rzbd->id, '30882722', '1116', '1105'),
             'platinum'=>$this->getPrefix('', $rzbd->id, '30882722', '1117', '1105'),
             'exclusive'=>$this->getPrefix('', $rzbd->id, '30882722', '1118', '1105'),
           ];
         }

   $export = new mttbExport([$result]);
   Excel::store($export,'отчет_витрина_VB_ABC.xlsx');
   Mail::to('monitoring_numbers@mtt.ru')->send(new vitrinavbabc());


 })->dailyAt('1:00')->runInBackground();

//отчет_витрина_VB_DEF
 $schedule->call(function () {

   $result=[];
          $rzbd = DB::table('region')->where('def_activate','=', true)->get();
          foreach ($rzbd as $rzbd) {
            $result[]=[
              'gorod' => $rzbd->ru_name_def,
              'regular' => $this->getPrefix('', $rzbd->id, '30882722', '1113', '1104'),
              'bronze' => $this->getPrefix('', $rzbd->id, '30882722', '1114', '1104'),
              'silver'=>$this->getPrefix('', $rzbd->id, '30882722', '1115', '1104'),
              'gold'=>$this->getPrefix('', $rzbd->id, '30882722', '1116', '1104'),
              'platinum'=>$this->getPrefix('', $rzbd->id, '30882722', '1117', '1104'),
              'exclusive'=>$this->getPrefix('', $rzbd->id, '30882722', '1118', '1104'),
            ];
          }

   $export = new mttbExport([$result]);
   Excel::store($export,'отчет_витрина_VB_DEF.xlsx');
   Mail::to('monitoring_numbers@mtt.ru')->send(new vitrinavbdef());


 })->dailyAt('2:00')->runInBackground();


 $schedule->call(function () {
   //витрина Гнездо
       $rzbd = DB::table('region')->where('def_activate','=', true)->get();

       foreach ($rzbd as $rzbd) {
           $col = $this->getPrefix('', $rzbd->id, '23784145', '1113', '1104');
           DB::table('ostatok_num')->insert([
             ['id_region' => $rzbd->id, 'project' => 'Gnezdo','col_def' => $col],
         ]);
       }
     })->dailyAt('03:00')->runInBackground();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

}
