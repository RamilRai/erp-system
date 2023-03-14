<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class LeadRequest extends FormRequest
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
    public function rules(Request $request)
    {
        $post = $request->only(['id', 'organization_type', 'organization_name', 'address', 'email',
                                'contact_number', 'mobile_number', 'lead_by_name', 'lead_date', 'lead_status']);

        $rules['organization_type'] = 'required';
        $rules['organization_name'] = 'required|max:100';
        $rules['address'] = 'required|max:100';
        $rules['email'] = 'required|email|max:50';
        $rules['contact_number'] = 'required';
        if ($post['mobile_number']) {
            $rules['mobile_number'] = 'min:10';
        }
        $rules['lead_by_name'] = 'required|max:100';
        $rules['lead_date'] = 'required';
        $rules['lead_status'] = 'required';

        return $rules;
    }

    public function messages()
    {
        return [
            'organization_type.required' => 'Organization type is required',
            'organization_name.required' => 'Organization type is required',
            'organization_name.max' => 'Organization type cannot be longer than 100 characters',
            'address.required' => 'Address is required',
            'address.max' => 'Address cannot be longer than 100 characters',
            'email.required' => 'Email is required',
            'email.email' => 'Email must be valid',
            'email.max' => 'Email cannot be longer than 50 characters',
            'contact_number.required' => 'Contact Number is required',
            'mobile_number.min' => 'Mobile Number cannot be less than 10 characters',
            'lead_by_name.required' => 'Lead by name is required',
            'lead_by_name.max' => 'Lead by name cannot be longer than 100 characters',
            'lead_date.required' => 'Lead date is required',
            'lead_status.required' => 'Lead status is required'
        ];
    }
}
