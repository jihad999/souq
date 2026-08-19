<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PartnerApprovedMail;
use App\Models\Partner;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::latest()->paginate(15);

        return view('admin.partners.index', compact('partners'));
    }

    public function approve(Partner $partner)
    {
        $partner->update([
            'status' => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        try {
            Mail::to($partner->email)->send(new PartnerApprovedMail($partner));
        } catch (\Exception $e) {
            \Log::error('Failed to send partner approval email: ' . $e->getMessage());
        }

        return back()->with('success', 'تمت الموافقة على الشراكة وإرسال إشعار للشريك.');
    }

    public function reject(Partner $partner)
    {
        $partner->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'تم رفض طلب الشراكة.');
    }

    public function toggleVisibility(Partner $partner)
    {
        $partner->update(['show_on_site' => ! $partner->show_on_site]);

        return back()->with('success', 'تم تحديث ظهور الشريك بالموقع.');
    }

    public function destroy(Partner $partner)
    {
        $partner->delete();

        return back()->with('success', 'تم نقل طلب الشراكة للمحذوفات.');
    }

    public function trashed()
    {
        $partners = Partner::onlyTrashed()->latest('deleted_at')->paginate(15);

        return view('admin.partners.trashed', compact('partners'));
    }

    public function restore($id)
    {
        Partner::onlyTrashed()->findOrFail($id)->restore();

        return back()->with('success', 'تم استرجاع طلب الشراكة بنجاح.');
    }

    public function forceDelete($id)
    {
        $partner = Partner::onlyTrashed()->findOrFail($id);

        if ($partner->logo) {
            Storage::disk('public')->delete($partner->logo);
        }

        $partner->forceDelete();

        return back()->with('success', 'تم حذف طلب الشراكة نهائيًا.');
    }
}