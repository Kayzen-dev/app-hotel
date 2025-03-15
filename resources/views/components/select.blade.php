@props(['disabled' => false])

<select style="width: 100px"  {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'select select-bordered w-full max-w-xs border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) !!}>
    {{ $slot }}
</select>
