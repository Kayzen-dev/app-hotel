@props(['id', 'maxWidth'])

@php
    $id = $id ?? md5($attributes->wire('model'));
@endphp

<div 
    x-data="{ show: @entangle($attributes->wire('model')) }"
    x-on:close.stop="show = false"
    x-on:keydown.escape.window="show = false"
    x-show="show"
    id="{{ $id }}"
    class="fixed inset-0 overflow-y-auto flex items-center justify-center px-4 py-6 sm:px-0 z-50"
    style="display: none;"
>
    <div x-show="show" class="fixed inset-0 transform transition-all" 
        x-on:click="show = false"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        <div class="absolute inset-0 bg-black opacity-50"></div>
    </div>

    <div x-show="show" 
        class="mb-6 dark:bg-gray-600 rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full 4 sm:mx-auto"
        x-trap.inert.noscroll="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
        {{ $slot }}
    </div>
</div>
