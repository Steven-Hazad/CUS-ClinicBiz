  <x-app-layout>
      <x-slot name="header">
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
              Add New Doctor
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
                  <form action="{{ route('admin.doctors.store') }}" method="POST">
                      @csrf
                      <div class="mb-4">
                          <label class="block text-gray-700">Name</label>
                          <input type="text" name="name" class="rounded-md border-gray-300" required>
                      </div>
                      <div class="mb-4">
                          <label class="block text-gray-700">Email</label>
                          <input type="email" name="email" class="rounded-md border-gray-300" required>
                      </div>
                      <div class="mb-4">
                          <label class="block text-gray-700">Password</label>
                          <input type="password" name="password" class="rounded-md border-gray-300" required>
                      </div>
                      <div class="mb-4">
                          <label class="block text-gray-700">Confirm Password</label>
                          <input type="password" name="password_confirmation" class="rounded-md border-gray-300" required>
                      </div>
                      <div class="mb-4">
                          <label class="block text-gray-700">Specialization</label>
                          <input type="text" name="specialization" class="rounded-md border-gray-300" required>
                      </div>
                      <div class="mb-4">
                          <label class="block text-gray-700">Schedule (JSON)</label>
                          <textarea name="schedule" class="rounded-md border-gray-300" required>["Mon 9-5", "Wed 9-5"]</textarea>
                      </div>
                      <div class="mb-4">
                          <label class="block text-gray-700">Status</label>
                          <select name="status" class="rounded-md border-gray-300" required>
                              <option value="active">Active</option>
                              <option value="inactive">Inactive</option>
                          </select>
                      </div>
                      <div class="mb-4">
                          <label class="block text-gray-700">Contact</label>
                          <input type="text" name="contact" class="rounded-md border-gray-300" required>
                      </div>
                      <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Create Doctor</button>
                  </form>
              </div>
          </div>
      </div>
  </x-app-layout>