<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;



class CreateUserRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|min:3|max:255',
            'email' => 'required',
//            'profile_pic'=> 'mimes:jpg,jpeg,png,bmp,tiff |max:4096',
            'profile_pic'=> 'string',
            'salary'=>'numeric',
//            'designation_id'=>'required|Rule::in(["manager", "developer","hr","CEO"])',
            'designation_id'=>'required',
            'password' => 'required|string|min:6'
        ];
    }
}
