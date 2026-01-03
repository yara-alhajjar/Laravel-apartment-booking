<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookingStatusChanged extends Notification
{
    use Queueable;

    public function __construct(public Booking $booking) {}

    public function via($notifiable)
    {
        return ['database']; 
    }

    public function toDatabase($notifiable)
    {
        return [
            'booking_id'   => $this->booking->id,
            'apartment_id' => $this->booking->apartment_id,
            'status'       => $this->booking->status,
            'message'      => "Your booking #{$this->booking->id} changed to {$this->booking->status}",
            'changed_at'   => now(),
        ];
    }
}
