<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class PortfolioController extends Controller
{
    function payment() {
    	return view('portfolio.payment');
    }
}
