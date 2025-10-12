  <x-app-layout>
      <x-slot name="header">
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
              Manage Doctors
          </h2>
      </x-slot>

      <div class="py-12">
          <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
              <div class="mb-4">
                  <a href="{{ route('admin.doctors.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Doctor</a>
                  <form action="{{ route('admin.doctors.index') }}" method="GET" class="inline-block ml-4">
                      <input type="text" name="search" value="{{ $search }}" placeholder="Search by name or email" class="rounded-md border-gray-300">
                      <button type="submit" class="bg-gray-500 text-white px-2 py-1 rounded hover:bg-gray-600">Search</button>
                  </form>
              </div>
              <div class="bg-white p-6 rounded-lg shadow-md">
                  @if (session('success'))
                      <div class="mb-4 p-2 bg-green-100 text-green-700 rounded">
                          {{ session('success') }}
                      </div>
                  @endif
                  <table class="min-w-full">
                      <thead>
                          <tr>
                              <th class="px-4 py-2">Name</th>
                              <th class="px-4 py-2">Email</th>
                              <th class="px-4 py-2">Specialization</th>
                              <th class="px-4 py-2">Status</th>
                              <th class="px-4 py-2">Actions</th>
                          </tr>
                      </thead>
                      <tbody>
                          @foreach ($doctors as $doctor)
                              <tr>
                                  <td class="px-4 py-2">{{ $doctor->user->name }}</td>
                                  <td class="px-4 py-2">{{ $doctor->user->email }}</td>
                                  <td class="px-4 py-2">{{ $doctor->specialization }}</td>
                                  <td class="px-4 py-2">{{ $doctor->status }}</td>
                                  <td class="px-4 py-2">
                                      <a href="{{ route('admin.doctors.edit', $doctor) }}" class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600">Edit</a>
                                  </td>
                              </tr>
                          @endforeach
                      </tbody>
                  </table>
                  {{ $doctors->links() }}
              </div>
          </div>
      </div>
  </x-app-layout>
  