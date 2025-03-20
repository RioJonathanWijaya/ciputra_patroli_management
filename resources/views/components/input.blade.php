@props(['type' => 'text', 'placeholder' => '', 'value' => ''])

<div class="flex flex-col">
    <input
        type="{{ $type }}"
        placeholder="{{ $placeholder }}"
        value="{{ $value }}"
        {{ $attributes->merge(['class' => 'border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#1C3A6B]']) }}
    />
</div>
