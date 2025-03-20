@props([
    'name' => '',
    'options' => [],
    'selected' => old($name)
])

<select
    name="{{ $name }}"
    {{ $attributes->merge(['class' => 'w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#1C3A6B]']) }}
>
    @foreach ($options as $value => $label)
        <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }}>
            {{ $label }}
        </option>
    @endforeach
</select>
