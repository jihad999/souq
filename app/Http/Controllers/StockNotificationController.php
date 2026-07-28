<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStockNotificationRequest;
use App\Models\StockNotification;

class StockNotificationController extends Controller
{
    public function store(StoreStockNotificationRequest $request)
    {
        $exists = StockNotification::where('product_id', $request->product_id)
            ->where('email', $request->email)
            ->where('is_notified', false)
            ->exists();

        if ($exists) {
            return $this->respond($request, false, 'أنت مسجل بالفعل لهذا المنتج، رح نبلغك أول ما يتوفر.');
        }

        StockNotification::create([
            'product_id' => $request->product_id,
            'email' => $request->email,
            'quantity' => $request->quantity,
        ]);

        return $this->respond($request, true, 'تم تسجيل طلبك بنجاح، رح نبلغك فور توفر المنتج.');
    }

    private function respond($request, bool $success, string $message)
    {
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => $success, 'message' => $message]);
        }

        return back()->with($success ? 'success' : 'error', $message);
    }
}