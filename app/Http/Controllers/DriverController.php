<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    // Get all drivers
    public function getDriver()
    {
        $drivers = Driver::all();
        return response()->json($drivers);
    }

    // Add a new driver
    public function addDriver(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'license_number' => 'required|string|unique:drivers',
            'phone' => 'nullable|string|max:20',
            // add other validation rules as needed
        ]);

        $driver = Driver::create($validated);

        return response()->json([
            'message' => 'Driver added successfully',
            'driver' => $driver
        ], 201);
    }

    // Edit/update a driver
    public function editDriver(Request $request, $id)
    {
        $driver = Driver::find($id);
        if (!$driver) {
            return response()->json(['message' => 'Driver not found'], 404);
        }

        $validated = $request->validate([
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'license_number' => "sometimes|required|string|unique:drivers,license_number,$id",
            'phone' => 'nullable|string|max:20',
            // add other validation rules as needed
        ]);

        $driver->update($validated);

        return response()->json([
            'message' => 'Driver updated successfully',
            'driver' => $driver
        ]);
    }

    // Delete a driver
    public function deleteDriver($id)
    {
        $driver = Driver::find($id);
        if (!$driver) {
            return response()->json(['message' => 'Driver not found'], 404);
        }

        $driver->delete();

        return response()->json(['message' => 'Driver deleted successfully']);
    }
}
