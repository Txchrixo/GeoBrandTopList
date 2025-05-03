<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Country;
use Illuminate\Http\Response;
use App\Enums\ApiStatus;
use App\Enums\Messages;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BrandListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function all($keys = null)
    {
        return $this->query->all();
    }

    public function rules(): array
    {
        return [
            'per_page'    => 'sometimes|integer|min:1|max:100',
            'country'     => 'sometimes|string|size:2|exists:countries,country_code_cca2',
            'rating_min'  => 'sometimes|numeric|min:0|max:5',
            'rating_max'  => 'sometimes|numeric|min:0|max:5',
            'is_active'   => 'sometimes|boolean',
            'page'        => 'sometimes|integer|min:1',
            'sort_by'     => 'sometimes|string|in:brand_rating,created_at',
            'sort_order'  => 'sometimes|string|in:asc,desc',
            'q'           => 'sometimes|string|max:255',
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