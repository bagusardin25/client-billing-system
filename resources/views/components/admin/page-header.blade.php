@props([
    'title',
    'subtitle' => null,
    'actionUrl' => null,
    'actionLabel' => null,
    'actionIcon' => 'add'
])

<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h1 class="h2 fw-bold text-body-emphasis mb-1">{{ $title }}</h1>
        @if($subtitle)
            <p class="text-secondary small mb-0">{!! $subtitle !!}</p>
        @endif
    </div>
    
    @if($actionUrl && $actionLabel)
        <a href="{{ $actionUrl }}" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm">
            <i class="bi bi-plus-lg"></i>
            <span>{{ $actionLabel }}</span>
        </a>
    @endif
    
    {{ $actions ?? '' }}
</div>
