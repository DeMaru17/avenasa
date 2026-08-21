<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    /**
     * Display the contact and quotation inquiry page.
     */
    public function index(string $locale, Request $request): View
    {
        return view('pages.contact');
    }
}
