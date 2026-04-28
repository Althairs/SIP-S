<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Display the landing page.
     */
    public function index()
    {
        return view('public.index');
    }

    /**
     * Display the schedule page.
     */
    public function jadwal()
    {
        return view('public.jadwal');
    }
}
