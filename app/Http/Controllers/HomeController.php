<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Client;
use App\Models\CompanyProfile;
use App\Models\HeroBanner;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the home page with CMS data.
     */
    public function index(string $locale): View
    {
        $heroBanners = HeroBanner::active()->ordered()->get();
        $hero = $heroBanners->first();
        $companyProfile = CompanyProfile::first();
        $featuredProducts = Product::with(['category', 'brand'])->active()->featured()->ordered()->take(12)->get();
        $brands = Brand::active()->ordered()->take(12)->get();
        $clients = Client::active()->ordered()->take(12)->get();

        return view('pages.home', compact('heroBanners', 'hero', 'companyProfile', 'featuredProducts', 'brands', 'clients'));
    }
}
