@props(['id' => null, 'maxWidth' => null, 'submit' => null])

<x-modal-surat-masuk :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    @if ($submit) <form wire:submit.prevent="{{ $submit }}"> @endif

    <div class="border-b pb-4 m-4">
        <h2 class="text-lg font-bold dark:text-white">
            {{ $title }}
        </h2>
    </div>

    <div class="py-4 p-5">
        {{ $content }}
    </div>

    <div class="flex justify-end gap-3 m-5">
        {{ $footer }}
    </div>

    @if ($submit) </form> @endif
</x-modal-surat-masuk>
