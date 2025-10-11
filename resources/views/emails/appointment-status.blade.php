     @component('mail::message')
     # Appointment Status Update

     Dear {{ $recipientType == 'patient' ? $appointment->patient->user->name : $appointment->doctor->user->name }},

          Your appointment with {{ $recipientType == 'patient' ? $appointment->doctor->user->name : $appointment->patient->user->name }} on {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y H:i') }} has been {{ $appointment->status }}.

     **Details:**
     - **Patient**: {{ $appointment->patient->user->name }}
     - **Doctor**: {{ $appointment->doctor->user->name }}
     - **Date & Time**: {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y H:i') }}
     - **Status**: {{ ucfirst($appointment->status) }}

     @if($recipientType == 'patient')
     Please contact us at support@medsync.com if you have any questions.
     @else
     You can manage this appointment from your dashboard: {{ route('doctor.dashboard') }}
     @endif

     Thank you,  
     MedSync Team

     @component('mail::button', ['url' => route($recipientType == 'patient' ? 'patient.appointment.book' : 'doctor.dashboard')])
     View Dashboard
     @endcomponent
     @endcomponent
     