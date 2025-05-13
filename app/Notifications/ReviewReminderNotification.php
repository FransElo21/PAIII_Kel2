<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReviewReminderNotification extends Notification
{
    use Queueable;

    protected $booking;

    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Ayo beri ulasan untuk ' . $this->booking->property_name,
            'booking_id' => $this->booking->booking_id,
            'check_out' => $this->booking->check_out,
            'link' => route('review.create', ['booking_id' => $this->booking->booking_id]),
        ];
    }
}
