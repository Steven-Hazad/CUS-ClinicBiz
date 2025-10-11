<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Confirm Appointment
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">
                    Book Slot: {{ \Carbon\Carbon::parse($availability->start_time)->format('M d, Y H:i') }} - {{ \Carbon\Carbon::parse($availability->end_time)->format('M d, Y H:i') }}
                </h3>
                @if ($errors->any())
                    <div class="mb-4 p-2 bg-red-100 text-red-700 rounded">
                        {{ $errors->first() }}
                    </div>
                @endif
                <form action="{{ route('patient.book', $availability) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700">Appointment Date & Time</label>
                        <input type="datetime-local" name="appointment_date" class="rounded-md border-gray-300" required min="{{ now()->format('Y-m-d\TH:i') }}" max="{{ $availability->end_time }}">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">Notes (Optional)</label>
                        <textarea name="notes" class="rounded-md border-gray-300 w-full" rows="3" maxlength="500"></textarea>
                    </div>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Book Appointment</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
