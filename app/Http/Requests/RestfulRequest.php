<?php


namespace App\Http\Requests;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RestfulRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            "Error" => $validator->errors(),
            'Code' => 0
        ], 400));
    }

    protected function failedAuthorization()
    {
        throw new HttpResponseException(response()->json(['Error' => 'This request is not authorized'], 403));
    }
}