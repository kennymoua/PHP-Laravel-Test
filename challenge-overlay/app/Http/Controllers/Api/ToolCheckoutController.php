<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckInToolCheckoutRequest;
use App\Http\Resources\ToolCheckoutResource;
use App\Models\Tool;
use App\Models\ToolCheckout;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ToolCheckoutController extends Controller
{
    /**
     * GET /api/tool-checkouts/current
     *
     * Return all currently checked-out tools (and their active checkout).
     */
    public function current(): JsonResponse
    {
        // TODO: implement using ToolCheckout::currentlyCheckedOut()
        // Expected: list of active checkouts with their tool
        return response()->json([
            'data' => [],
        ]);
    }

    /**
     * PATCH /api/tool-checkouts/{toolCheckout}/check-in
     *
     * Check in an active checkout:
     * - set checked_in_at = now()
     * - set tool.status = available
     * - prevent check-in if already checked in (422)
     */
    public function checkIn(CheckInToolCheckoutRequest $request, ToolCheckout $toolCheckout): JsonResponse
    {
        // TODO: implement
        return response()->json([
            'message' => 'Not implemented',
        ], 501);
    }
}
