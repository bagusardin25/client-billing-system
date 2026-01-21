@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden ' . $class]) }}>
    {{ $slot }}
</div>
