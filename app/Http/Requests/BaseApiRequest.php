<?php


namespace App\Http\Requests;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

trait BaseApiRequest
{
    public function failedValidation(Validator $validator)
    {
        $response = new Response(['errors'=>$validator->errors()->getMessageBag()->all()],400); // Here is your array of errors
        throw new HttpResponseException($response);
    }
}
