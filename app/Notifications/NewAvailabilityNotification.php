<?php

namespace App\Notifications;

use App\Models\Availability;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewAvailabilityNotification extends Notification
{
    use Queueable;

    public $availability;

    public function __construct(Availability $availability)
    {
        $this->availability = $availability;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Doctor Availability')
            ->line('A new availability slot has been added by your doctor.')
            ->line('Start Time: ' . \Carbon\Carbon::parse($this->availability->start_time)->format('M d, Y H:i'))
            ->line('End Time: ' . \Carbon\Carbon::parse($this->availability->end_time)->format('M d, Y H:i'))
            ->action('Confirm and Book', route('patient.confirm', ['availability' => $this->availability->id]))
            ->line('Thank you for using MedSync!');
    }
}
    