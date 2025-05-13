<?php

namespace App\Notifications;

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookingSuccessNotification extends Notification
{
    use Queueable;

    protected $booking;

    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    public function via($notifiable)
    {
        return ['database']; // Bisa juga: ['mail', 'database']
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Booking berhasil untuk ' . $this->booking->property_name,
            'booking_id' => $this->booking->id,
            'check_in' => $this->booking->check_in,
            'check_out' => $this->booking->check_out,
            'link' => route('booking.detail', ['id' => $this->booking->id])
        ];
    }
}
