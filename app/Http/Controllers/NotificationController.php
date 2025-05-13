<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getReviewNotifications()
    {
        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Query booking yang memenuhi syarat (check-out selesai & belum diulas)
        $bookings = DB::select('CALL get_BookingsByUserIdtest(?, ?)', [$userId, null]);

        // Filter booking yang bisa diulas
        $eligibleBookings = array_filter($bookings, function ($booking) {
            $checkOutDate = \Carbon\Carbon::parse($booking->check_out);
            return $checkOutDate->isPast() && $booking->status === 'completed' && !$booking->reviewed;
        });

        // Format notifikasi
        $notifications = array_map(function ($booking) {
            return [
                'booking_id' => $booking->booking_id,
                'property_name' => $booking->property_name,
                'check_out' => $booking->check_out,
                'time_ago' => \Carbon\Carbon::parse($booking->check_out)->diffForHumans(),
                'link' => route('review.create', ['booking_id' => $booking->booking_id]),
            ];
        }, $eligibleBookings);

        return response()->json([
            'count' => count($notifications),
            'notifications' => $notifications,
        ]);
    }
}