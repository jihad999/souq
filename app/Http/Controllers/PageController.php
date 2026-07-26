<?php

namespace App\Http\Controllers;

use App\Models\ClientLogo;
use App\Models\Partner;

class PageController extends Controller
{
    public function about()
    {
        $clientLogos = ClientLogo::active()->get();
        $partners = Partner::approved()->get();

        return view('about', compact('clientLogos', 'partners'));
    }
}