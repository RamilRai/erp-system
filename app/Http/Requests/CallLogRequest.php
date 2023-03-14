<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CallLogRequest extends FormRequest
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
            'call_date' => 'required',
            'called_by' => 'required|max:100',
            'received_by' => 'required|max:100',
            'remarks' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'call_date.required' => 'Call date is required.',
            'called_by.required' => 'Called by name is required.',
            'called_by.max' => 'Called by name cannot be longer than 100 characters.',
            'received_by.required' => 'Received by name is required.',
            'received_by.max' => 'Received by name cannot be longer than 100 characters.',
            'remarks.required' => 'Remarks is required.',
        ];
    }
}
