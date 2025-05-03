<?php

namespace App\Services;

use App\Repositories\BrandRepository;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Brand;
use App\Enums\Messages;
use App\Exceptions\BaseApiException;
use Illuminate\Http\Response;

class BrandService
{
    public function __construct() {}

    public function list(array $filters)
    {
        try {
            $perPage     = $filters['per_page']   ?? config('brands.pagination.per_page');
            $countryCode = $filters['country']    ?? null;
            $ratingMin   = $filters['rating_min'] ?? null;
            $ratingMax   = $filters['rating_max'] ?? null;
            $isActive    = $filters['is_active']  ?? null;
            $page        = $filters['page']       ?? config('brands.pagination.page');
            $sortBy      = $filters['sort_by']    ?? config('brands.pagination.sort_by');
            $sortOrder   = $filters['sort_order'] ?? config('brands.pagination.sort_order');
            $search      = $filters['q']          ?? null;


            $query = Brand::with('country')
            // Filtre par pays
            ->when($countryCode, fn($q) =>
                $q->whereHas('country', fn($q2) =>
                    $q2->where('country_code_cca2', $countryCode)
                )
            )
            ->when($isActive !== null, fn($q) =>
                $q->where('is_active', filter_var($isActive, FILTER_VALIDATE_BOOLEAN))
            )
            ->when($search, fn($q) =>
                $q->where('brand_name', 'like', '%'.$search.'%')
            )
            ->when($ratingMin !== null, fn($q) =>
                $q->where('brand_rating', '>=', (float) $ratingMin)
            )
            ->when($ratingMax !== null, fn($q) =>
                $q->where('brand_rating', '<=', (float) $ratingMax)
            )
            ->orderBy($sortBy, $sortOrder);

        } catch (\Throwable $e) {
            throw new BaseApiException(
                Messages::INTERNAL_SERVER_ERROR_MSG->value,
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    public function create(array $data): array
    {
        try {
            $brand = Brand::create($data);
        } catch (\Exception $e) {
            throw new BaseApiException(
                Messages::INTERNAL_SERVER_ERROR_MSG->value,
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return compact('brand');
    }

    public function find(string $id): array
    {
        $brand = Brand::find($id);
        
        if (!$brand) {
            throw new BaseApiException(
                Messages::RESOURCE_NOT_FOUND->value,
                Response::HTTP_NOT_FOUND
            );
        }

        return compact('brand');
    }


    public function update(string $id, array $data): array
    {
        $brand = Brand::find($id);

        if (!$brand) {
            throw new BaseApiException(
                Messages::RESOURCE_NOT_FOUND->value,
                Response::HTTP_NOT_FOUND
            );
        }

        $brand->update($data);

        return compact('brand');
    }

    public function delete(string $id): void
    {
        $brand = Brand::find($id);

        if (!$brand) {
            throw new BaseApiException(
                Messages::RESOURCE_NOT_FOUND->value,
                Response::HTTP_NOT_FOUND
            );
        }

        $brand->delete();
    }
}
