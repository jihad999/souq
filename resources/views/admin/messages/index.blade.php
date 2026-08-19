@extends('layouts.admin')
@section('title', 'رسائل التواصل - سوق')
@section('page-title', 'رسائل التواصل')

@section('content')

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr class="text-right text-gray-500">
                <th class="p-4"></th>
                <th class="p-4">الاسم</th>
                <th class="p-4">البريد الإلكتروني</th>
                <th class="p-4">الموضوع</th>
                <th class="p-4">التاريخ</th>
                <th class="p-4">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($messages as $message)
            <tr class="border-t {{ ! $message->is_read ? 'bg-orange-50/30' : '' }}">
                <td class="p-4">
                    @if(! $message->is_read)
                        <span class="w-2 h-2 bg-accent rounded-full inline-block"></span>
                    @endif
                </td>
                <td class="p-4 font-medium text-primary">{{ $message->name }}</td>
                <td class="p-4" dir="ltr">{{ $message->email }}</td>
                <td class="p-4">{{ $message->subject ?? '—' }}</td>
                <td class="p-4 text-gray-500">{{ $message->created_at->format('Y-m-d H:i') }}</td>
                <td class="p-4">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.messages.show', $message) }}" class="cursor-pointer text-accent hover:underline">عرض</a>
                        <form action="{{ route('admin.messages.destroy', $message) }}" method="POST"
                              x-data
                              @submit.prevent="$store.confirm.show('حذف هذه الرسالة؟', () => $el.submit())">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="cursor-pointer text-sale hover:underline">حذف</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="p-8 text-center text-gray-500">لا يوجد رسائل بعد.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $messages->links() }}
</div>

@endsection