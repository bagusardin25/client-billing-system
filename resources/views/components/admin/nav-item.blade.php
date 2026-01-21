@props(['href', 'icon', 'label', 'active' => false])

@php
    $classes = $active 
        ? 'flex items-center space-x-3 p-3 bg-primary text-white rounded-lg' 
        : 'flex items-center space-x-3 p-3 hover:bg-slate-800 rounded-lg transition-colors mt-1';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    <span class="material-icons-round">{{ $icon }}</span>
    <span class="text-sm font-medium">{{ $label }}</span>
</a>
