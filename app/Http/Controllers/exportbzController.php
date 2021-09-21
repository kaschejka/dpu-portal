<?php



namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\bzExport;
use Excel;
use App\Http\Controllers\parsebzController;

class exportbzController extends Controller
{


    public function export()
  {
    $myarray = session('bz_ar');

   //$myarray = json_decode($_GET["result_arr"]);
      $export = new bzExport([$myarray]);

      return Excel::download($export,'exportBZ.xlsx');
  }

}
