<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\User;

class role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
//      public function handle($request, Closure $next, $role)
//     {
//       if ($request->user() && $request->user()->role == 'Null')
//       {
//           return response()->View('dashboard');
//       }
//       return $next($request);
//     }
// }

public function handle($request, Closure $next,...$roles)
{

        $userRole = $request->user();

        if($userRole && $userRole->count() > 0)
        {
            $userRole = $userRole->role;
            // $checkRole = 0;
            // if($userRole == $role && $role =='admin')
            // {
            //     $checkRole = 1;
            // }
            // elseif($userRole == $role && $role == 'manager')
            // {
            //     $checkRole = 1;
            // }
            // if ($userRole <> '' && $role == 'all') {
            //   $checkRole = 1;
            // }
            //
            // if($checkRole == 1)
            //     return $next($request);
            // else
            foreach($roles as $role){
        if ($userRole == $role){
            return $next($request);
        }
    }
               return redirect('dashboard');
        }
        else
        {
            return redirect('login');
        }


    }
}
