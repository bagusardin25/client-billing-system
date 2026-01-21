@props([
    'type' => 'success',
    'message' => null,
    'dismissible' => true
])

@php
    $types = [
        'success' => [
            'bg' => 'bg-emerald-50 dark:bg-emerald-900/20',
            'border' => 'border-emerald-200 dark:border-emerald-800',
            'text' => 'text-emerald-800 dark:text-emerald-200',
            'icon' => 'check_circle'
        ],
        'error' => [
            'bg' => 'bg-rose-50 dark:bg-rose-900/20',
            'border' => 'border-rose-200 dark:border-rose-800',
            'text' => 'text-rose-800 dark:text-rose-200',
            'icon' => 'error'
        ],
        'warning' => [
            'bg' => 'bg-amber-50 dark:bg-amber-900/20',
            'border' => 'border-amber-200 dark:border-amber-800',
            'text' => 'text-amber-800 dark:text-amber-200',
            'icon' => 'warning'
        ],
        'info' => [
            'bg' => 'bg-blue-50 dark:bg-blue-900/20',
            'border' => 'border-blue-200 dark:border-blue-800',
            'text' => 'text-blue-800 dark:text-blue-200',
            'icon' => 'info'
        ],
    ];
    
    $style = $types[$type] ?? $types['info'];
@endphp

@if($message || $slot->isNotEmpty())
<div {{ $attributes->merge(['class' => "flex items-center justify-between p-4 rounded-lg border {$style['bg']} {$style['border']} {$style['text']}"]) }}>
    <div class="flex items-center space-x-3">
        <span class="material-icons-round">{{ $style['icon'] }}</span>
        <span class="text-sm font-medium">{{ $message ?? $slot }}</span>
    </div>
    
    @if($dismissible)
        <button onclick="this.parentElement.remove()" class="p-1 hover:opacity-70 transition-opacity">
            <span class="material-icons-round text-sm">close</span>
        </button>
    @endif
</div>
@endif
