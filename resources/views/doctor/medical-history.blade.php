<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Medical History for {{ $patient->user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Add Record</h3>
                <form action="{{ route('doctor.storeMedicalRecord', $patient) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700">Notes</label>
                        <textarea name="notes" class="rounded-md border-gray-300 w-full" required></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">Upload File (PDF/JPEG/PNG)</label>
                        <input type="file" name="file" class="rounded-md border-gray-300">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">Record Date</label>
                        <input type="date" name="record_date" class="rounded-md border-gray-300" required>
                    </div>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Record</button>
                </form>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Records</h3>
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">Date</th>
                            <th class="px-4 py-2">Notes</th>
                            <th class="px-4 py-2">File</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($patient->medicalRecords as $record)
                            <tr>
                                <td class="px-4 py-2">{{ $record->record_date }}</td>
                                <td class="px-4 py-2">{{ $record->notes }}</td>
                                <td class="px-4 py-2">
                                    @if ($record->file_path)
                                        <a href="{{ asset('storage/' . $record->file_path) }}" target="_blank" class="text-blue-500">View File</a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-2 text-center">No records yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
