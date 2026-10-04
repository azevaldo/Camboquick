<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('app.paginas.usuarios.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();
       if( Auth::user()->hasAnyRole(['admin','gerente']))
        return redirect('/dashboard1')->with('success','Sessão Iniciada Com Sucesso');
    else if(Auth::user()->hasAnyRole(['caixa']))
    return redirect()->route('dashboard2')->with('success','Sessão Iniciada Com Sucesso');
else
      return redirect('/')->with('error','Não Permitido');
}

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success','Sessão Encerrada Com Sucesso');
    }
}
