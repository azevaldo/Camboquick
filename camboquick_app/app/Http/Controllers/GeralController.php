<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GeralController extends Controller
{
    //
    public function home(){
        return view('app.index');
    }
    public function erro(){
        return view('app.erro.critico');
    }
}
