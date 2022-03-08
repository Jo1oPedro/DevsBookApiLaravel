<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UpdateUserFormRequest extends FormRequest
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
            'name' => 'sometimes|string|max:100|min:2',
            'email' => 'sometimes|string|email|max:100|unique:users',
            'password' => 'sometimes|string|min:6|same:password_confirmation',
            'password_confirmation' => 'string|min:6|same:password',
            'birthdate' => 'sometimes|date',
            'city' => 'sometimes',
            'work' => 'sometimes',
        ];
    }

    public function messages()
    {
        return [
            'name.min' => 'O tamanho minimo para o campo :attribute é 2',
            'nome.max' => 'O tamanho maximo para esse campo é 100',
            'email.email' => 'O campo email precisa ser um email valido',
            'email.unique' => 'O email inserido já foi cadastrado',
            'password.min' => 'O tamanho minimo para senha é 6 caracteres',
            'password.same' => 'O campo de senha precisa ser igual ao campo de confirmação', 
            'password_confirmation.same' => 'O campo de confirmação precisa ser igual ao campo de senha', 
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
