<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Admin Dashboard
            </h2>
            <a href="{{ route('admin.patients.index') }}"
               class="text-blue-500 hover:underline">Manage Patients</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold text-gray-700">Total Patients</h3>
                    <p class="text-3xl font-bold text-blue-600">{{ $totalPatients }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold text-gray-700">Total Doctors</h3>
                    <p class="text-3xl font-bold text-blue-600">{{ $totalDoctors }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold text-gray-700">Total Appointments</h3>
                    <p class="text-3xl font-bold text-blue-600">{{ $totalAppointments }}</p>
                </div>
            </div>

            <!-- Chart -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Appointments (Last 7 Days)</h3>
                <canvas id="appointmentChart" height="100"></canvas>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const ctx = document.getElementById('appointmentChart').getContext('2d');
                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: @json($dates),
                                datasets: [{
                                    label: 'Appointments',
                                    data: @json($appointmentCounts),
                                    borderColor: '#2563eb',
                                    backgroundColor: 'rgba(37, 99, 235, 0.2)',
                                    fill: true,
                                    tension: 0.4
                                }]
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                    });
                </script>
            </div>

            <!-- Recent Appointments -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Recent Appointments</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 text-left">Patient</th>
                                <th class="px-4 py-2 text-left">Doctor</th>
                                <th class="px-4 py-2 text-left">Date</th>
                                <th class="px-4 py-2 text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentAppointments as $appointment)
                                <tr>
                                    <td class="border px-4 py-2">{{ $appointment->patient->user->name }}</td>
                                    <td class="border px-4 py-2">{{ $appointment->doctor->user->name }}</td>
                                    <td class="border px-4 py-2">
                                        {{ $appointment->appointment_date ? \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y H:i') : '' }}
                                    </td>
                                    <td class="border px-4 py-2">{{ ucfirst($appointment->status) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Notifications (Stub) -->
            <div x-data="{ open: false }" class="mt-6">
                <button @click="open = !open" class="bg-blue-500 text-white px-4 py-2 rounded">
                    Toggle Notifications
                </button>
                <div x-show="open" class="mt-2 p-4 bg-gray-100 rounded">
                    <p>No new notifications.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
