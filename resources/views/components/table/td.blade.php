@props(['align' => 'left', 'primary' => false])
@php
    $alignClass = match($align) {
        'right' => 'text-right',
        'center' => 'text-center',
        default => 'text-left',
    };
    $textClass = $primary ? 'text-gray-900 font-medium' : 'text-gray-500';
@endphp

<td {{ $attributes->merge(['class' => "px-6 py-4 text-sm {$textClass} {$alignClass}"]) }}>
    {{ $slot }}
</td>
