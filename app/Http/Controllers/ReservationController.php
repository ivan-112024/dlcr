<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Schedule; // We'll need the Schedule model for bus specifics
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB; // For database transactions

class ReservationController extends Controller // You might rename this to BusTicketReservationController if you prefer
{
    /**
     * Display a listing of bus ticket reservations.
     * Includes associated user, schedule, bus, and route details.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Eager load relationships: user, and schedule (which itself loads its bus and route)
        $reservations = Reservation::with(['user', 'schedule.bus', 'schedule.route'])->get();
        return response()->json($reservations);
    }

    /**
     * Store a new bus ticket reservation.
     * This method includes logic for seat availability and basic fare calculation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        DB::beginTransaction(); // Start a database transaction for atomicity
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'schedule_id' => 'required|exists:schedules,id', // This is the key change for bus tickets
                'seat_number' => 'nullable|integer|min:1', // Optional: if selecting a specific seat
                // 'quantity' => 'required|integer|min:1', // If allowing multiple tickets per reservation
            ]);

            $schedule = Schedule::with('route')->find($validated['schedule_id']);

            if (!$schedule) {
                throw ValidationException::withMessages(['schedule_id' => 'The selected schedule does not exist.']);
            }

            // Basic seat availability check and decrement
            // You'll need more sophisticated logic for actual seat management (e.g., checking if seat is taken, locking seats)
            if ($schedule->available_seats <= 0) {
                throw ValidationException::withMessages(['schedule_id' => 'No seats available for this schedule.']);
            }
            if (isset($validated['seat_number']) && $schedule->reservations()->where('seat_number', $validated['seat_number'])->exists()) {
                 throw ValidationException::withMessages(['seat_number' => 'This seat is already taken for this schedule.']);
            }

            // Decrement available seats (adjust based on your actual seat management and quantity if added)
            $schedule->decrement('available_seats');

            // Calculate total fare based on the schedule's associated route
            $totalFare = $schedule->route->fare; // Assumes your Route model has a 'fare' attribute

            $reservation = Reservation::create([
                'user_id' => $validated['user_id'],
                'schedule_id' => $validated['schedule_id'], // Store the schedule ID
                'seat_number' => $validated['seat_number'] ?? null, // Assign seat if provided
                'status' => 'confirmed', // Default status, could be 'pending' for payment flow
                'booking_reference' => 'BUS-' . uniqid(), // A simple unique booking reference
                'total_fare' => $totalFare, // Store the calculated fare
            ]);

            DB::commit(); // Commit the transaction if all is successful
            return response()->json($reservation, 201); // 201 Created

        } catch (ValidationException $e) {
            DB::rollBack(); // Rollback transaction on validation error
            return response()->json([
                'message' => 'Validation Error.',
                'errors' => $e->errors(),
            ], 422); // 422 Unprocessable Entity
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction on any other error
            return response()->json([
                'message' => 'An error occurred while creating the reservation.',
                'error' => $e->getMessage(),
            ], 500); // 500 Internal Server Error
        }
    }

    /**
     * Display the specified bus ticket reservation by ID.
     * Includes the associated user, schedule, bus, and route.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $reservation = Reservation::with(['user', 'schedule.bus', 'schedule.route'])->findOrFail($id);
        return response()->json($reservation);
    }

    /**
     * Update the specified bus ticket reservation (e.g., change status, seat number).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $reservation = Reservation::findOrFail($id);

            $validated = $request->validate([
                'seat_number' => 'sometimes|nullable|integer|min:1',
                'status' => 'sometimes|required|string|in:pending,confirmed,cancelled,completed',
                // You might allow changing schedule_id here, but it's more complex with availability
            ]);

            // Handle seat number change: if changing seat, ensure new seat is available
            if (isset($validated['seat_number']) && $validated['seat_number'] !== $reservation->seat_number) {
                 // You'd need more robust logic here:
                 // - If old seat was assigned, increment its count if you track individual seat availability outside schedule.
                 // - Check if the NEW seat is available for this schedule.
                 if ($reservation->schedule->reservations()->where('seat_number', $validated['seat_number'])->exists()) {
                    throw ValidationException::withMessages(['seat_number' => 'This new seat is already taken for this schedule.']);
                }
            }

            // Handle status change impacting seat availability
            if (isset($validated['status'])) {
                if ($validated['status'] === 'cancelled' && $reservation->status !== 'cancelled') {
                    // If status changes to cancelled from a non-cancelled status, increment seats
                    $reservation->schedule->increment('available_seats');
                } elseif ($reservation->status === 'cancelled' && $validated['status'] !== 'cancelled') {
                    // If status changes from cancelled to a non-cancelled status, decrement seats (re-booking)
                    if ($reservation->schedule->available_seats <= 0) {
                         throw ValidationException::withMessages(['status' => 'Cannot re-confirm: no seats available for this schedule.']);
                    }
                    $reservation->schedule->decrement('available_seats');
                }
            }

            $reservation->update($validated);

            DB::commit();
            return response()->json($reservation);
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Validation Error.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'An error occurred while updating the reservation.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified bus ticket reservation from storage.
     * Also increments the available seats for the associated schedule.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $reservation = Reservation::findOrFail($id);

            // Increment available seats if the reservation was confirmed/pending when deleted
            if ($reservation->status !== 'cancelled' && $reservation->schedule) {
                 $reservation->schedule->increment('available_seats');
            }

            $reservation->delete();
            DB::commit();

            return response()->json(null, 204); // 204 No Content
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'An error occurred while deleting the reservation.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}