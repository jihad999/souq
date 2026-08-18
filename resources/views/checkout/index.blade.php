@extends('layouts.app')
@section('title', 'إتمام الطلب - سوق')

@section('content')
    <div class="container mx-auto px-4 py-10">

        <h1 class="text-3xl font-bold text-primary mb-8">إتمام الطلب</h1>

        @if(session('error'))
            <div class="bg-red-50 text-sale border border-red-200 rounded-lg p-4 mb-6">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('checkout.store') }}" method="POST" class="grid md:grid-cols-3 gap-8" x-data="checkoutMap()" x-init="init()" @submit="if (paymentMethod !== 'cod') { latitude = ''; longitude = ''; }">
            @csrf

            {{-- بيانات الشحن + الدفع --}}
            <div class="md:col-span-2 space-y-6">

                <div class="bg-white rounded-xl shadow p-6">
                    <h2 class="text-lg font-bold text-primary mb-4">بيانات الشحن</h2>

                    <div class="grid md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">الاسم الكامل</label>
                            <input type="text" name="customer_name" value="{{ old('customer_name') }}"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none" required>
                            @error('customer_name') <p class="text-sale text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">البريد الإلكتروني</label>
                            <input type="email" name="customer_email" value="{{ old('customer_email') }}"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none" required>
                            @error('customer_email') <p class="text-sale text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">رقم الهاتف</label>
                        <input type="text" name="customer_phone" value="{{ old('customer_phone') }}"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none" required>
                        @error('customer_phone') <p class="text-sale text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">عنوان الشحن الكامل</label>
                        <textarea name="shipping_address" rows="3"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:outline-none" required>{{ old('shipping_address') }}</textarea>
                        @error('shipping_address') <p class="text-sale text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- خريطة تحديد الموقع (تظهر بس لو COD) --}}
                <div class="bg-white rounded-xl shadow p-6" x-show="paymentMethod === 'cod'" x-cloak>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold text-primary">حدد موقعك على الخريطة</h2>
                        <button type="button" @click="locateMe()"
                                class="cursor-pointer text-sm text-accent hover:underline flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zM12 2v3m0 14v3M2 12h3m14 0h3"/>
                            </svg>
                            استخدم موقعي الحالي
                        </button>
                    </div>

                    <p class="text-sm text-gray-500 mb-4">
                        حرّك الدبوس على الخريطة عشان تحدد موقعك بالضبط، هاد بيساعدنا نوصلك بسرعة أكتر.
                    </p>

                    <div id="checkout-map" style="height: 350px; border-radius: 12px;" class="mb-3"></div>

                    <template x-if="paymentMethod === 'cod'">
                        <div>
                            <input type="hidden" name="latitude" x-model="latitude">
                            <input type="hidden" name="longitude" x-model="longitude">
                        </div>
                    </template>

                    <p class="text-xs text-gray-400" x-show="latitude && longitude">
                        الإحداثيات المحددة: <span x-text="latitude"></span>, <span x-text="longitude"></span>
                    </p>
                </div>

                {{-- طريقة الدفع --}}
                <div class="bg-white rounded-xl shadow p-6">
                    <h2 class="text-lg font-bold text-primary mb-4">طريقة الدفع</h2>

                    <div class="space-y-3">
                        <label class="flex items-center gap-3 border rounded-lg p-4 cursor-pointer hover:border-accent transition has-[:checked]:border-accent has-[:checked]:bg-accent/5">
                            <input type="radio" name="payment_method" value="cod" x-model="paymentMethod"
                                {{ old('payment_method', 'cod') == 'cod' ? 'checked' : '' }} class="cursor-pointer">
                            <div>
                                <span class="font-medium text-primary block">الدفع عند الاستلام</span>
                                <span class="text-xs text-gray-500">ادفع نقدًا عند وصول الطلب</span>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 border rounded-lg p-4 cursor-pointer hover:border-accent transition has-[:checked]:border-accent has-[:checked]:bg-accent/5">
                            <input type="radio" name="payment_method" value="stripe" x-model="paymentMethod"
                                {{ old('payment_method') == 'stripe' ? 'checked' : '' }} class="cursor-pointer">
                            <div>
                                <span class="font-medium text-primary block">بطاقة ائتمان (Stripe)</span>
                                <span class="text-xs text-gray-500">فيزا / ماستركارد</span>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 border rounded-lg p-4 cursor-pointer hover:border-accent transition has-[:checked]:border-accent has-[:checked]:bg-accent/5">
                            <input type="radio" name="payment_method" value="paytabs" x-model="paymentMethod"
                                {{ old('payment_method') == 'paytabs' ? 'checked' : '' }} class="cursor-pointer">
                            <div>
                                <span class="font-medium text-primary block">PayTabs</span>
                                <span class="text-xs text-gray-500">بوابة دفع عربية</span>
                            </div>
                        </label>

                        @error('payment_method') <p class="text-sale text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- ملخص الطلب --}}
            <div class="bg-white rounded-xl shadow p-6 h-fit sticky top-20">
                <h2 class="text-xl font-bold text-primary mb-6">ملخص الطلب</h2>

                <div class="space-y-3 mb-6 max-h-64 overflow-y-auto">
                    @foreach($cart->items as $item)
                        <div class="flex justify-between text-sm">
                            <div>
                                <span class="text-gray-600">{{ $item->product->name }} × {{ $item->quantity }}</span>
                                @if($item->variant)
                                    <span class="block text-xs text-gray-400">{{ $item->variant->label }}</span>
                                @endif
                            </div>
                            <span class="font-medium">{{ number_format(($item->variant ? $item->variant->final_price : $item->product->final_price) * $item->quantity, 2) }} ₪</span>
                        </div>
                    @endforeach
                </div>

                <div class="space-y-3 mb-6 border-t pt-4">
                    <div class="flex justify-between text-gray-600">
                        <span>المجموع الفرعي</span>
                        <span>{{ number_format($cart->subtotal, 2) }} ₪</span>
                    </div>
                    @if($discount > 0)
                    <div class="flex justify-between text-success">
                        <span>الخصم ({{ $promoCode->code }})</span>
                        <span>- {{ number_format($discount, 2) }} ₪</span>
                    </div>
                    @endif
                    <div class="flex justify-between text-primary font-bold text-lg border-t pt-3">
                        <span>الإجمالي</span>
                        <span>{{ number_format($total, 2) }} ₪</span>
                    </div>
                </div>

                <button type="submit"
                        class="cursor-pointer w-full bg-accent hover:bg-accent-dark text-white font-semibold py-3 rounded-lg transition">
                    تأكيد الطلب
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        function checkoutMap() {
            return {
                paymentMethod: '{{ old('payment_method', 'cod') }}',
                latitude: null,
                longitude: null,
                map: null,
                marker: null,

                init() {
                    this.$watch('paymentMethod', (value) => {
                        if (value === 'cod') {
                            this.$nextTick(() => this.initMap());
                        } else {
                            // امسح الإحداثيات لما يبدل لطريقة دفع غير COD
                            this.latitude = null;
                            this.longitude = null;
                        }
                    });

                    if (this.paymentMethod === 'cod') {
                        this.$nextTick(() => this.initMap());
                    }
                },

                initMap() {
                    if (this.map) {
                        this.map.invalidateSize();
                        return;
                    }

                    // نابلس كمركز افتراضي (تقدر تغيرها لمركز مدينتك)
                    const defaultLat = 32.2211;
                    const defaultLng = 35.2544;

                    this.map = L.map('checkout-map').setView([defaultLat, defaultLng], 13);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap contributors',
                    }).addTo(this.map);

                    this.marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(this.map);

                    this.latitude = defaultLat;
                    this.longitude = defaultLng;

                    this.marker.on('dragend', () => {
                        const pos = this.marker.getLatLng();
                        this.latitude = pos.lat.toFixed(7);
                        this.longitude = pos.lng.toFixed(7);
                    });

                    this.map.on('click', (e) => {
                        this.marker.setLatLng(e.latlng);
                        this.latitude = e.latlng.lat.toFixed(7);
                        this.longitude = e.latlng.lng.toFixed(7);
                    });
                },

                locateMe() {
                    if (! navigator.geolocation) {
                        alert('متصفحك ما بيدعم تحديد الموقع.');
                        return;
                    }

                    navigator.geolocation.getCurrentPosition((position) => {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        this.latitude = lat.toFixed(7);
                        this.longitude = lng.toFixed(7);

                        if (this.map && this.marker) {
                            this.map.setView([lat, lng], 15);
                            this.marker.setLatLng([lat, lng]);
                        }
                    }, () => {
                        alert('تعذر الوصول لموقعك، تأكد من السماح بالوصول للموقع من إعدادات المتصفح.');
                    });
                },
            };
        }
    </script>
@endpush