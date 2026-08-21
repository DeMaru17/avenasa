<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Display the about us page.
     */
    public function about(string $locale): View
    {
        return view('pages.about');
    }

    /**
     * Display the partners and clients page.
     */
    public function partnersClients(string $locale): View
    {
        return view('pages.partners-clients');
    }
}
