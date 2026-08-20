@extends('layouts.admin')
@section('title', 'عرض الرسالة - سوق')
@section('page-title', 'رسالة من: ' . $message->name)

@section('content')

<div class="mb-6">
    <a href="{{ route('admin.messages.index') }}" class="cursor-pointer text-sm text-gray-500 hover:text-accent">← الرجوع لكل الرسائل</a>
</div>

<div class="bg-white rounded-xl shadow p-6">
    <div class="grid md:grid-cols-2 gap-4 mb-6 pb-6 border-b">
        <div>
            <p class="text-gray-500 text-sm mb-1">الاسم</p>
            <p class="font-medium">{{ $message->name }}</p>
        </div>
        <div>
            <p class="text-gray-500 text-sm mb-1">البريد الإلكتروني</p>
            <p class="font-medium text-end" dir="ltr">{{ $message->email }}</p>
        </div>
        <div>
            <p class="text-gray-500 text-sm mb-1">الموضوع</p>
            <p class="font-medium">{{ $message->subject ?? '—' }}</p>
        </div>
        <div>
            <p class="text-gray-500 text-sm mb-1">التاريخ</p>
            <p class="font-medium">{{ $message->created_at->format('Y-m-d H:i') }}</p>
        </div>
    </div>

    <div>
        <p class="text-gray-500 text-sm mb-2">الرسالة</p>
        <p class="leading-7 text-primary">{{ $message->message }}</p>
    </div>

    <div class="mt-6 pt-6 border-t">
        <a href="mailto:{{ $message->email }}" class="cursor-pointer inline-block bg-accent hover:bg-accent-dark text-white font-medium px-5 py-2.5 rounded-lg transition">
            الرد عبر البريد الإلكتروني
        </a>
    </div>
</div>

@endsection