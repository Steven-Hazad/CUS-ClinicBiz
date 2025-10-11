<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Doctor Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Availability Form -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Set Availability</h3>
                @if ($errors->any())
                    <div class="mb-4 p-2 bg-red-100 text-red-700 rounded">
                        {{ $errors->first() }}
                    </div>
                @endif
                @if (session('success'))
                    <div class="mb-4 p-2 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif
                <form action="{{ route('doctor.storeAvailability') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700">Start Time</label>
                        <input type="datetime-local" name="start_time" class="rounded-md border-gray-300" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">End Time</label>
                        <input type="datetime-local" name="end_time" class="rounded-md border-gray-300" required>
                    </div>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Slot</button>
                </form>
            </div>

            <!-- Upcoming Appointments -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Upcoming Appointments</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 text-left">Patient</th>
                                <th class="px-4 py-2 text-left">Date & Time</th>
                                <th class="px-4 py-2 text-left">Status</th>
                                <th class="px-4 py-2 text-left">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($upcomingAppointments as $appointment)
                                <tr>
                                    <td class="border px-4 py-2">{{ $appointment->patient->user->name }}</td>
                                    <td class="border px-4 py-2">{{ $appointment->appointment_date ? \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y H:i') : '' }}</td>
                                    <td class="border px-4 py-2">{{ ucfirst($appointment->status) }}</td>
                                    <td class="border px-4 py-2">
                                        <form action="{{ route('doctor.appointment.update', $appointment) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" class="rounded-md border-gray-300">
                                                <option value="confirmed" {{ $appointment->status == 'confirmed' ? 'selected' : '' }}>Confirm</option>
                                                <option value="cancelled" {{ $appointment->status == 'cancelled' ? 'selected' : '' }}>Cancel</option>
                                            </select>
                                            <button type="submit" class="ml-2 bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">Update</button>
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
                                <th class="px-4 py-2 text-left">Patient</th>
                                <th class="px-4 py-2 text-left">Date & Time</th>
                                <th class="px-4 py-2 text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pastAppointments as $appointment)
                                <tr>
                                    <td class="border px-4 py-2">{{ $appointment->patient->user->name }}</td>
                                    <td class="border px-4 py-2">{{ $appointment->appointment_date ? \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y H:i') : '' }}</td>
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

            <!-- Availability Slots -->
            <div class="bg-white p-6 rounded-lg shadow-md mt-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Availability Slots</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 text-left">Start Time</th>
                                <th class="px-4 py-2 text-left">End Time</th>
                                <th class="px-4 py-2 text-left">Available</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($availabilities as $availability)
                                <tr>
                                    <td class="border px-4 py-2">{{ $availability->start_time ? \Carbon\Carbon::parse($availability->start_time)->format('M d, Y H:i') : '' }}</td>
                                    <td class="border px-4 py-2">{{ $availability->end_time ? \Carbon\Carbon::parse($availability->end_time)->format('M d, Y H:i') : '' }}</td>
                                    <td class="border px-4 py-2">{{ $availability->available ? 'Yes' : 'No' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="border px-4 py-2 text-center">No availability slots.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
