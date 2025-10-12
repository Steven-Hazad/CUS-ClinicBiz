<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Medicine Inventory
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <form action="{{ route('admin.medicines.store') }}" method="POST" class="mb-6">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700">Name</label>
                            <input type="text" name="name" class="rounded-md border-gray-300" required>
                        </div>
                        <div>
                            <label class="block text-gray-700">Category</label>
                            <input type="text" name="category" class="rounded-md border-gray-300" required>
                        </div>
                        <div>
                            <label class="block text-gray-700">Stock Quantity</label>
                            <input type="number" name="stock_quantity" class="rounded-md border-gray-300" required>
                        </div>
                        <div>
                            <label class="block text-gray-700">Expiry Date</label>
                            <input type="date" name="expiry_date" class="rounded-md border-gray-300" required>
                        </div>
                        <div>
                            <label class="block text-gray-700">Price</label>
                            <input type="number" step="0.01" name="price" class="rounded-md border-gray-300" required>
                        </div>
                    </div>
                    <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Medicine</button>
                </form>

                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">Name</th>
                            <th class="px-4 py-2">Category</th>
                            <th class="px-4 py-2">Stock</th>
                            <th class="px-4 py-2">Expiry Date</th>
                            <th class="px-4 py-2">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($medicines as $medicine)
                            <tr>
                                <td class="px-4 py-2">{{ $medicine->name }}</td>
                                <td class="px-4 py-2">{{ $medicine->category }}</td>
                                <td class="px-4 py-2">{{ $medicine->stock_quantity }}</td>
                                <td class="px-4 py-2">{{ $medicine->expiry_date }}</td>
                                <td class="px-4 py-2">${{ $medicine->price }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-2 text-center">No medicines yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
