<?php

namespace App\Http\Controllers;

use App\Models\TripProcessing;
use Illuminate\Http\Request;

class TripProcessingController extends Controller
{
    // List all processings for a specific trip
    public function indexForTrip($tripId)
    {
        $processings = TripProcessing::where('trip_id', $tripId)->get();
        return response()->json($processings);
    }

    // Store a new processing for a specific trip
    public function storeForTrip(Request $request, $tripId)
    {
        $validated = $request->validate([
            'status' => 'nullable|string',
            'started_at' => 'nullable|date',
            'ended_at' => 'nullable|date',
        ]);

        // Set trip_id from the route parameter
        $validated['trip_id'] = $tripId;

        $processing = TripProcessing::create($validated);

        return response()->json($processing, 201);
    }

    // Show a single processing belonging to a trip
    public function showForTrip($tripId, $processingId)
    {
        $processing = TripProcessing::where('trip_id', $tripId)
            ->where('id', $processingId)
            ->firstOrFail();

        return response()->json($processing);
    }

    // Update a processing belonging to a trip
    public function updateForTrip(Request $request, $tripId, $processingId)
    {
        $processing = TripProcessing::where('trip_id', $tripId)
            ->where('id', $processingId)
            ->firstOrFail();

        $validated = $request->validate([
            'status' => 'sometimes|string',
            'started_at' => 'nullable|date',
            'ended_at' => 'nullable|date',
        ]);

        $processing->update($validated);

        return response()->json($processing);
    }

    // Delete a processing belonging to a trip
    public function destroyForTrip($tripId, $processingId)
    {
        $processing = TripProcessing::where('trip_id', $tripId)
            ->where('id', $processingId)
            ->firstOrFail();

        $processing->delete();

        return response()->json(null, 204);
    }
}
