@props(['active'])

@php
$classes = ($active ?? false)
            ? 'nav-link active fw-bold text-primary active-border'
            : 'nav-link text-secondary';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
