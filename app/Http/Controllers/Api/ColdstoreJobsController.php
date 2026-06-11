<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShowColdstoreJobsRequest;
use App\Services\ColdstoreJobs\JobMatchingService;
use Illuminate\Http\JsonResponse;

class ColdstoreJobsController extends Controller
{
    public function __invoke(ShowColdstoreJobsRequest $request, JobMatchingService $jobMatchingService): JsonResponse
    {
        return response()->json(
            $jobMatchingService->payloadForLine((int) $request->validated('selected_line'))
        );
    }
}
