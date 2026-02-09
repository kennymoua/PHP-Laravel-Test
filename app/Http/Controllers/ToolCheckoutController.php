<?php

namespace App\Http\Controllers;

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

        
        // Query the database for tool checkouts where checked_in_at IS NULL
        // This uses the scope we fixed in ToolCheckout model
        // The with('tool') eager loads the related Tool record to avoid N+1 queries
        // (In Salesforce terms: this is like a subquery to get Tool__r fields) - ken 
        $checkouts = ToolCheckout::currentlyCheckedOut()->with('tool')->get(); 

        // Return the data as JSON
        // ToolCheckoutResource formats each checkout record into clean JSON
        // collection() applies the resource formatting to all records in the collection - ken
        return response()->json([
            'data' => ToolCheckoutResource::collection($checkouts),
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
        // Expected: if already checked in, return 422 with message "Tool checkout is already checked in"
        // Otherwise, set checked_in_at to now, update tool status to available, and return 200 with message "Tool checked in successfully"


        // Step 1: Validate the checkout is still active
        // isActive() returns true if checked_in_at is NULL (tool not yet returned)
        // If already checked in (checked_in_at has a timestamp), return error
        if (!$toolCheckout->isActive()) {
            return response()->json([
                'message' => 'Tool checkout is already checked in',
            ], 422);
        }

        // Step 2: Update the checkout record
        // Set checked_in_at to current timestamp (marks when tool was returned)
        $toolCheckout->checked_in_at = now();

        // save the updated checkout record to the database
        $toolCheckout->save();


        // Step 3: Update the related tool's status
        // Access the related Tool via the 'tool' relationship (defined in ToolCheckout model)
        // Tool::STATUS_AVAILABLE is a constant = 'available'
        $toolCheckout->tool->status = Tool::STATUS_AVAILABLE;

        // Save the tool record to the database
        $toolCheckout->tool->save();

        // Step 4: Return success response
        return response()->json([
            'data' => new ToolCheckoutResource($toolCheckout),
        ], 200);
    }
}
