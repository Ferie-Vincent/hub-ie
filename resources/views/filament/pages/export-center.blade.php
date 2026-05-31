<x-filament-panels::page>

<style>
:root {
  --hd-orange:#E8741C; --hd-vert:#009A44; --hd-vert-s:#4CAF7A;
  --hd-sable:#C5A96A; --hd-noir:#0D0F0D; --hd-white:#FAFAF8;
  --hd-t1:rgba(13,15,13,.90); --hd-t2:rgba(13,15,13,.60);
  --hd-t3:rgba(13,15,13,.36); --hd-border:rgba(13,15,13,.08);
  --hd-shadow:0 1px 4px rgba(13,15,13,.06);
}
.dark {
  --hd-t1:rgba(250,250,248,.90); --hd-t2:rgba(250,250,248,.60);
  --hd-t3:rgba(250,250,248,.36); --hd-border:rgba(250,250,248,.10);
  --hd-shadow:none;
}
@keyframes hd-fade-up { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }
@keyframes spin { from{transform:rotate(0deg)} to{transform:rotate(360deg)} }
.ec-wrap { animation:hd-fade-up .4s ease both; padding-bottom:2rem; }
.ec-card { background:#fff; border:1px solid var(--hd-border); border-radius:1rem; padding:1.5rem; box-shadow:var(--hd-shadow); display:flex; flex-direction:column; gap:1rem; }
.dark .ec-card { background:rgba(255,255,255,.045); }
.ec-kicker { font-family:'JetBrains Mono',monospace; font-size:.52rem; font-weight:700; letter-spacing:.22em; text-transform:uppercase; color:var(--hd-t3); display:block; margin-bottom:.375rem; }
.ec-title { font-family:'Manrope',sans-serif; font-size:.95rem; font-weight:800; color:var(--hd-t1); margin-bottom:.5rem; }
.ec-desc { font-family:'Manrope',sans-serif; font-size:.78rem; color:var(--hd-t2); line-height:1.55; }
.ec-btn { display:inline-flex; align-items:center; justify-content:center; gap:.5rem; width:100%; font-family:'Manrope',sans-serif; font-size:.78rem; font-weight:700; padding:.625rem 1rem; border-radius:.625rem; border:none; cursor:pointer; transition:opacity .15s,transform .15s; margin-top:auto; }
.ec-btn:hover { opacity:.82; transform:translateY(-1px); }
</style>

<div class="ec-wrap">

  {{-- Notice --}}
  <div style="display:flex;align-items:center;gap:.75rem;padding:.875rem 1.125rem;background:#fff;border:1px solid var(--hd-border);border-radius:.875rem;box-shadow:var(--hd-shadow);margin-bottom:1rem;">
    <svg style="width:15px;height:15px;flex-shrink:0;color:var(--hd-sable);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <p style="font-family:'JetBrains Mono',monospace;font-size:.6rem;font-weight:600;letter-spacing:.04em;color:var(--hd-t2);">Les exports reflètent les données en temps réel. Toute modification de statut après téléchargement n'est pas incluse dans le fichier.</p>
  </div>

  <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1rem;">

    {{-- Toutes les candidatures --}}
    <div class="ec-card">
      <div>
        <span class="ec-kicker">Export complet</span>
        <div class="ec-title">Toutes les candidatures</div>
        <p class="ec-desc">Tous les dossiers soumis (hors brouillons et retirés) — toutes les colonnes, tous les statuts. Point de référence global du comité de sélection.</p>
      </div>
      <button wire:click="exportAll" class="ec-btn" style="background:rgba(13,15,13,.07);color:var(--hd-t1);">
        <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Télécharger XLSX
      </button>
    </div>

    {{-- Candidatures retenues --}}
    <div class="ec-card" style="border-top:3px solid var(--hd-vert);">
      <div>
        <span class="ec-kicker" style="color:var(--hd-vert);">Retenus</span>
        <div class="ec-title">Candidatures retenues</div>
        <p class="ec-desc">Dossiers au statut <strong>Retenu(e)</strong> uniquement. Utilisé pour la préparation des badges et la communication officielle.</p>
      </div>
      <button wire:click="exportAccepted" class="ec-btn" style="background:rgba(0,154,68,.12);color:var(--hd-vert);">
        <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Télécharger XLSX
      </button>
    </div>

    {{-- Liste d'attente --}}
    <div class="ec-card" style="border-top:3px solid var(--hd-sable);">
      <div>
        <span class="ec-kicker" style="color:var(--hd-sable);">Liste d'attente</span>
        <div class="ec-title">Candidatures en attente</div>
        <p class="ec-desc">Dossiers au statut <strong>Liste d'attente</strong>. Activé si des places se libèrent avant le 22 juin 2026.</p>
      </div>
      <button wire:click="exportWaitlisted" class="ec-btn" style="background:rgba(197,169,106,.14);color:var(--hd-sable);">
        <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Télécharger XLSX
      </button>
    </div>

    {{-- PDF participants --}}
    <div class="ec-card" style="border-top:3px solid var(--hd-orange);">
      <div>
        <span class="ec-kicker" style="color:var(--hd-orange);">PDF officiel</span>
        <div class="ec-title">Liste des participants</div>
        <p class="ec-desc">Fichier PDF A4 paysage — participants retenus triés par groupe alphabétique. Destiné à l'impression et à la vérification sur site lors de l'événement.</p>
      </div>
      <button wire:click="exportParticipantsPdf" class="ec-btn" style="background:rgba(232,116,28,.12);color:var(--hd-orange);">
        <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
        Télécharger PDF
      </button>
    </div>

  </div>
</div>

</x-filament-panels::page>
