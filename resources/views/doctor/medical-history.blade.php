<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Patient Medical History
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">
                    {{ $patient->user->name }}
                </h3>
                <p><strong>Contact:</strong> {{ $patient->contact }}</p>
                <p><strong>DOB:</strong> {{ $patient->dob ? \Carbon\Carbon::parse($patient->dob)->format('Y-m-d') : 'N/A' }}</p>
                <p><strong>Gender:</strong> {{ $patient->gender }}</p>
                <p><strong>Medical History:</strong> {{ $patient->medical_history ?? 'None' }}</p>
                <a href="{{ route('doctor.dashboard') }}" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Back to Dashboard</a>
            </div>
        </div>
    </div>
</x-app-layout>
