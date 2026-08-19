@extends('layouts.admin')
@section('title', 'إدارة الشركاء - سوق')
@section('page-title', 'طلبات الشراكة')

@section('content')

<div class="flex justify-end mb-6">
    <a href="{{ route('admin.partners.trashed') }}" class="cursor-pointer text-sm text-gray-500 hover:text-sale flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
        </svg>
        سلة المحذوفات
    </a>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr class="text-right text-gray-500">
                <th class="p-4">الشركة</th>
                <th class="p-4">المسؤول</th>
                <th class="p-4">البريد الإلكتروني</th>
                <th class="p-4">الهاتف</th>
                <th class="p-4">الحالة</th>
                <th class="p-4">الظهور بالموقع</th>
                <th class="p-4">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($partners as $partner)
            <tr class="border-t">
                <td class="p-4 font-medium text-primary">{{ $partner->company_name }}</td>
                <td class="p-4">{{ $partner->contact_name }}</td>
                <td class="p-4" dir="ltr">{{ $partner->email }}</td>
                <td class="p-4" dir="ltr">{{ $partner->phone }}</td>
                <td class="p-4">
                    @switch($partner->status)
                        @case('pending')
                            <span class="text-xs bg-yellow-50 text-yellow-600 px-2 py-1 rounded">قيد المراجعة</span>
                            @break
                        @case('approved')
                            <span class="text-xs bg-green-50 text-success px-2 py-1 rounded">تمت الموافقة</span>
                            @break
                        @case('rejected')
                            <span class="text-xs bg-red-50 text-sale px-2 py-1 rounded">مرفوض</span>
                            @break
                    @endswitch
                </td>
                <td class="p-4">
                    @if($partner->status === 'approved')
                    <form action="{{ route('admin.partners.toggle-visibility', $partner) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="cursor-pointer text-xs {{ $partner->show_on_site ? 'text-success' : 'text-gray-400' }} hover:underline">
                            {{ $partner->show_on_site ? 'ظاهر' : 'مخفي' }}
                        </button>
                    </form>
                    @else
                        <span class="text-xs text-gray-300">—</span>
                    @endif
                </td>
                <td class="p-4">
                    <div class="flex items-center gap-3">
                        @if($partner->status === 'pending')
                        <form action="{{ route('admin.partners.approve', $partner) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="cursor-pointer text-success hover:underline">موافقة</button>
                        </form>
                        <form action="{{ route('admin.partners.reject', $partner) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="cursor-pointer text-sale hover:underline">رفض</button>
                        </form>
                        @endif
                        <form action="{{ route('admin.partners.destroy', $partner) }}" method="POST"
                              x-data
                              @submit.prevent="$store.confirm.show('حذف طلب الشراكة هذا؟', () => $el.submit())">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="cursor-pointer text-gray-400 hover:text-sale hover:underline">حذف</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="p-8 text-center text-gray-500">لا يوجد طلبات شراكة بعد.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $partners->links() }}
</div>

@endsection