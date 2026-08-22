<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Client;
use App\Models\CompanyProfile;
use App\Models\CoreValue;
use App\Models\Management;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Display the about us page.
     */
    public function about(string $locale): View
    {
        $companyProfile = CompanyProfile::first();
        $coreValues = CoreValue::active()->ordered()->get();
        $managements = Management::active()->ordered()->get();

        return view('pages.about', [
            'companyProfile' => $companyProfile,
            'coreValues' => $coreValues,
            'managements' => $managements,
        ]);
    }

    /**
     * Display the partners and clients page.
     */
    public function partnersClients(string $locale): View
    {
        $brands = Brand::active()
            ->withCount(['products' => fn ($query) => $query->where('is_active', true)])
            ->ordered()
            ->get();
        $clients = Client::active()->ordered()->get();
        $companyProfile = CompanyProfile::first();

        return view('pages.partners-clients', [
            'brands' => $brands,
            'clients' => $clients,
            'companyProfile' => $companyProfile,
        ]);
    }
}
