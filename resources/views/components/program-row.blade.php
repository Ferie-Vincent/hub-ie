@props([
    'num'      => '01',
    'tag'      => null,
    'tagColor' => 'vert',
])
<div class="v10-prog-row group">
    <span class="prog-number">{{ $num }}</span>
    <span class="flex-1 text-noir-profond font-medium text-sm leading-snug">{{ $slot }}</span>
    @if($tag)
    <x-tag :color="$tagColor">{{ $tag }}</x-tag>
    @endif
</div>
