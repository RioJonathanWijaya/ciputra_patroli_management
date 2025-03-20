@props(['type' => 'button', 'color' => 'primary'])

@php
    $baseClasses = 'font-normal text-white px-5 py-2 rounded-lg transition duration-200 ease-in-out';
    $colors = [
        'primary' => 'bg-[#1C3A6B] hover:bg-[#173058]',
        'secondary' => 'bg-[#0D7C5D] hover:bg-[#09674B]',
        'danger' => 'bg-red-600 hover:bg-red-700',
        'success' => 'bg-green-600 hover:bg-green-700',
    ];
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $baseClasses . ' ' . ($colors[$color] ?? $colors['primary'])]) }}>
    {{ $slot }}
</button>
