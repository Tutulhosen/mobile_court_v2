<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SottogolpoController extends Controller
{
    //

    public function index(){
        return view('sottogolpo.list');
    }
    public function create(){
        return view('sottogolpo.createGolpo');
    }
}
