  <x-app-layout>
      <x-slot name="header">
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
              Edit Doctor
          </h2>
      </x-slot>

      <div class="py-12">
          <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
              <div class="bg-white p-6 rounded-lg shadow-md">
                  @if ($errors->any())
                      <div class="mb-4 p-2 bg-red-100 text-red-700 rounded">
                          {{ $errors->first() }}
                      </div>
                  @endif
                  <form action="{{ route('admin.doctors.update', $doctor) }}" method="POST">
                      @csrf
                      @method('PUT')
                      <div class="mb-4">
                          <label class="block text-gray-700">Name</label>
                          <input type="text" name="name" value="{{ old('name', $doctor->user->name) }}" class="rounded-md border-gray-300" required>
                      </div>
                      <div class="mb-4">
                          <label class="block text-gray-700">Email</label>
                          <input type="email" name="email" value="{{ old('email', $doctor->user->email) }}" class="rounded-md border-gray-300" required>
                      </div>
                      <div class="mb-4">
                          <label class="block text-gray-700">Specialization</label>
                          <input type="text" name="specialization" value="{{ old('specialization', $doctor->specialization) }}" class="rounded-md border-gray-300" required>
                      </div>
                      <div class="mb-4">
                          <label class="block text-gray-700">Schedule (JSON)</label>
                          <textarea name="schedule" class="rounded-md border-gray-300" required>{{ old('schedule', json_encode($doctor->schedule)) }}</textarea>
                      </div>
                      <div class="mb-4">
                          <label class="block text-gray-700">Status</label>
                          <select name="status" class="rounded-md border-gray-300" required>
                              <option value="active" {{ old('status', $doctor->status) === 'active' ? 'selected' : '' }}>Active</option>
                              <option value="inactive" {{ old('status', $doctor->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                          </select>
                      </div>
                      <div class="mb-4">
                          <label class="block text-gray-700">Contact</label>
                          <input type="text" name="contact" value="{{ old('contact', $doctor->contact) }}" class="rounded-md border-gray-300" required>
                      </div>
                      <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Update Doctor</button>
                  </form>
              </div>
          </div>
      </div>
  </x-app-layout>
  