<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePromoCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $promoCodeId = $this->route('promo_code')?->id;

        return [
            'code' => ['required', 'string', 'max:50', 'unique:promo_codes,code,' . $promoCodeId],
            'type' => ['required', 'in:percentage,fixed'],
            'value' => ['required', 'numeric', 'min:0.01'],
            'min_order_amount' => ['nullable', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'starts_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'كود الخصم مطلوب',
            'code.unique' => 'هذا الكود مستخدم مسبقًا',
            'value.required' => 'قيمة الخصم مطلوبة',
            'value.min' => 'قيمة الخصم يجب أن تكون أكبر من صفر',
        ];
    }
}