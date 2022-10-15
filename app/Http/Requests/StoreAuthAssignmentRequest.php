<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StoreAuthAssignmentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'required|exists:pgsql.public.users,id',
            'auth_item_id' => 'required|exists:pgsql.system.auth_item,id',
        ];
    }
    /**
     * Overrides a failed validation attempt to be returned as json
     */
    public function failedValidation(Validator $validator)
    {
    throw new HttpResponseException(response()->json([
        'success'   => false,
        'message'   => 'Validation errors',
        'data'      => $validator->errors()
    ],400));
    }
}
