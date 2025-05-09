@props(['size' => 'md', 'color' => 'primary'])

@php
    $sizeClasses = [
        'sm' => 'w-12 h-12',
        'md' => 'w-16 h-16',
        'lg' => 'w-24 h-24',
        'xl' => 'w-32 h-32'
    ];

    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

<div class="flex items-center justify-center">
    <div class="loader {{ $sizeClass }}">
        <img src="{{ asset('images/ciputra_logo.png') }}" alt="Ciputra Logo" class="loader-image">
    </div>
</div>

<style>
    .loader {
        display: inline-flex;
        position: relative;
        align-items: center;
        justify-content: center;
    }

    .loader-image {
        width: 100%;
        height: 100%;
        object-fit: contain;
        animation: logo-spin 2s cubic-bezier(0.4, 0, 0.2, 1) infinite;
    }

    @keyframes logo-spin {
        0% {
            transform: rotate(0deg) scale(1);
            opacity: 1;
        }
        50% {
            transform: rotate(180deg) scale(0.9);
            opacity: 0.8;
        }
        100% {
            transform: rotate(360deg) scale(1);
            opacity: 1;
        }
    }

    /* Add a subtle pulse effect to the loader */
    .loader::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background-color: currentColor;
        opacity: 0.1;
        animation: loader-pulse 2s cubic-bezier(0.4, 0, 0.2, 1) infinite;
    }

    @keyframes loader-pulse {
        0% {
            transform: scale(0.8);
            opacity: 0.1;
        }
        50% {
            transform: scale(1.2);
            opacity: 0.2;
        }
        100% {
            transform: scale(0.8);
            opacity: 0.1;
        }
    }
</style> 