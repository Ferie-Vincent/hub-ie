<x-filament-panels::page>

@php $acceptedPct = round($quotas['accepted'] / max($quotas['quota'], 1) * 100); @endphp

<style>
:root {
  --hd-orange:#E8741C; --hd-amber:#C45A0A; --hd-gold:#F09E62;
  --hd-vert:#009A44; --hd-vert-s:#4CAF7A; --hd-sable:#C5A96A;
  --hd-noir:#0D0F0D; --hd-white:#FAFAF8;
  --hd-t1:rgba(13,15,13,.90); --hd-t2:rgba(13,15,13,.60);
  --hd-t3:rgba(13,15,13,.36); --hd-border:rgba(13,15,13,.08);
  --hd-track:rgba(13,15,13,.07); --hd-shadow:0 1px 4px rgba(13,15,13,.06);
}
.dark {
  --hd-t1:rgba(250,250,248,.90); --hd-t2:rgba(250,250,248,.60);
  --hd-t3:rgba(250,250,248,.36); --hd-border:rgba(250,250,248,.10);
  --hd-track:rgba(250,250,248,.09); --hd-shadow:none;
}
@keyframes hd-fade-up { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }
.cb-wrap { display:flex; flex-direction:column; gap:1rem; padding-bottom:2rem; animation:hd-fade-up .4s ease both; }
.cb-card { background:#fff; border:1px solid var(--hd-border); border-radius:1rem; padding:1.25rem; box-shadow:var(--hd-shadow); position:relative; overflow:hidden; }
.dark .cb-card { background:rgba(255,255,255,.045); }
.cb-kicker { font-family:'JetBrains Mono',monospace; font-size:.52rem; font-weight:700; letter-spacing:.22em; text-transform:uppercase; color:var(--hd-t3); display:block; margin-bottom:.35rem; }
.cb-num { font-family:'Fraunces',serif; font-size:2.25rem; font-weight:900; line-height:1; color:var(--hd-t1); }
.cb-track { height:4px; background:var(--hd-track); border-radius:9999px; overflow:hidden; margin-top:.5rem; }
.cb-fill { height:100%; border-radius:9999px; transition:width .6s ease; }
.cb-col-hd { font-family:'JetBrains Mono',monospace; font-size:.54rem; font-weight:700; letter-spacing:.18em; text-transform:uppercase; padding:.375rem .75rem; border-radius:.5rem; display:inline-flex; align-items:center; gap:.5rem; margin-bottom:.75rem; }
.cb-app { background:#fff; border:1px solid var(--hd-border); border-radius:.75rem; padding:.875rem 1rem; margin-bottom:.5rem; box-shadow:var(--hd-shadow); transition:transform .15s,box-shadow .15s; }
.dark .cb-app { background:rgba(255,255,255,.055); }
.cb-app:hover { transform:translateY(-1px); box-shadow:0 4px 12px rgba(13,15,13,.08); }
.cb-name { font-family:'Manrope',sans-serif; font-size:.82rem; font-weight:700; color:var(--hd-t1); margin-bottom:.125rem; }
.cb-ref { font-family:'JetBrains Mono',monospace; font-size:.6rem; font-weight:600; color:var(--hd-t3); letter-spacing:.05em; }
.cb-score { font-family:'JetBrains Mono',monospace; font-size:.72rem; font-weight:700; flex-shrink:0; }
.cb-btn { display:inline-flex; align-items:center; font-size:.68rem; font-weight:700; padding:.25rem .625rem; border-radius:.4rem; border:none; cursor:pointer; transition:opacity .15s,transform .15s; letter-spacing:.02em; }
.cb-btn:hover { opacity:.80; transform:scale(.98); }
.cb-empty { padding:2rem 1rem; text-align:center; color:var(--hd-t3); font-family:'JetBrains Mono',monospace; font-size:.6rem; letter-spacing:.1em; text-transform:uppercase; }
</style>

<div class="cb-wrap">

{{-- ── KPI strip ── --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;">

  <div class="cb-card" style="border-left:3px solid var(--hd-vert);">
    <span class="cb-kicker">Retenu(e)s</span>
    <div class="cb-num" style="color:var(--hd-vert);">{{ $quotas['accepted'] }}</div>
    <div class="cb-track"><div class="cb-fill" style="width:{{ $acceptedPct }}%;background:var(--hd-vert);"></div></div>
    <span style="font-family:'JetBrains Mono',monospace;font-size:.58rem;color:var(--hd-t3);">{{ $acceptedPct }}% du quota</span>
  </div>

  <div class="cb-card" style="border-left:3px solid var(--hd-border);">
    <span class="cb-kicker">Quota total</span>
    <div class="cb-num">{{ $quotas['quota'] }}</div>
    <div class="cb-track"><div class="cb-fill" style="width:100%;background:var(--hd-track);"></div></div>
    <span style="font-family:'JetBrains Mono',monospace;font-size:.58rem;color:var(--hd-t3);">Objectif comité</span>
  </div>

  <div class="cb-card" style="border-left:3px solid {{ $quotas['remaining'] > 0 ? 'var(--hd-vert-s)' : '#ef4444' }};">
    <span class="cb-kicker">Places restantes</span>
    <div class="cb-num" style="color:{{ $quotas['remaining'] > 0 ? 'var(--hd-vert)' : '#ef4444' }};">{{ $quotas['remaining'] }}</div>
    @php $remPct = round($quotas['remaining'] / max($quotas['quota'],1) * 100); @endphp
    <div class="cb-track"><div class="cb-fill" style="width:{{ $remPct }}%;background:{{ $quotas['remaining'] > 0 ? 'var(--hd-vert-s)' : '#ef4444' }};"></div></div>
    <span style="font-family:'JetBrains Mono',monospace;font-size:.58rem;color:var(--hd-t3);">Disponibles</span>
  </div>

  <div class="cb-card" style="border-left:3px solid var(--hd-sable);">
    <span class="cb-kicker">Présélectionné(e)s</span>
    <div class="cb-num" style="color:var(--hd-sable);">{{ $quotas['shortlisted'] }}</div>
    @php $shPct = round($quotas['shortlisted'] / max($quotas['quota'],1) * 100); @endphp
    <div class="cb-track"><div class="cb-fill" style="width:{{ $shPct }}%;background:var(--hd-sable);"></div></div>
    <span style="font-family:'JetBrains Mono',monospace;font-size:.58rem;color:var(--hd-t3);">En attente décision finale</span>
  </div>

</div>

{{-- ── Kanban ── --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;">

  {{-- Éligibles --}}
  <div style="display:flex;flex-direction:column;max-height:70vh;">
    <div class="cb-col-hd" style="background:rgba(59,130,246,.10);color:rgb(59,130,246);flex-shrink:0;">
      <svg style="width:12px;height:12px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      Éligibles <span style="opacity:.55;margin-left:.25rem;">{{ $eligible->count() }}</span>
    </div>
    @forelse($eligible as $app)
    <div class="cb-app">
      <p class="cb-name">{{ $app->user?->full_name }}</p>
      <p class="cb-ref">{{ $app->reference_code }}</p>
      <div style="display:flex;gap:.375rem;flex-wrap:wrap;margin-top:.625rem;">
        <button wire:click="transition({{ $app->id }}, 'under_review')"
                wire:loading.attr="disabled" wire:target="transition({{ $app->id }}, 'under_review')"
                class="cb-btn" style="background:rgba(168,85,247,.12);color:rgb(147,51,234);">
          Évaluer →
        </button>
        <button wire:click="transition({{ $app->id }}, 'incomplete')"
                wire:confirm="Marquer ce dossier comme incomplet ?"
                wire:loading.attr="disabled" wire:target="transition({{ $app->id }}, 'incomplete')"
                class="cb-btn" style="background:rgba(232,116,28,.12);color:var(--hd-orange);">
          Incomplet
        </button>
      </div>
    </div>
    @empty
    <div class="cb-empty">Aucun dossier éligible</div>
    @endforelse
  </div>

  {{-- En évaluation --}}
  <div style="display:flex;flex-direction:column;max-height:70vh;">
    <div class="cb-col-hd" style="background:rgba(168,85,247,.10);color:rgb(147,51,234);flex-shrink:0;">
      <svg style="width:12px;height:12px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
      En évaluation <span style="opacity:.55;margin-left:.25rem;">{{ $underReview->count() }}</span>
    </div>
    @forelse($underReview as $app)
    <div class="cb-app">
      <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:.5rem;">
        <p class="cb-name" style="flex:1;">{{ $app->user?->full_name }}</p>
        @if($app->average_score !== null)
        <span class="cb-score" style="color:var(--hd-orange);">{{ number_format($app->average_score, 1) }}<span style="font-size:.55rem;opacity:.6;">/10</span></span>
        @endif
      </div>
      <p class="cb-ref">{{ $app->reference_code }}</p>
      <div style="display:flex;gap:.375rem;flex-wrap:wrap;margin-top:.625rem;">
        <button wire:click="transition({{ $app->id }}, 'shortlisted')"
                wire:loading.attr="disabled" wire:target="transition({{ $app->id }}, 'shortlisted')"
                class="cb-btn" style="background:rgba(197,169,106,.14);color:var(--hd-sable);">
          Présélectionner →
        </button>
        <button wire:click="transition({{ $app->id }}, 'rejected')"
                wire:confirm="Confirmer le refus de {{ $app->user?->full_name }} ?"
                wire:loading.attr="disabled" wire:target="transition({{ $app->id }}, 'rejected')"
                class="cb-btn" style="background:rgba(239,68,68,.10);color:#ef4444;">
          Refuser
        </button>
      </div>
    </div>
    @empty
    <div class="cb-empty">Aucun dossier en évaluation</div>
    @endforelse
  </div>

  {{-- Présélectionné(e)s --}}
  <div style="display:flex;flex-direction:column;max-height:70vh;">
    <div class="cb-col-hd" style="background:rgba(197,169,106,.14);color:var(--hd-sable);flex-shrink:0;">
      <svg style="width:12px;height:12px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
      Présélectionné(e)s <span style="opacity:.55;margin-left:.25rem;">{{ $shortlisted->count() }}</span>
    </div>
    @forelse($shortlisted as $app)
    <div class="cb-app" style="border-left:2px solid var(--hd-sable);">
      <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:.5rem;">
        <p class="cb-name" style="flex:1;">{{ $app->user?->full_name }}</p>
        @if($app->average_score !== null)
        <span class="cb-score" style="color:var(--hd-sable);">{{ number_format($app->average_score, 1) }}<span style="font-size:.55rem;opacity:.6;">/10</span></span>
        @endif
      </div>
      <p class="cb-ref">{{ $app->reference_code }}</p>
      <div style="display:flex;gap:.375rem;flex-wrap:wrap;margin-top:.625rem;">
        <button wire:click="transition({{ $app->id }}, 'accepted')"
                wire:loading.attr="disabled" wire:target="transition({{ $app->id }}, 'accepted')"
                class="cb-btn" style="background:rgba(0,154,68,.14);color:var(--hd-vert);">
          Retenir ✓
        </button>
        <button wire:click="transition({{ $app->id }}, 'waitlisted')"
                wire:loading.attr="disabled" wire:target="transition({{ $app->id }}, 'waitlisted')"
                class="cb-btn" style="background:rgba(59,130,246,.10);color:rgb(59,130,246);">
          Attente
        </button>
        <button wire:click="transition({{ $app->id }}, 'rejected')"
                wire:confirm="Confirmer le refus définitif de {{ $app->user?->full_name }} ?"
                wire:loading.attr="disabled" wire:target="transition({{ $app->id }}, 'rejected')"
                class="cb-btn" style="background:rgba(239,68,68,.10);color:#ef4444;">
          Refuser
        </button>
      </div>
    </div>
    @empty
    <div class="cb-empty">Aucun dossier présélectionné</div>
    @endforelse
  </div>

</div>
</div>

</x-filament-panels::page>
