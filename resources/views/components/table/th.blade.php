@props(['align' => 'left'])
@php
    $alignClass = match($align) {
        'right' => 'text-right',
        'center' => 'text-center',
        default => 'text-left',
    };
@endphp

<th {{ $attributes->merge(['class' => "px-6 py-3 {$alignClass} text-xs font-semibold text-gray-500 uppercase tracking-wider"]) }}>
    {{ $slot }}
</th>
