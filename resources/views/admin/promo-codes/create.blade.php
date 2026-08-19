@extends('layouts.admin')
@section('title', 'إضافة كود خصم - سوق')
@section('page-title', 'إضافة كود خصم جديد')

@section('content')

<form action="{{ route('admin.promo-codes.store') }}" method="POST">
    @csrf

    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <div class="grid md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium mb-1">الكود</label>
                <input type="text" name="code" value="{{ old('code') }}" placeholder="مثال: SUMMER25"
                       class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none uppercase">
                @error('code') <p class="text-sale text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">نوع الخصم</label>
                <select name="type" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none">
                    <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>نسبة مئوية (%)</option>
                    <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>مبلغ ثابت (₪)</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">قيمة الخصم</label>
                <input type="number" step="0.01" name="value" value="{{ old('value') }}"
                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none">
                @error('value') <p class="text-sale text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">الحد الأدنى لقيمة الطلب (اختياري)</label>
                <input type="number" step="0.01" name="min_order_amount" value="{{ old('min_order_amount') }}"
                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">حد الاستخدام (اختياري)</label>
                <input type="number" name="usage_limit" value="{{ old('usage_limit') }}" placeholder="اتركه فارغًا لاستخدام غير محدود"
                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">تاريخ البدء (اختياري)</label>
                <input type="datetime-local" name="starts_at" value="{{ old('starts_at') }}"
                       class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">تاريخ الانتهاء (اختياري)</label>
                <input type="datetime-local" name="expires_at" value="{{ old('expires_at') }}"
                       class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none">
                @error('expires_at') <p class="text-sale text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="cursor-pointer">
            <span class="text-sm">كود نشط (قابل للاستخدام)</span>
        </label>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="cursor-pointer bg-accent hover:bg-accent-dark text-white font-semibold px-6 py-3 rounded-lg transition">
            حفظ الكود
        </button>
        <a href="{{ route('admin.promo-codes.index') }}" class="cursor-pointer bg-gray-100 hover:bg-gray-200 text-primary font-semibold px-6 py-3 rounded-lg transition">
            إلغاء
        </a>
    </div>
</form>

@endsection