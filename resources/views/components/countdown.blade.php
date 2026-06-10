{{-- Countdown vers le 22 juin 2026 09:00 Africa/Abidjan (countdown.js) --}}
<div class="flex items-center gap-2 mb-4">
    <span class="w-2 h-2 rounded-full animate-pulse-dot flex-shrink-0" style="background: hsl(var(--vert-ivoire));"></span>
    <p class="text-xs text-blanc-pur/60 uppercase tracking-widest font-medium">Rendez-vous à Abidjan dans</p>
</div>
<div class="grid grid-cols-4 gap-3 text-center">
    @foreach([['cd-days','Jours'],['cd-hours','Heures'],['cd-minutes','Min'],['cd-seconds','Sec']] as [$id,$label])
    <div>
        <div class="font-mono font-bold text-3xl text-blanc-pur leading-none" id="{{ $id }}">--</div>
        <div class="text-[0.6rem] text-blanc-pur/60 uppercase tracking-wider mt-2">{{ $label }}</div>
    </div>
    @endforeach
</div>
