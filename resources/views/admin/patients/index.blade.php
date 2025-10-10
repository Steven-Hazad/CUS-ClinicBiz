<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manage Patients
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Search Form -->
            <div class="mb-6">
                <form method="GET" action="{{ route('admin.patients.index') }}">
                    <div class="flex">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Search by name or email..."
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        <button type="submit" class="ml-2 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Search
                        </button>
                    </div>
                </form>
            </div>

            <!-- Patients Table -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 text-left">Name</th>
                                <th class="px-4 py-2 text-left">Email</th>
                                <th class="px-4 py-2 text-left">Gender</th>
                                <th class="px-4 py-2 text-left">DOB</th>
                                <th class="px-4 py-2 text-left">Contact</th>
                                <th class="px-4 py-2 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($patients as $patient)
                                <tr>
                                    <td class="border px-4 py-2">{{ $patient->user->name }}</td>
                                    <td class="border px-4 py-2">{{ $patient->user->email }}</td>
                                    <td class="border px-4 py-2">{{ ucfirst($patient->gender) }}</td>
                                    <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($patient->dob)->format('M d, Y') }}</td>
                                    <td class="border px-4 py-2">{{ $patient->contact }}</td>
                                    <td class="border px-4 py-2">
                                        <a href="{{ route('admin.patients.edit', $patient) }}"
                                           class="text-blue-500 hover:underline">Edit</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="border px-4 py-2 text-center">No patients found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $patients->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>