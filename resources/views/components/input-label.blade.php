@props(['value'])

<label {{ $attributes->merge(['class' => 'form-label fw-medium text-secondary small']) }}>
    {{ $value ?? $slot }}
</label>
