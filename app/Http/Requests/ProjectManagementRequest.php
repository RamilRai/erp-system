<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ProjectManagementRequest extends FormRequest
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
        $post = $request->only(['_token', 'id', 'project_name', 'pdf', 'project_type', 'client_company_name', 'contact_person',
        'phone_number', 'email', 'project_time_duration', 'start_date_bs', 'start_date_ad', 'end_date_bs', 'end_date_ad', 'project_lead_by', 'assign_team_members', 'project_status']);

        $rules['project_name'] = 'required|max:100';
        $rules['project_type'] = 'required';
        if ($post['project_type'] == 'Client') {
            $rules['client_company_name'] = 'required|max:100';
            $rules['contact_person'] = 'required|max:100';
            $rules['phone_number'] = 'required|max:100';
            $rules['email'] = 'required|email|max:100';
        }
        if ($post['pdf']) {
            $rules['pdf'] = 'mimes:pdf';
        }
        $rules['project_time_duration'] = 'required|max:100';
        $rules['start_date_bs'] = 'required|date';
        $rules['end_date_bs'] = 'required|date|after:start_date_bs';
        $rules['project_lead_by'] = 'required';
        $rules['assign_team_members'] = 'required';
        $rules['project_status'] = 'required';

        return $rules;
    }

    public function messages()
    {
        return [
            'end_date_bs.after' => "Project End date must be greater than it's start date.",
        ];
    }
}
