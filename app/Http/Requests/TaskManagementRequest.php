<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class TaskManagementRequest extends FormRequest
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

        $post = $request->all();

        $rules['task_title'] = 'required|max:100';
        if($post['id'] == null){
            $rules['project_id'] = 'required';
        }
        $rules['task_type'] = 'required';
        $rules['task_start_date_bs'] = 'required|date';
        $rules['task_end_date_bs'] = 'required|date|after:task_start_date_bs';
        $rules['estimated_hour'] = 'required|max:100';
        $rules['priority'] = 'required';
        $rules['assigned_to'] = 'required';
        $rules['task_point'] = 'required';

        return $rules;
    }

    public function messages()
    {
        return [
            'task_end_date_bs.after' => "Task End date must be greater than it's start date.",
        ];
    }
}
