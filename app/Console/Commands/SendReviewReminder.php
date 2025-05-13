<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Notifications\ReviewReminderNotification;

class SendReviewReminder extends Command
{
    protected $signature = 'reminder:review';
    protected $description = 'Kirim notifikasi pengingat review kepada user setelah check-out';

    public function handle()
    {
        $results = DB::select('CALL get_ReviewReminderBookings()');

        foreach ($results as $booking) {
            $user = User::find($booking->user_id);
            if ($user) {
                $user->notify(new ReviewReminderNotification($booking));
            }
        }

        $this->info('Notifikasi review dikirim ke semua user yang eligible.');
    }
}
