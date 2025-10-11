<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Patient Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Patient Info -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <h3 class="text-lg font-semibold text-gray-700">Welcome, {{ $patient->user->name }}</h3>
                <p class="text-gray-600">Contact: {{ $patient->contact }}</p>
                <p class="text-gray-600">DOB: {{ \Carbon\Carbon::parse($patient->dob)->format('M d, Y') }}</p>
            </div>

            <!-- Upcoming Appointments -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Upcoming Appointments</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 text-left">Doctor</th>
                                <th class="px-4 py-2 text-left">Date & Time</th>
                                <th class="px-4 py-2 text-left">Status</th>
                                <th class="px-4 py-2 text-left">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($upcomingAppointments as $appointment)
                                <tr>
                                    <td class="border px-4 py-2">{{ $appointment->doctor->user->name }}</td>
                                    <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y H:i') }}</td>
                                    <td class="border px-4 py-2">{{ ucfirst($appointment->status) }}</td>
                                    <td class="border px-4 py-2">
                                        <form action="{{ route('patient.reschedule', $appointment) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="datetime-local" name="appointment_date" value="{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d\TH:i') }}" class="rounded-md border-gray-300 mb-2">
                                            <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">Reschedule</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="border px-4 py-2 text-center">No upcoming appointments.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Past Appointments -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Past Appointments</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 text-left">Doctor</th>
                                <th class="px-4 py-2 text-left">Date & Time</th>
                                <th class="px-4 py-2 text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pastAppointments as $appointment)
                                <tr>
                                    <td class="border px-4 py-2">{{ $appointment->doctor->user->name }}</td>
                                    <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y H:i') }}</td>
                                    <td class="border px-4 py-2">{{ ucfirst($appointment->status) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="border px-4 py-2 text-center">No past appointments.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
