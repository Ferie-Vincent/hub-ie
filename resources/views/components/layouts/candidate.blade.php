@props(['title' => null])
<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ? $title . ' — Espace candidat · Hub IE 2026' : 'Espace candidat — Hub Import-Export 2026' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300..900;1,9..144,300..900&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;700&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="antialiased font-sans bg-blanc-creme text-noir-profond min-h-screen">

{{-- Barre de navigation candidate --}}
<header class="sticky top-0 z-40 glass-light shadow-sm border-b border-blanc-pur/60">
    <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between gap-6">

        <a href="{{ route('home') }}" class="flex items-center gap-2.5 flex-shrink-0">
            <svg width="36" height="36" viewBox="0 0 44 44" fill="none" aria-hidden="true">
                <rect width="44" height="44" rx="10" fill="hsl(var(--orange-ivoire))"/>
                <text x="22" y="30" font-family="Inter,sans-serif" font-size="16" font-weight="700" fill="white" text-anchor="middle" letter-spacing="-0.5">HIE</text>
            </svg>
            <span class="font-manrope font-bold text-xs text-noir-profond leading-tight hidden sm:block">
                Hub Import-Export<br>
                <span class="font-normal opacity-60">Espace candidat</span>
            </span>
        </a>

        @php
            $navUnread = 0;
            if (auth()->check()) {
                $navApp = auth()->user()->applications()
                    ->where('status', 'accepted')->first();
                if ($navApp) {
                    $navConvIds = \App\Models\Conversation::where('application_id', $navApp->id)->pluck('id');
                    $navUnread = \App\Models\ConversationMessage::whereIn('conversation_id', $navConvIds)
                        ->where('sender_id', '!=', auth()->id())
                        ->whereNull('read_at')
                        ->count();
                }
            }
        @endphp

        <nav class="hidden sm:flex items-center gap-1 text-sm">
            @foreach([
                ['Tableau de bord', route('candidate.dashboard'),   'candidate.dashboard'],
                ['Ma candidature',  route('candidature.index'),     'candidature.*'],
                ['Documents',       route('participant.downloads'), 'participant.downloads'],
                ['Messages',        route('participant.messages'),  'participant.messages'],
                ['Mon profil',      route('participant.profile'),   'participant.profile'],
            ] as [$label, $href, $routeName])
            @php $navActive = request()->routeIs($routeName); @endphp
            <a href="{{ $href }}"
               class="relative px-3 py-1.5 rounded-lg font-medium transition-colors
                      {{ $navActive ? 'bg-noir-profond text-blanc-pur' : 'text-gris-500 hover:text-noir-profond' }}">
                {{ $label }}
                @if($label === 'Messages' && $navUnread > 0)
                <span class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-orange-ivoire text-[9px] font-bold text-blanc-pur">
                    {{ $navUnread > 9 ? '9+' : $navUnread }}
                </span>
                @endif
            </a>
            @endforeach
        </nav>

        <div class="flex items-center gap-3">
            <a href="{{ route('participant.profile') }}" class="flex items-center gap-2 group cursor-pointer">
                @if(auth()->user()?->photo_path)
                    <img src="{{ Storage::url(auth()->user()->photo_path) }}"
                         alt="Mon profil"
                         class="h-8 w-8 rounded-xl object-cover ring-2 ring-transparent group-hover:ring-orange-300 transition-all">
                @else
                    <div class="h-8 w-8 rounded-xl flex items-center justify-center text-xs font-bold text-blanc-pur transition-all group-hover:ring-2 group-hover:ring-orange-300"
                         style="background:hsl(var(--orange-ivoire));">
                        {{ mb_strtoupper(mb_substr(auth()->user()?->first_name ?? 'U', 0, 1) . mb_substr(auth()->user()?->last_name ?? '', 0, 1)) }}
                    </div>
                @endif
                <span class="hidden sm:block text-xs text-gris-500 truncate max-w-[120px] group-hover:text-noir-profond transition-colors">
                    {{ auth()->user()?->email }}
                </span>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-xs text-gris-500 hover:text-orange-ivoire transition-colors link-underline">
                    Déconnexion
                </button>
            </form>
        </div>
    </div>
</header>

<main class="max-w-6xl mx-auto px-6 py-10">
    {{ $slot }}
</main>

<footer class="border-t border-sable mt-16 py-6">
    <div class="max-w-6xl mx-auto px-6 flex flex-col sm:flex-row items-center justify-between gap-3">
        <p class="text-xs text-gris-500">
            © 2026 Hub Import-Export — Ministère du Commerce, de l'Industrie et de l'Artisanat
        </p>
        <a href="{{ route('home') }}" class="text-xs text-orange-ivoire link-underline">← Retour au site</a>
    </div>
</footer>

@livewireScripts
</body>
</html>
