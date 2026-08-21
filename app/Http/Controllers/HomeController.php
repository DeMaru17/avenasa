<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use App\Models\HeroBanner;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the home page with CMS data.
     */
    public function index(string $locale): View
    {
        $hero = HeroBanner::active()->ordered()->first();
        $companyProfile = CompanyProfile::first();

        return view('pages.home', compact('hero', 'companyProfile'));
    }
}
