@props([
    'name',
    'show' => false,
    'maxWidth' => '2xl'
])

@php
$maxWidth = [
    'sm' => 'modal-sm',
    'md' => '',
    'lg' => 'modal-lg',
    'xl' => 'modal-xl',
    '2xl' => 'modal-lg', // Bootstrap doesn't have 2xl, mapping to lg or xl
][$maxWidth];
@endphp

<div class="modal fade" id="{{ $name }}" tabindex="-1" aria-hidden="true" 
    x-data="{
        show: @js($show),
        focusables() {
            // ...
        }
    }"
    x-init="$watch('show', value => {
        if (value) {
            var modal = new bootstrap.Modal(document.getElementById('{{ $name }}'));
            modal.show();
        } else {
            /* Handled by bootstrap dismiss */
        }
    })"
>
    <!-- Note: This component might need more adaption to fully replace Alpine logic with Bootstrap modal -->
    <!-- For now, we assume standard Bootstrap modal usage in parent views -->
    <div class="modal-dialog {{ $maxWidth }} modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            {{ $slot }}
        </div>
    </div>
</div>
