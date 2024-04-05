<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TiposProdutoController extends Controller
{
    //
    public function index(Request $request){
        return view('tipos');
    }

}
