<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePartnerRequest;
use App\Models\Partner;

class PartnerController extends Controller
{
    public function store(StorePartnerRequest $request)
    {
        Partner::create([
            ...$request->validated(),
            'status' => 'pending',
        ]);

        return back()->with('partner_success', 'تم إرسال طلب الشراكة بنجاح، رح نراجعه ونتواصل معك.');
    }
}