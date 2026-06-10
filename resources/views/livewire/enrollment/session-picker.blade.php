<div class="space-y-8">

    {{-- En-tête --}}
    <div>
        <h1 class="text-2xl font-bold text-blanc-pur">Choisir votre session</h1>
        <p class="mt-1 text-sm text-gris-400">
            Sélectionnez l'atelier auquel vous souhaitez participer.
            Une seule inscription est possible par participant.
        </p>
    </div>

    {{-- Message d'erreur global --}}
    @if ($errors->has('selectedWorkshopId'))
        <div role="alert"
             class="flex items-start gap-3 rounded-xl border border-red-500/30 bg-red-900/20 px-4 py-3 text-sm text-red-300">
            <svg class="mt-0.5 h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01M21 12A9 9 0 1 1 3 12a9 9 0 0 1 18 0Z"/>
            </svg>
            <span>{{ $errors->first('selectedWorkshopId') }}</span>
        </div>
    @endif

    {{-- Liste des workshops --}}
    @if ($this->workshops->isEmpty())
        <div class="rounded-2xl border border-white/10 bg-white/5 px-6 py-12 text-center">
            <p class="text-gris-400">Aucune session disponible pour le moment.</p>
            <p class="mt-1 text-sm text-gris-500">Veuillez revenir ultérieurement ou contacter l'organisation.</p>
        </div>
    @else
        <fieldset class="space-y-4">
            <legend class="sr-only">Sessions disponibles</legend>

            @foreach ($this->workshops as $workshop)
                @php
                    $isFull    = $workshop->isFull();
                    $spotsLeft = $workshop->spotsLeft();
                    $isSelected = (int) $selectedWorkshopId === $workshop->id;
                    $percent   = $workshop->capacity > 0
                        ? min(100, (int) round(($workshop->registered_count / $workshop->capacity) * 100))
                        : 0;
                @endphp

                <label for="workshop-{{ $workshop->id }}"
                       class="group flex cursor-pointer flex-col gap-4 rounded-2xl border p-5 transition-all duration-200
                              {{ $isSelected
                                  ? 'border-[#2B9E5E] bg-[#2B9E5E]/10 ring-1 ring-[#2B9E5E]'
                                  : 'border-white/10 bg-white/5 hover:border-white/20 hover:bg-white/8' }}">

                    {{-- Radio + Titre + Badge --}}
                    <div class="flex items-start gap-4">
                        {{-- Radio button --}}
                        <div class="mt-0.5 flex-shrink-0">
                            <input type="radio"
                                   id="workshop-{{ $workshop->id }}"
                                   name="workshop"
                                   value="{{ $workshop->id }}"
                                   wire:model="selectedWorkshopId"
                                   {{ $isFull ? '' : '' }}
                                   class="h-4 w-4 cursor-pointer border-white/30 bg-transparent accent-[#2B9E5E]
                                          focus:ring-2 focus:ring-[#2B9E5E] focus:ring-offset-0">
                        </div>

                        {{-- Contenu --}}
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-base font-semibold leading-snug text-blanc-pur">
                                    {{ $workshop->title }}
                                </span>

                                {{-- Badge statut --}}
                                @if ($isFull)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-900/40 px-2.5 py-0.5 text-xs font-medium text-amber-300 ring-1 ring-amber-500/30">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 9v2m0 4h.01M21 12A9 9 0 1 1 3 12a9 9 0 0 1 18 0Z"/>
                                        </svg>
                                        Complet — liste d'attente
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-[#2B9E5E]/20 px-2.5 py-0.5 text-xs font-medium text-[#2B9E5E] ring-1 ring-[#2B9E5E]/30">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0Z"/>
                                        </svg>
                                        {{ $spotsLeft }} place{{ $spotsLeft > 1 ? 's' : '' }} restante{{ $spotsLeft > 1 ? 's' : '' }}
                                    </span>
                                @endif
                            </div>

                            @if ($workshop->short_description)
                                <p class="mt-1.5 text-sm leading-relaxed text-gris-400">
                                    {{ $workshop->short_description }}
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Jauge de remplissage --}}
                    <div class="space-y-1.5">
                        <div class="flex justify-between text-xs text-gris-500">
                            <span>{{ $workshop->registered_count }} inscrit{{ $workshop->registered_count > 1 ? 's' : '' }}</span>
                            <span>Capacité : {{ $workshop->capacity }}</span>
                        </div>
                        <div class="h-1.5 w-full overflow-hidden rounded-full bg-white/10">
                            <div class="h-full rounded-full transition-all duration-500
                                        {{ $isFull ? 'bg-amber-400' : 'bg-[#2B9E5E]' }}"
                                 style="width: {{ $percent }}%"
                                 role="progressbar"
                                 aria-valuenow="{{ $workshop->registered_count }}"
                                 aria-valuemin="0"
                                 aria-valuemax="{{ $workshop->capacity }}"
                                 aria-label="Taux de remplissage : {{ $percent }} %">
                            </div>
                        </div>
                    </div>
                </label>
            @endforeach
        </fieldset>

        {{-- Bouton de confirmation --}}
        <div class="flex items-center gap-4 pt-2">
            <button type="button"
                    wire:click="submit"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-60 cursor-not-allowed"
                    @disabled(! $selectedWorkshopId)
                    class="relative inline-flex items-center gap-2 rounded-xl bg-[#2B9E5E] px-6 py-3 text-sm font-semibold
                           text-white shadow-lg shadow-[#2B9E5E]/25 transition-all duration-200
                           hover:bg-[#239150] hover:shadow-[#2B9E5E]/40
                           focus:outline-none focus:ring-2 focus:ring-[#2B9E5E] focus:ring-offset-2 focus:ring-offset-[#0A1628]
                           disabled:cursor-not-allowed disabled:opacity-40">

                {{-- Spinner de chargement --}}
                <svg wire:loading
                     wire:target="submit"
                     class="h-4 w-4 animate-spin"
                     fill="none"
                     viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor"
                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4Z"/>
                </svg>

                {{-- Icône normale --}}
                <svg wire:loading.remove
                     wire:target="submit"
                     class="h-4 w-4"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0Z"/>
                </svg>

                <span wire:loading.remove wire:target="submit">Confirmer mon inscription</span>
                <span wire:loading wire:target="submit">Traitement en cours…</span>
            </button>

            <a href="{{ route('candidate.dashboard') }}"
               class="text-sm text-gris-400 transition-colors hover:text-blanc-pur">
                Annuler
            </a>
        </div>

        {{-- Note liste d'attente --}}
        <p class="text-xs text-gris-500">
            Si la session choisie est complète, vous serez automatiquement placé(e) sur liste d'attente.
            Vous serez notifié(e) par e-mail en cas de place libérée.
        </p>
    @endif

</div>
