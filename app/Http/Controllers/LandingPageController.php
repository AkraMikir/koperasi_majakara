<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    /**
     * Show the services page.
     */
    public function layanan()
    {
        return view('landing.layanan');
    }

    /**
     * Show the benefits page.
     */
    public function keuntungan()
    {
        return view('landing.keuntungan');
    }

    /**
     * Show the testimonials page.
     */
    public function testimoni()
    {
        return view('landing.testimoni');
    }

    /**
     * Show the FAQ page.
     */
    public function faq()
    {
        return view('landing.faq');
    }
}
