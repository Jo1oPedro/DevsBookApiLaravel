<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class AuthFormRequest extends FormRequest
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
        
        return[
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
            'birthdate' => 'required|date'
        ];
    }

    public function messages()
    {
        return [
            'nome.required' => 'O campo :attribute é obrigatorio',
            'nome.max' => 'O tamanho maximo para esse campo é 100',
            'email.required' => 'O campo :attribute é obrigatorio',
            'email.email' => 'O campo email precisa ser um email valido',
            'email.unique' => 'O email inserido já foi cadastrado',
            'password.required' => 'O campo de senha é obrigatorio',
            'password.min' => 'O tamanho minimo para senha é 6 caracteres',
            'birthdate.required' => 'O campo birthdate é obrigatorio',
            'birthdate.date' => 'O campo birthdate precisa ser uma data valida'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ]));
    }
}
