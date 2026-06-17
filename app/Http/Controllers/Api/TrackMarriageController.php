<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTrackMarriageRequest;
use App\Services\ColdstoreApiService;
use Illuminate\Http\JsonResponse;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class TrackMarriageController extends Controller
{
    public function __invoke(StoreTrackMarriageRequest $request, ColdstoreApiService $coldstoreApiService): JsonResponse
    {
        try {
            $result = $coldstoreApiService->assignTrackMarriage($request->validated());
        } catch (RuntimeException $runtimeException) {
            return response()->json([
                'message' => $runtimeException->getMessage(),
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }

        return response()->json($result['body'], $result['status']);
    }
}
