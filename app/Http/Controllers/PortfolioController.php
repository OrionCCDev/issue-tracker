<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    /**
     * Display the engineer portfolio page.
     */
    public function index()
    {
        return view('portfolio');
    }
}
