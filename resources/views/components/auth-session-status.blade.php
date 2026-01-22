@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'alert alert-success small mb-4']) }}>
        {{ $status }}
    </div>
@endif
