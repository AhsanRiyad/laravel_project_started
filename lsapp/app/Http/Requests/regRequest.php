<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class regRequest extends FormRequest
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
         'email' => 'required|email|unique:user,u_email'  ,
         'password' => 'required|string|between:6,20' , 
         //'country' => 'required|string|between:3,20' , 
         'phone' => 'required|between:10,15' , 
         'last_name' => 'required|string|between:3,15' , 
         'confirm_password' => 'same:password' , 

     ];
    }



    public function messages(){
        return [
            'email.required' => ':attribute field is empty',
            'password.required' => ':attribute No field is empty'
        ];
    }



    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->all()!=[]) {
                $validator->errors()->add('msg', 'Field requirement not met up');
            }
        });
    }
}
