<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class SendOtpRequest extends FormRequest
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

        if ($request->has('email')) {
            $request->validate([
                'email' => 'required'
            ]);
        }

        if ($request->has('userid')) {
            $request->validate([
                'currentPassword' => 'required',
                'newPassword' => 'required',
                'confirmPassword' => 'required'
            ]);
        }

        return $request;
    }
}
