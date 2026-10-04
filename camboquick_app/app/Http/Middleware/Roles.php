<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class Roles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if(!(Auth()->check()) || !(Auth::user()->hasAnyRole($roles)))
        return redirect()->back()->with('error','Você não tem permissão para acessar essa pagina');
        //abort(403,'Você não tem permissão para acessar essa pagina');
        return $next($request);
    }
}
