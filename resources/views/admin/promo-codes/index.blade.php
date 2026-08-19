@extends('layouts.admin')
@section('title', 'أكواد الخصم - سوق')
@section('page-title', 'أكواد الخصم')

@section('content')

<div class="flex justify-end mb-6">
    <a href="{{ route('admin.promo-codes.create') }}"
       class="cursor-pointer bg-accent hover:bg-accent-dark text-white font-medium px-5 py-2.5 rounded-lg transition">
        + إضافة كود جديد
    </a>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr class="text-right text-gray-500">
                <th class="p-4">الكود</th>
                <th class="p-4">النوع</th>
                <th class="p-4">القيمة</th>
                <th class="p-4">مرات الاستخدام</th>
                <th class="p-4">تاريخ الانتهاء</th>
                <th class="p-4">الحالة</th>
                <th class="p-4">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($promoCodes as $promo)
            <tr class="border-t">
                <td class="p-4 font-mono font-bold text-primary">{{ $promo->code }}</td>
                <td class="p-4">{{ $promo->type === 'percentage' ? 'نسبة مئوية' : 'مبلغ ثابت' }}</td>
                <td class="p-4">{{ $promo->type === 'percentage' ? $promo->value . '%' : number_format($promo->value, 2) . ' ₪' }}</td>
                <td class="p-4">
                    {{ $promo->used_count }} @if($promo->usage_limit) / {{ $promo->usage_limit }} @endif
                </td>
                <td class="p-4 text-gray-500">
                    {{ $promo->expires_at ? $promo->expires_at->format('Y-m-d') : 'بدون تاريخ انتهاء' }}
                </td>
                <td class="p-4">
                    @if($promo->is_active)
                        <span class="text-xs bg-green-50 text-success px-2 py-1 rounded">نشط</span>
                    @else
                        <span class="text-xs bg-gray-100 text-gray-500 px-2 py-1 rounded">غير نشط</span>
                    @endif
                </td>
                <td class="p-4">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.promo-codes.edit', $promo) }}" class="cursor-pointer text-accent hover:underline">تعديل</a>
                        <form action="{{ route('admin.promo-codes.destroy', $promo) }}" method="POST"
                              x-data
                              @submit.prevent="$store.confirm.show('حذف كود \'{{ $promo->code }}\'؟', () => $el.submit())">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="cursor-pointer text-sale hover:underline">حذف</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="p-8 text-center text-gray-500">لا يوجد أكواد خصم بعد.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $promoCodes->links() }}
</div>

@endsection