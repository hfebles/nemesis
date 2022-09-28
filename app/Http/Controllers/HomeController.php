<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Conf\Exchange;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $dataExchange = Exchange::whereEnabledExchange(1)->orderBy('id_exchange', 'DESC')->get();

        

        //return $dataExchange;

        return view('home', compact('dataExchange'));
    }
}
