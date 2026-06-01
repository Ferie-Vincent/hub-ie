<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-serif font-bold text-2xl text-noir-profond">Mes documents de cours</h1>
            <p class="mt-1 text-sm text-gris-500">Documents mis à disposition par vos formateurs</p>
        </div>
        @if($hasNewFiles)
        <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-sm font-semibold"
              style="background: hsl(var(--vert-soft-bg)); color: hsl(var(--vert-ivoire));">
            <span class="relative flex h-2 w-2">
                <span class="absolute inline-flex h-full w-full animate-ping rounded-full opacity-75"
                      style="background: hsl(var(--vert-ivoire));"></span>
                <span class="relative inline-flex h-2 w-2 rounded-full"
                      style="background: hsl(var(--vert-ivoire));"></span>
            </span>
            {{ $newFilesCount }} nouveau{{ $newFilesCount > 1 ? 'x' : '' }}
        </span>
        @endif
    </div>

    @if(session('error'))
    <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600" role="alert">
        {{ session('error') }}
    </div>
    @endif

    @if($workshops->isEmpty())
    <div class="rounded-2xl bg-white shadow-card p-10 text-center">
        <svg class="mx-auto mb-4 h-12 w-12 text-gris-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
        </svg>
        <p class="text-gris-500">Aucun atelier associé à votre candidature.</p>
    </div>
    @else
    <div class="space-y-6">
        @foreach($workshops as $workshop)
        @php $files = $courseFiles->get($workshop->id, collect()); @endphp

        <div class="rounded-2xl bg-white shadow-card overflow-hidden">
            {{-- Atelier header --}}
            <div class="flex items-center gap-3 border-b border-sable px-6 py-4">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg flex-shrink-0"
                     style="background: hsl(var(--orange-soft-bg));">
                    <svg class="h-4 w-4" style="color: hsl(var(--orange-ivoire));" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div>
                    <h2 class="font-semibold text-noir-profond text-sm">{{ $workshop->title }}</h2>
                </div>
                <span class="ml-auto text-xs text-gris-500">
                    {{ $files->count() }} document{{ $files->count() > 1 ? 's' : '' }}
                </span>
            </div>

            <div class="p-6">
                @if($files->isEmpty())
                <p class="py-4 text-center text-sm text-gris-500">
                    Aucun document disponible pour cet atelier pour le moment.
                </p>
                @else
                <div class="grid gap-4 sm:grid-cols-2">
                    @foreach($files as $file)
                    @php
                        $isNew = $file->created_at->isAfter(now()->subDays(7));
                        [$iconBg, $iconColor] = match(true) {
                            str_contains($file->mime_type, 'pdf')   => ['bg-red-50', 'text-red-500'],
                            str_contains($file->mime_type, 'powerpoint') || str_contains($file->mime_type, 'presentation')
                                => ['bg-orange-50', 'text-orange-500'],
                            str_contains($file->mime_type, 'word') || str_contains($file->mime_type, 'document')
                                => ['bg-blue-50', 'text-blue-500'],
                            str_contains($file->mime_type, 'video') => ['bg-purple-50', 'text-purple-500'],
                            str_contains($file->mime_type, 'zip')   => ['bg-gray-100', 'text-gray-500'],
                            default => ['bg-gray-100', 'text-gray-400'],
                        };
                    @endphp
                    <div class="relative flex flex-col gap-3 rounded-xl border border-sable bg-blanc-creme/60 p-4 transition hover:border-sable hover:shadow-sm">
                        @if($isNew)
                        <span class="absolute right-3 top-3 rounded-full px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide"
                              style="background: hsl(var(--vert-soft-bg)); color: hsl(var(--vert-ivoire));">
                            Nouveau
                        </span>
                        @endif

                        <div class="flex items-start gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ $iconBg }}">
                                <svg class="h-5 w-5 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold text-noir-profond">{{ $file->title }}</p>
                                <p class="text-xs text-gris-500">{{ $file->file_size_human }}</p>
                            </div>
                        </div>

                        @if($file->description)
                        <p class="text-xs leading-relaxed text-gris-500">{{ $file->description }}</p>
                        @endif

                        <div class="mt-auto flex items-center justify-between">
                            <span class="text-xs text-gris-500">{{ $file->created_at->format('d/m/Y') }}</span>
                            <button
                                wire:click="download({{ $file->id }})"
                                wire:loading.attr="disabled"
                                class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold btn-fill transition"
                            >
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Télécharger
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif

</div>
