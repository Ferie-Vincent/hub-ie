<div class="min-h-screen px-4 py-10 sm:px-6 lg:px-8">
    {{-- Header --}}
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Mes documents de cours</h1>
            <p class="mt-1 text-sm text-white/60">Documents mis à disposition par vos formateurs</p>
        </div>
        @if($hasNewFiles)
            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/20 px-3 py-1 text-sm font-medium text-emerald-400 ring-1 ring-emerald-500/30">
                <span class="relative flex h-2 w-2">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                </span>
                {{ $newFilesCount }} nouveau{{ $newFilesCount > 1 ? 'x' : '' }}
            </span>
        @endif
    </div>

    @if(session('error'))
        <div class="mb-6 rounded-xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-400">
            {{ session('error') }}
        </div>
    @endif

    @if($workshops->isEmpty())
        <div class="glass rounded-2xl p-10 text-center">
            <x-heroicon-o-folder-open class="mx-auto mb-4 h-12 w-12 text-white/30" />
            <p class="text-white/50">Aucun atelier associé à votre candidature.</p>
        </div>
    @else
        <div class="space-y-8">
            @foreach($workshops as $workshop)
                @php $files = $courseFiles->get($workshop->id, collect()); @endphp

                <div class="glass rounded-2xl overflow-hidden">
                    {{-- Atelier header --}}
                    <div class="flex items-center gap-3 border-b border-white/10 px-6 py-4">
                        @if($workshop->icon_path)
                            <img src="{{ asset($workshop->icon_path) }}" alt="" class="h-8 w-8 rounded-lg object-cover">
                        @else
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-[hsl(var(--brand-hsl)/0.2)]">
                                <x-heroicon-o-academic-cap class="h-4 w-4 text-[hsl(var(--brand-hsl))]" />
                            </div>
                        @endif
                        <h2 class="font-semibold text-white">{{ $workshop->title }}</h2>
                        <span class="ml-auto text-xs text-white/40">{{ $files->count() }} document{{ $files->count() > 1 ? 's' : '' }}</span>
                    </div>

                    <div class="p-6">
                        @if($files->isEmpty())
                            <p class="py-4 text-center text-sm text-white/40">
                                Aucun document disponible pour cet atelier.
                            </p>
                        @else
                            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                @foreach($files as $file)
                                    @php
                                        $isNew = $file->created_at->isAfter(now()->subDays(7));
                                        $iconColor = match(true) {
                                            str_contains($file->mime_type, 'pdf')   => 'text-red-400 bg-red-500/10',
                                            str_contains($file->mime_type, 'powerpoint') || str_contains($file->mime_type, 'presentation') => 'text-orange-400 bg-orange-500/10',
                                            str_contains($file->mime_type, 'word') || str_contains($file->mime_type, 'document') => 'text-blue-400 bg-blue-500/10',
                                            str_contains($file->mime_type, 'video') => 'text-violet-400 bg-violet-500/10',
                                            str_contains($file->mime_type, 'zip') || str_contains($file->mime_type, 'compressed') => 'text-slate-400 bg-slate-500/10',
                                            default => 'text-white/60 bg-white/10',
                                        };
                                    @endphp
                                    <div class="group relative flex flex-col gap-3 rounded-xl border border-white/10 bg-white/5 p-4 transition hover:border-white/20 hover:bg-white/10">
                                        @if($isNew)
                                            <span class="absolute right-3 top-3 rounded-full bg-emerald-500/20 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-emerald-400">
                                                Nouveau
                                            </span>
                                        @endif

                                        <div class="flex items-start gap-3">
                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ $iconColor }}">
                                                <x-heroicon-o-document class="h-5 w-5" />
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="truncate text-sm font-medium text-white">{{ $file->title }}</p>
                                                <p class="text-xs text-white/40">{{ $file->file_size_human }}</p>
                                            </div>
                                        </div>

                                        @if($file->description)
                                            <p class="text-xs leading-relaxed text-white/50">{{ $file->description }}</p>
                                        @endif

                                        <div class="mt-auto flex items-center justify-between text-xs text-white/30">
                                            <span>{{ $file->created_at->format('d/m/Y') }}</span>
                                            <button
                                                wire:click="download({{ $file->id }})"
                                                wire:loading.attr="disabled"
                                                class="btn-fill inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-medium transition"
                                            >
                                                <x-heroicon-o-arrow-down-tray class="h-3.5 w-3.5" />
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
