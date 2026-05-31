@props(['variant' => 'glass', 'rounded' => '3xl', 'padding' => 'p-7'])
<div {{ $attributes->merge(['class' => "{$variant} rounded-{$rounded} {$padding}"]) }}>
    {{ $slot }}
</div>
