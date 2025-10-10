|<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Receptionist Dashboard
            </h2>
            <div class="space-x-4">
                <a href="{{ route('receptionist.patient.register') }}"
                   class="text-blue-500 hover:underline">Register Patient</a>
                <a href="{{ route('receptionist.appointments.index') }}"
                   class="text-blue-500 hover:underline">Manage Appointments</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-700">Welcome to MedSync</h3>
                <p class="text-gray-600">Use the links above to register patients or manage appointments.</p>
            </div>
        </div>
    </div>
</x-app-layout>