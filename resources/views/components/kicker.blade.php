@props(['color' => 'orange'])
<span {{ $attributes->merge(['class' => "kicker-{$color} rounded-full w-fit"]) }}>{{ $slot }}</span>
