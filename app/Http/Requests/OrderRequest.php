<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return (Auth::user()->can('create order') || Auth::user()->can('edit order'));
    }




    /**
     * prepareForValidation function
     *
     * @return void
     */
    public function prepareForValidation()
    {
        $order_number =  date('Ymd').'/'.sprintf("%03d",$this->fk_customer).'/'.strtoupper(Str::random(5));
        $this->merge([
            'fk_user' => Auth::id(),
            'order_ID' => $order_number,
            'amount' => 0,
            'status' => 'Initiated'
        ]);
    }





    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fk_user' => 'required|exists:users,id',
            'fk_customer' => 'required|exists:customers,id',
            'order_ID' => 'required',
            'order_date' => 'required',
            'status' => 'required',
            'amount' => 'nullable',
            'active' => 'nullable',
        ];
    }
}
