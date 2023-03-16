<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
    public function rules()
    {
        return [
            'task_title' => 'required|max:100',
            'project_id' => 'required',
            'task_type' => 'required',
            'task_start_date_bs' => 'required|date',
            'task_end_date_bs' => 'required|date|after:task_start_date_bs',
            'estimated_hour' => 'required|max:100',
            'priority' => 'required',
            'assigned_to' => 'required',
            'task_point' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'task_end_date_bs.after' => "Task End date must be greater than it's start date.",
        ];
    }
}
