<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ColdstoreApiService;
use Illuminate\Http\JsonResponse;

class ColdstoreOverviewController extends Controller
{
    public function __invoke(ColdstoreApiService $coldstoreApiService): JsonResponse
    {
        return response()->json($coldstoreApiService->fetchOverview());
    }
}
