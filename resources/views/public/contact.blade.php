<x-layouts.public :title="'Contact — Hub Import-Export 2026'" :darkHero="true">
<x-slot:head>
<style>
@media (min-width: 1024px) {
    .contact-grid { display: grid; grid-template-columns: 1fr 1.4fr; gap: 2.5rem; align-items: start; }
}

/* ── v10-float : icône flottante au hover (BRIEF §II.5.9) ───── */
@keyframes v10-float {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50%       { transform: translateY(-6px) rotate(-2deg); }
}

/* ── Contact card hover (BRIEF §II.5.11) ────────────────────── */
.contact-card {
    position: relative;
    overflow: hidden;
    transition: transform 0.5s cubic-bezier(0.16,1,0.3,1), box-shadow 0.5s cubic-bezier(0.16,1,0.3,1);
    cursor: default;
}
.contact-card::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(229,107,26,0.07) 0%, transparent 60%);
    opacity: 0;
    transition: opacity 0.5s ease;
    pointer-events: none;
}
.contact-card::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 2px;
    background: linear-gradient(to right, hsl(var(--orange-ivoire)), transparent);
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.6s cubic-bezier(0.16,1,0.3,1);
}
.contact-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 48px rgba(15,12,8,0.12), 0 4px 12px rgba(15,12,8,0.06);
}
.contact-card:hover::before { opacity: 1; }
.contact-card:hover::after  { transform: scaleX(1); }
.contact-card:hover .contact-icon {
    animation: v10-float 3s ease-in-out infinite;
}

/* Card orange hover — gradient différent */
.contact-card-orange::before {
    background: linear-gradient(135deg, rgba(255,255,255,0.08) 0%, transparent 60%);
}
.contact-card-orange::after {
    background: linear-gradient(to right, rgba(255,255,255,0.5), transparent);
}
</style>
</x-slot:head>

{{-- ── Hero ──────────────────────────────────────────────────────── --}}
<x-page-hero
    kicker="Contact"
    description="Vous avez une question sur le Hub, besoin d'assistance ou souhaitez proposer un partenariat ? Notre équipe est à votre disposition pour vous répondre.">
    Contactez <em>notre équipe.</em>
</x-page-hero>

{{-- ── Corps : info + formulaire (même ligne) ──────────────────────────── --}}
<div class="bg-blanc-creme text-noir-profond">
    <section class="max-w-hub mx-auto px-6 py-16 md:py-20">

        <div class="contact-grid">

            {{-- ═══════════════════════════════ --}}
            {{-- COLONNE GAUCHE                  --}}
            {{-- ═══════════════════════════════ --}}
            <div class="space-y-5">

                {{-- Titre avec barre orange gauche (identique à la référence) --}}
                <h2 class="font-serif text-2xl font-bold pl-4"
                    style="border-left: 3.5px solid hsl(var(--orange-ivoire));">
                    Lieu de l'évènement
                </h2>

                {{-- Carte coordonnées --}}
                <div class="contact-card rounded-2xl bg-blanc-pur p-6 space-y-6"
                     style="border: 1px solid hsl(var(--noir-profond)/0.08); box-shadow: 0 1px 6px rgba(15,12,8,0.05);">

                    {{-- Adresse --}}
                    <div class="flex gap-4">
                        <div class="contact-icon flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center"
                             style="background: hsl(var(--orange-ivoire)/0.10);">
                            <svg class="w-4 h-4" fill="none" stroke="hsl(var(--orange-ivoire))" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold" style="color: #1A1208;">Adresse physique</p>
                            <p class="mt-0.5 text-sm leading-relaxed" style="color: rgba(15,12,8,0.55);">
                                Plateau, Abidjan<br>
                                République de Côte d'Ivoire
                            </p>
                        </div>
                    </div>

                    <div class="h-px" style="background: rgba(15,12,8,0.07);"></div>

                    {{-- Téléphone --}}
                    <div class="flex gap-4">
                        <div class="contact-icon flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center"
                             style="background: hsl(var(--orange-ivoire)/0.10);">
                            <svg class="w-4 h-4" fill="none" stroke="hsl(var(--orange-ivoire))" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold" style="color: #1A1208;">Téléphone</p>
                            <p class="mt-0.5 text-sm" style="color: rgba(15,12,8,0.55);">+225 27 22 XX XX XX</p>
                            <p class="text-sm" style="color: rgba(15,12,8,0.55);">+225 07 XX XX XX XX</p>
                        </div>
                    </div>

                    <div class="h-px" style="background: rgba(15,12,8,0.07);"></div>

                    {{-- Email --}}
                    <div class="flex gap-4">
                        <div class="contact-icon flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center"
                             style="background: hsl(var(--orange-ivoire)/0.10);">
                            <svg class="w-4 h-4" fill="none" stroke="hsl(var(--orange-ivoire))" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold" style="color: #1A1208;">Courrier électronique</p>
                            <a href="mailto:hub-import-export@commerce.gouv.ci"
                               class="mt-0.5 block text-sm link-underline break-all"
                               style="color: hsl(var(--orange-ivoire));">
                                hub-import-export@commerce.gouv.ci
                            </a>
                            <p class="mt-1 text-xs" style="color: rgba(15,12,8,0.38);">Réponse sous 48h ouvrables</p>
                        </div>
                    </div>
                </div>

                {{-- Card orange "Besoin de contacter l'organisation ?" --}}
                <div class="contact-card contact-card-orange rounded-2xl p-6 text-blanc-pur"
                     style="background: linear-gradient(135deg, hsl(var(--orange-brule)) 0%, hsl(var(--orange-ivoire)) 100%);">
                    <h3 class="font-serif text-xl font-bold leading-snug">
                        Besoin de contacter<br>l'organisation ?
                    </h3>
                    <p class="mt-3 text-sm leading-relaxed" style="color: rgba(255,255,255,0.80);">
                        Notre équipe d'accueil vous orientera vers le service ou la direction compétente pour traiter votre requête efficacement.
                    </p>
                    <a href="mailto:hub-import-export@commerce.gouv.ci"
                       class="mt-5 inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-bold transition-opacity hover:opacity-80"
                       style="background: rgba(255,255,255,0.18); color: #fff;">
                        Écrire un e-mail
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                </div>

            </div>

            {{-- ═══════════════════════════════ --}}
            {{-- COLONNE DROITE : FORMULAIRE     --}}
            {{-- ═══════════════════════════════ --}}
            <div class="contact-card rounded-2xl bg-blanc-pur p-6 sm:p-8"
                 style="border: 1px solid hsl(var(--noir-profond)/0.08); box-shadow: 0 1px 6px rgba(15,12,8,0.05);">

                <h2 class="font-serif text-2xl font-bold mb-7" style="color: #1A1208;">
                    Envoyez-nous un message
                </h2>

                <form
                    x-data="{ sent: false, loading: false, honeypot: '' }"
                    @submit.prevent="if(honeypot) return; loading = true; setTimeout(() => { sent = true; loading = false; }, 700)"
                    class="space-y-5"
                    aria-label="Formulaire de contact"
                >
                    {{-- Honeypot anti-spam --}}
                    <div aria-hidden="true" class="hidden">
                        <input type="text" x-model="honeypot" tabindex="-1" autocomplete="off">
                    </div>

                    {{-- NOM COMPLET + EMAIL --}}
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-[11px] font-bold uppercase tracking-[0.14em]"
                                   style="color: rgba(15,12,8,0.45);" for="c-nom">
                                Nom complet <span style="color:#ef4444;">*</span>
                            </label>
                            <input id="c-nom" type="text" required autocomplete="name"
                                   placeholder="Votre nom complet"
                                   class="w-full rounded-xl px-4 py-3 text-sm text-noir-profond outline-none transition"
                                   style="border: 1px solid rgba(15,12,8,0.14); background: hsl(var(--blanc-creme));"
                                   onfocus="this.style.borderColor='hsl(var(--orange-ivoire))'; this.style.background='#fff';"
                                   onblur="this.style.borderColor='rgba(15,12,8,0.14)'; this.style.background='hsl(var(--blanc-creme))';">
                        </div>
                        <div>
                            <label class="mb-1.5 block text-[11px] font-bold uppercase tracking-[0.14em]"
                                   style="color: rgba(15,12,8,0.45);" for="c-email">
                                Email professionnel <span style="color:#ef4444;">*</span>
                            </label>
                            <input id="c-email" type="email" required autocomplete="email"
                                   placeholder="exemple@organisation.com"
                                   class="w-full rounded-xl px-4 py-3 text-sm text-noir-profond outline-none transition"
                                   style="border: 1px solid rgba(15,12,8,0.14); background: hsl(var(--blanc-creme));"
                                   onfocus="this.style.borderColor='hsl(var(--orange-ivoire))'; this.style.background='#fff';"
                                   onblur="this.style.borderColor='rgba(15,12,8,0.14)'; this.style.background='hsl(var(--blanc-creme))';">
                        </div>
                    </div>

                    {{-- SUJET --}}
                    <div>
                        <label class="mb-1.5 block text-[11px] font-bold uppercase tracking-[0.14em]"
                               style="color: rgba(15,12,8,0.45);" for="c-sujet">
                            Sujet <span style="color:#ef4444;">*</span>
                        </label>
                        <div class="relative">
                            <select id="c-sujet" required
                                    class="w-full appearance-none rounded-xl px-4 py-3 pr-10 text-sm text-noir-profond outline-none transition"
                                    style="border: 1px solid rgba(15,12,8,0.14); background: hsl(var(--blanc-creme));"
                                    onfocus="this.style.borderColor='hsl(var(--orange-ivoire))'; this.style.background='#fff';"
                                    onblur="this.style.borderColor='rgba(15,12,8,0.14)'; this.style.background='hsl(var(--blanc-creme))';">
                                <option value="">Sélectionnez un sujet…</option>
                                <option value="candidature">Candidature & inscription</option>
                                <option value="partenariat">Demande de partenariat</option>
                                <option value="presse">Presse & médias</option>
                                <option value="programme">Programme & ateliers</option>
                                <option value="information">Informations générales</option>
                                <option value="technique">Assistance technique</option>
                                <option value="autre">Autre</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center" aria-hidden="true">
                                <svg class="w-4 h-4" fill="none" stroke="rgba(15,12,8,0.38)" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- MESSAGE --}}
                    <div>
                        <label class="mb-1.5 block text-[11px] font-bold uppercase tracking-[0.14em]"
                               style="color: rgba(15,12,8,0.45);" for="c-message">
                            Message <span style="color:#ef4444;">*</span>
                        </label>
                        <textarea id="c-message" required rows="6"
                                  placeholder="Comment pouvons-nous vous aider ?"
                                  class="w-full resize-none rounded-xl px-4 py-3 text-sm text-noir-profond outline-none transition"
                                  style="border: 1px solid rgba(15,12,8,0.14); background: hsl(var(--blanc-creme));"
                                  onfocus="this.style.borderColor='hsl(var(--orange-ivoire))'; this.style.background='#fff';"
                                  onblur="this.style.borderColor='rgba(15,12,8,0.14)'; this.style.background='hsl(var(--blanc-creme))';">
                        </textarea>
                    </div>

                    {{-- Bouton + état envoyé --}}
                    <div x-show="!sent">
                        <button type="submit" :disabled="loading"
                                class="btn-fill w-full py-4 text-base disabled:cursor-wait disabled:opacity-75">
                            <span x-text="loading ? 'Envoi en cours…' : 'Envoyer le message'"></span>
                            <svg x-show="!loading" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>

                    <div x-show="sent" x-transition
                         class="rounded-2xl p-6 text-center"
                         style="display:none; background: hsl(var(--vert-soft-bg)); border: 1px solid hsl(var(--vert-ivoire)/0.20);">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-4"
                             style="background: hsl(var(--vert-ivoire)/0.14);">
                            <svg class="w-6 h-6" fill="none" stroke="hsl(var(--vert-ivoire))" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="font-semibold" style="color: hsl(var(--vert-ivoire));">Message transmis avec succès.</p>
                        <p class="mt-1 text-sm" style="color: rgba(15,12,8,0.50);">Notre équipe vous répondra sous 48h ouvrables.</p>
                    </div>
                </form>
            </div>

        </div>
    </section>

    {{-- ═══════════════════════════════════════ --}}
    {{-- SECTION CARTE — plein largeur en bas    --}}
    {{-- ═══════════════════════════════════════ --}}
    <div style="border-top: 1px solid rgba(15,12,8,0.08);">
        <section class="max-w-hub mx-auto px-6 py-12">

            <div class="flex items-center justify-between mb-6">
                <h2 class="font-serif text-2xl font-bold" style="color: #1A1208;">
                    Lieu de l'évènement
                </h2>
                <a href="https://maps.google.com/?q=Plateau,+Abidjan,+Côte+d'Ivoire"
                   target="_blank" rel="noopener noreferrer"
                   class="flex items-center gap-1.5 text-sm font-semibold transition-opacity hover:opacity-70"
                   style="color: hsl(var(--orange-ivoire));">
                    Ouvrir dans Google Maps
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </a>
            </div>

            <div class="overflow-hidden rounded-2xl" style="height: 420px; border: 1px solid rgba(15,12,8,0.08);">
                <iframe
                    title="Carte — Plateau, Abidjan"
                    src="https://maps.google.com/maps?q=Plateau,+Abidjan,+Côte+d%27Ivoire&t=&z=15&ie=UTF8&iwloc=&output=embed"
                    width="100%"
                    height="100%"
                    style="border: none; display: block;"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    aria-label="Carte Google Maps — Plateau, Abidjan"
                ></iframe>
            </div>

            <p class="mt-3 text-xs text-center" style="color: rgba(15,12,8,0.35);">
                Hub Import-Export 2026 · Plateau, Abidjan · République de Côte d'Ivoire
            </p>
        </section>
    </div>

</div>

</x-layouts.public>
