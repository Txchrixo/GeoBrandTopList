<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\BrandService;
use App\Http\Resources\BrandResource;
use App\Http\Requests\BrandStoreRequest;
use App\Http\Requests\BrandUpdateRequest;
use App\Enums\ApiStatus;
use Illuminate\Http\Response;
use App\Enums\Messages;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\BrandCollection;
use App\Http\Middleware\FilterUnauthorizedParams;
use App\Http\Requests\BrandListRequest;
use App\Http\Requests\GeolocationRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Middleware\GeolocationCloudflare;

class BrandController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    protected $brandService;

    public function __construct(BrandService $brandService)
    {
        $this->middleware(FilterUnauthorizedParams::class)->only(['index']);
        $this->middleware(GeolocationCloudflare::class)->only(['index']);
        $this->brandService = $brandService;
    }

    public function index(BrandListRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $paginator = $this->brandService->list($filters);

        $collectionWithMetaData = (new BrandCollection($paginator))->additional([
            'metadata' => [
                'current_page' => $paginator->currentPage(),
                'from' => $paginator->firstItem(),
                'last_page' => $paginator->lastPage(),
                'path' => $paginator->path(),
                'per_page' => $paginator->perPage(),
                'to' => $paginator->lastItem(),
                'total' => $paginator->total(),
                'links' => [
                    'first' => $paginator->url(1),
                    'last' => $paginator->url($paginator->lastPage()),
                    'prev' => $paginator->previousPageUrl(),
                    'next' => $paginator->nextPageUrl(),
                ],
            ],
        ]);

        return response()->json([
            'status'      => ApiStatus::SUCCESS->value,
            'status_code' => Response::HTTP_OK,
            'message'     => Messages::RESOURCE_LIST_FETCHED_SUCCESSFULLY->value,
            'data'        => $collectionWithMetaData
        ], Response::HTTP_OK);
    }

    public function store(BrandStoreRequest $request): JsonResponse
    {
        $data = $this->brandService->create($request->validated());
        $resource = new BrandResource($data['brand']);

        return response()->json([
            'status'      => ApiStatus::SUCCESS->value,
            'status_code' => Response::HTTP_CREATED,
            'message'     => Messages::RESOURCE_CREATED_SUCCESSFULLY->value,
            'data'        => $resource,
        ]);
    }

    public function show(string $id): JsonResponse
    {
        $data = $this->brandService->find($id);
        $resource = new BrandResource($data['brand']);

        return response()->json([
            'status'      => ApiStatus::SUCCESS->value,
            'status_code' => Response::HTTP_OK,
            'message'     => Messages::RESOURCE_FETCHED_SUCCESSFULLY->value,
            'data'        => $resource,
        ]);
    }

    public function update(BrandUpdateRequest $request, string $id): JsonResponse
    {
        $data = $this->brandService->update($id, $request->validated());
        $resource = new BrandResource($data['brand']);

        return response()->json([
            'status'      => ApiStatus::SUCCESS->value,
            'status_code' => Response::HTTP_OK,
            'message'     => Messages::RESOURCE_UPDATED_SUCCESSFULLY->value,
            'data'        => $resource,
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->brandService->delete($id);

        return response()->json([
            'status'      => ApiStatus::SUCCESS->value,
            'status_code' => Response::HTTP_OK,
            'message'     => Messages::RESOURCE_DELETED_SUCCESSFULLY->value,
        ]);
    }
}
