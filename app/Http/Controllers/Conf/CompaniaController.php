<?php

namespace App\Http\Controllers\Conf;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CompaniaController extends Controller
{
    public function index(Request $request){

        return view('conf.compania.index');
    }
}
