@props([
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'href' => null
])

@php
    $variants = [
        'primary' => 'bg-primary hover:bg-primary/90 text-white shadow-sm shadow-primary/20',
        'secondary' => 'bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-900 dark:text-white',
        'success' => 'bg-emerald-500 hover:bg-emerald-600 text-white',
        'danger' => 'bg-rose-500 hover:bg-rose-600 text-white',
        'warning' => 'bg-amber-500 hover:bg-amber-600 text-white',
        'outline' => 'border border-slate-300 dark:border-slate-600 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200',
        'ghost' => 'hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200',
    ];
    
    $sizes = [
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base',
    ];
    
    $baseClasses = 'inline-flex items-center justify-center space-x-2 font-medium rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary disabled:opacity-50 disabled:cursor-not-allowed';
    $variantClasses = $variants[$variant] ?? $variants['primary'];
    $sizeClasses = $sizes[$size] ?? $sizes['md'];
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => "$baseClasses $variantClasses $sizeClasses"]) }}>
        @if($icon)
            <span class="material-icons-round text-sm">{{ $icon }}</span>
        @endif
        <span>{{ $slot }}</span>
    </a>
@else
    <button {{ $attributes->merge(['class' => "$baseClasses $variantClasses $sizeClasses"]) }}>
        @if($icon)
            <span class="material-icons-round text-sm">{{ $icon }}</span>
        @endif
        <span>{{ $slot }}</span>
    </button>
@endif
