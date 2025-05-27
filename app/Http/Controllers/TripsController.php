<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;

class TripsController extends Controller
{
    // List all trips
    public function index()
    {
        $trips = Trip::all();
        return response()->json($trips);
    }

    // Show a single trip by ID
    public function show($id)
    {
        $trip = Trip::findOrFail($id);
        return response()->json($trip);
    }

    // Create a new trip
    public function store(Request $request)
    {
        $validated = $request->validate([
            'destination' => 'required|string|max:255',
            'trip_date' => 'required|date',
        ]);

        $trip = Trip::create($validated);

        return response()->json($trip, 201);
    }

    // Update an existing trip
    public function update(Request $request, $id)
    {
        $trip = Trip::findOrFail($id);

        $validated = $request->validate([
            'destination' => 'sometimes|required|string|max:255',
            'trip_date' => 'sometimes|required|date',
        ]);

        $trip->update($validated);

        return response()->json($trip);
    }

    // Delete a trip
    public function destroy($id)
    {
        $trip = Trip::findOrFail($id);
        $trip->delete();

        return response()->json(null, 204);
    }
}
