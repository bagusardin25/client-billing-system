<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-light border text-secondary rounded-3 fw-medium px-4']) }}>
    {{ $slot }}
</button>
