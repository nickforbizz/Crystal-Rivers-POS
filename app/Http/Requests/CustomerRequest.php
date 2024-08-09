<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return (Auth::user()->can('create customer') || Auth::user()->can('edit customer'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'client_ID' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique',
            'phone' => 'nullable',
            'address' => 'nullable',
            'active' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'unique' => ':attribute is already used',
            'required' => 'The :attribute field is required.',
        ];
    }


    public function passedValidation()
    {
        $this->merge([
            'fk_user' => Auth::id()
        ]);
    }
}
