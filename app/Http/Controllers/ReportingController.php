<?php

namespace App\Http\Controllers;

use App\Models\Reporting;
use Illuminate\Http\Request;

class ReportingController extends Controller
{
    // List all reports with their user
    public function index()
    {
        $reports = Reporting::with('user')->get();
        return response()->json($reports);
    }

    // Create a new report
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|string',
        ]);

        $report = Reporting::create($validated);

        return response()->json($report, 201);
    }

    // Show a report by ID with user
    public function show($id)
    {
        $report = Reporting::with('user')->findOrFail($id);
        return response()->json($report);
    }

    // Update a report by ID
    public function update(Request $request, $id)
    {
        $report = Reporting::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'sometimes|exists:users,id',
            'status' => 'sometimes|string',
        ]);

        $report->update($validated);

        return response()->json($report);
    }

    // Delete a report by ID
    public function destroy($id)
    {
        $report = Reporting::findOrFail($id);
        $report->delete();

        return response()->json(null, 204);
    }
}
