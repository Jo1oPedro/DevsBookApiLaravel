<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class FeedCreateFormRequest extends FormRequest
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
            'type' => 'required|string|',
            'body' => 'sometimes|string',
            'photo' => 'sometimes|image|mimes:jpg,png,jpeg|'
        ];
    }

    public function messages()
    {
        return [
            'type.required' => 'Tipo de postagem inexistente',
            'type.string' => 'O campo :attribute precisa ser uma texto',
            'body.string' => 'Texto ou imagem não enviada',
            'photo.image' => 'O campo :attribute precisa ser uma imagem',
            'photo.mimes' => 'O campo :attribute precisa ser uma imagem valida(jpg/png/jpeg)',
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
