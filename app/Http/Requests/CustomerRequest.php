<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return (Auth::user()->can('create customer') || Auth::user()->can('edit customer'));
    }

    public function prepareForValidation()
    {
        // Concatenate first_name and last_name to form the names attribute
        $this->merge([
            'fk_user' => Auth::id(),
            'client_ID' => strtoupper(substr($this->first_name, 0, 1) . substr($this->last_name, 0, 1) . Str::random(5)),
            'names' => trim($this->first_name . ' ' . $this->last_name),
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
            'fk_user' => 'required',
            'client_ID' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'names' => 'nullable',
            'email' => 'required|min:2|unique:customers,email',
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
