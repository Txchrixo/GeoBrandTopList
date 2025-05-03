<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use App\Enums\ApiStatus;
use App\Enums\Messages;

class LoginRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email:rfc,dns',
            'password' => 'required|string',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(
                [
                    'status'      => ApiStatus::ERROR->value,
                    'status_code' => Response::HTTP_UNPROCESSABLE_ENTITY,
                    'message'     => Messages::VALIDATION_FAILED->value,
                    'errors'      => $validator->errors(),
                ], 
                Response::HTTP_UNPROCESSABLE_ENTITY
            )
        );
    }
}
