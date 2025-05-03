<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Http\Resources\CountryResource;
use App\Enums\ApiStatus;
use App\Enums\Messages;
use Symfony\Component\HttpFoundation\Response;

class CountryController extends Controller
{

    public function index()
    {
        $countries = Country::all();
        $collection = CountryResource::collection($countries);
        
        return response()->json([
            'status'      => ApiStatus::SUCCESS->value,
            'status_code' => Response::HTTP_OK,
            'message'     => Messages::RESOURCE_LIST_FETCHED_SUCCESSFULLY->value,
            'data'        => $collection,
        ]);
    }
}
