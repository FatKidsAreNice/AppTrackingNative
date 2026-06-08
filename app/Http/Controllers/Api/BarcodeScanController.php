<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBarcodeScanRequest;
use App\Services\ColdstoreApiService;
use Illuminate\Http\JsonResponse;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class BarcodeScanController extends Controller
{
    public function __invoke(StoreBarcodeScanRequest $request, ColdstoreApiService $coldstoreApiService): JsonResponse
    {
        try {
            $result = $coldstoreApiService->forwardBarcode($request->validated());
        } catch (RuntimeException $runtimeException) {
            return response()->json([
                'message' => $runtimeException->getMessage(),
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }

        return response()->json($result, Response::HTTP_CREATED);
    }
}
