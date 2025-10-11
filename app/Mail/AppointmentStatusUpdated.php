<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $recipientType;

    public function __construct(Appointment $appointment, string $recipientType)
    {
        $this->appointment = $appointment;
        $this->recipientType = $recipientType;
    }

    public function build()
    {
        return $this->subject('MedSync: Appointment Status Update')
                    ->view('emails.appointment-status')
                    ->with([
                        'appointment' => $this->appointment,
                        'recipientType' => $this->recipientType,
                    ]);
    }
}