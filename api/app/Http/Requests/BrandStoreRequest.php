<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use App\Enums\ApiStatus;
use App\Enums\Messages;

class BrandStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'brand_name'       => 'required|string|max:255',
            'brand_url'        => 'required|url|max:1024',
            'brand_img_url'    => 'required|url|max:1024',
            'brand_rating'     => 'required|numeric|min:0|max:5',
            'brand_country_id' => 'required|exists:countries,country_id',
            'is_active'        => 'required|boolean',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('is_active')) {
            $this->merge([
                'is_active' => filter_var(
                    $this->input('is_active'),
                    FILTER_VALIDATE_BOOLEAN,
                    FILTER_NULL_ON_FAILURE
                ),
            ]);
        }
    }

    public function failedValidation(Validator $validator)
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