@props(['id' => null, 'maxWidth' => null, 'submit' => null])

<x-upload-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    @if ($submit) 
        <form wire:submit.prevent="{{ $submit }}"> 
    @endif
    
    <div class="px-6 py-4">
        <div class="text-lg font-medium dark:text-gray-600">
            {{ $title }}
        </div>

        <div class="mt-4 text-sm text-gray-600">
            {{ $content }}
        </div>
    </div>

    <div class="flex flex-row justify-end px-6 py-4 text-end">
        {{ $footer }}
    </div>

    @if ($submit) 
        </form> 
    @endif
</x-upload-modal>
