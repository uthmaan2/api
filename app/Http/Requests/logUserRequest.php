<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class logUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email'=> 'required|email|exists:users,email',
            'password'=>'required'
        ];
    }
    public function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
            'success'=>false,
            'error'=>true,
            'message'=> 'Erreur de validation',
            'errorslist'=>$validator->errors()
        ]));
    }
    public function messages(){

        return[
            'email.required'=>'email no  fourni',
            'email.email'=>'email no  valide',
            'email.exists'=>'email n\'existe pas',
            'password.required'=>'mot de passe no  fourni'
        ];
    }

}
