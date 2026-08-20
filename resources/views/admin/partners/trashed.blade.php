@extends('layouts.admin')
@section('title', 'الشركاء المحذوفة - سوق')
@section('page-title', 'سلة المحذوفات - الشركاء')

@section('content')

<div class="flex justify-end mb-6">
    <a href="{{ route('admin.partners.index') }}" class="cursor-pointer text-sm text-gray-500 hover:text-accent">← الرجوع لقائمة الشركاء</a>
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
                        <form action="{{ route('admin.partners.restore', $partner->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="cursor-pointer text-success hover:underline">استرجاع</button>
                        </form>
                        <form action="{{ route('admin.partners.force-delete', $partner->id) }}" method="POST"
                              x-data
                              @submit.prevent="$store.confirm.show('حذف نهائي! هذا الإجراء لا يمكن التراجع عنه. متأكد؟', () => $el.submit())">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="cursor-pointer text-sale hover:underline">حذف نهائي</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="p-8 text-center text-gray-500">سلة المحذوفات فارغة.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $partners->links() }}
</div>

@endsection