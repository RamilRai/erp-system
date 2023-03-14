<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UserRequest extends FormRequest
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
        $post = $request->only(['id', 'first_name', 'last_name', 'permanent_address', 'temporary_address',
                                'email', 'phone_number', 'username', 'profile', 'updateProfile', 'gender', 'dob_bs']);
        $userid = $post['id'];

        $rules['first_name'] = 'required|max:50';
        $rules['middle_name'] = 'max:50';
        $rules['last_name'] = 'required|max:50';
        $rules['permanent_address'] = 'required|max:100';
        $rules['temporary_address'] = 'required|max:100';
        $rules['phone_number'] = 'required|min:10';
        $rules['username'] = 'required|max:50';
        if ($post['id'] == null) {
            $rules['profile'] = 'required';
            $rules['email'] = 'required|email|max:50|unique:users,email';
        }else{
            $rules['email'] = 'required|email|max:50|unique:users,email,'.$userid.',id';
            $rules['id'] = 'integer'; //used but not worked.
        }
        if ($post['profile']) {
            $rules['profile'] = 'mimes:png,jpg,jpeg';
        }
        if ($post['updateProfile'] == 'N') {
            $rules['gender'] = 'required';
            $rules['dob_bs'] = 'required';
        }

        return $rules;

        // global rule class
    }

    public function messages()
    {
        return [
            'first_name.required' => 'First name is required',
            'first_name.max' => 'First name cannot be longer than 50 characters',
            'middle_name.max' => 'Middle name cannot be longer than 50 characters',
            'last_name.required' => 'Last name is required',
            'last_name.max' => 'Last name cannot be longer than 50 characters',
            'permanent_address.required' => 'Permanent address is required',
            'permanent_address.max' => 'Permanent address cannot be longer than 100 characters',
            'temporary_address.required' => 'Temporary address is required',
            'temporary_address.max' => 'Temporary address cannot be longer than 100 characters',
            'email.required' => 'Email is required',
            'email.email' => 'Email must be valid',
            'email.max' => 'Email cannot be longer than 50 characters',
            'email.unique' => 'Email has already been taken',
            'phone_number.required' => 'Phone number is required',
            'phone_number.min' => 'Phone number cannot be less than 10 characters',
            'username.required' => 'Username is required',
            'username.max' => 'Username cannot be longer than 50 characters',
            'profile.required' => 'Profile image is required',
            'profile.mimes' => 'Upload jpg, jpeg or png file only',
            'gender.required' => 'Gender is required',
            'dob_bs.required' => 'Date of birth is required',
        ];
    }
}
