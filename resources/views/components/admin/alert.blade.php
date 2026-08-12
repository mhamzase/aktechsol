@props([
    'type'       => 'success',
    'message'    => null,        // single message string (optional)
    'messages'   => [],          // array of messages (optional)
    'dismissible' => true,
])

@php
    $classes = match($type) {
        'success' => 'bg-green-50 border-green-200 text-green-700',
        'error'   => 'bg-red-50 border-red-200 text-red-700',
        'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-700',
        'info'    => 'bg-blue-50 border-blue-200 text-blue-700',
        default   => 'bg-gray-50 border-gray-200 text-gray-700',
    };

    // Normalise to an array of messages
    if (is_null($message) && empty($messages)) {
        $messageList = [];
    } elseif (!is_null($message)) {
        $messageList = is_array($message) ? $message : [$message];
    } else {
        $messageList = is_array($messages) ? $messages : [$messages];
    }

    $id = 'alert-' . uniqid();
@endphp

@if (!empty($messageList))
<div id="{{ $id }}"
     class="mb-4 flex items-start justify-between gap-3 px-4 py-3 rounded-md border {{ $classes }}"
     role="alert">
    <div class="flex-1">
        @foreach ($messageList as $msg)
            <p>{!! $msg !!}</p>
        @endforeach
    </div>
    @if($dismissible)
        <button onclick="document.getElementById('{{ $id }}').remove()" type="button"
                class="text-current opacity-50 hover:opacity-75 transition-opacity">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
    @endif
</div>
@endif
