<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Admin Analytics
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Total Appointments -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <h3 class="text-lg font-semibold text-gray-700">Total Appointments</h3>
                <p class="text-2xl">{{ $totalAppointments }}</p>
            </div>

            <!-- Appointments by Status -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <h3 class="text-lg font-semibold text-gray-700">Appointments by Status</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 text-left">Status</th>
                                <th class="px-4 py-2 text-left">Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($byStatus as $status => $count)
                                <tr>
                                    <td class="border px-4 py-2">{{ ucfirst($status) }}</td>
                                    <td class="border px-4 py-2">{{ $count }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="border px-4 py-2 text-center">No data available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Appointments by Date -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-700">Appointments by Date (Last 7 Days)</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 text-left">Date</th>
                                <th class="px-4 py-2 text-left">Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($byDate as $day)
                                <tr>
                                    <td class="border px-4 py-2">{{ $day->date }}</td   >
                                    <td class="border px-4 py-2">{{ $day->count }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="border px-4 py-2 text-center">No data available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
