<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'company_name' => 'required|max:100',
            'owner_name' => 'required|max:100',
            'email' => 'required|email|max:100',
            'address' => 'required|max:100',
            'mobile_number' => 'required|max:20',
            'landline_number' => 'max:20',
            'service_id' => 'required',
            'service_name' => 'required|max:100',
            'domain_name' => 'max:100',
            'company_website' => 'max:100',
            'contracted_date' => 'required|date',
            'contract_end_date' => 'required|date|after:contracted_date',
            'contracted_by' => 'required|max:100',
        ];
    }

    public function messages()
    {
        return [
            'contract_end_date.after' => "Contract End date must be greater than it's start date.",
        ];
    }
}
