@props([
    'title',
    'description',
])

<div class="flex w-full flex-col text-center text-white">
    <flux:heading size="xl">{{ $title }}</flux:heading>
    <flux:subheading class="text-white/70">{{ $description }}</flux:subheading>
</div>
