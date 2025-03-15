@props(['id' => null, 'maxWidth' => null, 'submit' => null])

<x-modal-pesan :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-3xl max-h-[90vh] overflow-auto">
            {{ $content }}
        </div>
</x-modal-pesan>
