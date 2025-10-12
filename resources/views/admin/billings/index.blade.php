<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Billing Management
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">Invoice No</th>
                            <th class="px-4 py-2">Patient</th>
                            <th class="px-4 py-2">Amount</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Payment Method</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($billings as $billing)
                            <tr>
                                <td class="px-4 py-2">{{ $billing->invoice_no }}</td>
                                <td class="px-4 py-2">{{ $billing->appointment->patient->user->name }}</td>
                                <td class="px-4 py-2">${{ $billing->amount }}</td>
                                <td class="px-4 py-2">{{ ucfirst($billing->status) }}</td>
                                <td class="px-4 py-2">{{ ucfirst($billing->payment_method) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-2 text-center">No billings yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
