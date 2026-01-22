@props(['active'])

@php
$classes = ($active ?? false)
            ? 'nav-link active fw-bold text-primary border-bottom border-primary border-2'
            : 'nav-link text-secondary hover-text-primary';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
