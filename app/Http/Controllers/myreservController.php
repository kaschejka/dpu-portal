<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\Helper;

class myreservController extends Controller
{
    public function rez(Request $req)
    {
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

$hmodel=[];
$h = DB::table('historyrezerv')->where('description','=', $req->description)->get();
 foreach ($h as $h) {
   $nm = Helper::oprregop($h->number,$regop);
   $hmodel[] = ['number'=>$h->number,
 'operator'=>$nm[1],
'region'=>$nm[2]];
 }

     return view('my_reserved',['hmodel'=>$hmodel]);
    }
}
