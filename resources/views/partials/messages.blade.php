@forelse ($messages as $message)
    <p>{{ $message->patient->user->name ?? $message->doctor->user->name }}: {{ $message->content }}</p>
@empty
    <p>No messages yet.</p>
@endforelse