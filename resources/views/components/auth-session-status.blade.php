@props([
    'status',
])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-[#b7f7c8]']) }}>
        {{ $status }}
    </div>
@endif
