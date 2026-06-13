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
    $navLinks = [
        ['Tableau de bord', route('candidate.dashboard'),   'candidate.dashboard'],
        ['Documents',       route('participant.downloads'), 'participant.downloads'],
        ['Messages',        route('participant.messages'),  'participant.messages'],
        ['Mon profil',      route('participant.profile'),   'participant.profile'],
    ];
@endphp

<header x-data="{ open: false }" class="sticky top-0 z-40 glass-light shadow-sm border-b border-blanc-pur/60">
    <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between gap-4">

        <a href="{{ route('home') }}" class="flex items-center gap-2.5 flex-shrink-0">
            <img src="{{ asset('images/logo.svg') }}" alt="Hub Import-Export 2026" class="h-9 w-auto object-contain">
        </a>

        {{-- Nav desktop --}}
        <nav class="hidden sm:flex items-center gap-1 text-sm flex-1 justify-center">
            @foreach($navLinks as [$label, $href, $routeName])
            @php $navActive = request()->routeIs($routeName); @endphp
            <a href="{{ $href }}"
               class="relative px-3 py-1.5 rounded-lg font-medium transition-colors
                      {{ $navActive ? 'bg-noir-profond text-blanc-pur' : 'text-gris-500 hover:text-noir-profond' }}">
                {{ $label }}
                @if($label === 'Messages' && $navUnread > 0)
                <span class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-vert-ivoire text-[9px] font-bold text-blanc-pur">
                    {{ $navUnread > 9 ? '9+' : $navUnread }}
                </span>
                @endif
            </a>
            @endforeach
        </nav>

        <div class="flex items-center gap-3">
            {{-- Avatar + email desktop --}}
            <a href="{{ route('participant.profile') }}" class="flex items-center gap-2 group cursor-pointer">
                @if(auth()->user()?->photo_path)
                    <img src="{{ Storage::url(auth()->user()->photo_path) }}"
                         alt="Mon profil"
                         class="h-8 w-8 rounded-xl object-cover ring-2 ring-transparent group-hover:ring-orange-300 transition-all">
                @else
                    <div class="h-8 w-8 rounded-xl flex items-center justify-center text-xs font-bold text-blanc-pur transition-all group-hover:ring-2 group-hover:ring-orange-300"
                         style="background:hsl(var(--vert-ivoire));">
                        {{ mb_strtoupper(mb_substr(auth()->user()?->first_name ?? 'U', 0, 1) . mb_substr(auth()->user()?->last_name ?? '', 0, 1)) }}
                    </div>
                @endif
                <span class="hidden sm:block text-xs text-gris-500 truncate max-w-[120px] group-hover:text-noir-profond transition-colors">
                    {{ auth()->user()?->first_name }} {{ auth()->user()?->last_name }}
                </span>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">
                @csrf
                <button type="submit" class="text-xs text-gris-500 hover:text-vert-ivoire transition-colors link-underline">
                    Déconnexion
                </button>
            </form>

            {{-- Burger mobile --}}
            <button @click="open = !open"
                    class="sm:hidden relative flex items-center justify-center h-9 w-9 rounded-lg text-gris-500 hover:text-noir-profond hover:bg-sable/50 transition-colors"
                    :aria-expanded="open"
                    aria-label="Menu navigation">
                @if($navUnread > 0)
                <span class="absolute top-1 right-1 flex h-2 w-2 rounded-full bg-vert-ivoire"></span>
                @endif
                <svg x-show="!open" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="open" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Drawer mobile --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         @click.away="open = false"
         class="sm:hidden border-t border-blanc-pur/60 bg-blanc-creme/95 backdrop-blur-sm"
         style="display:none">

        {{-- Identité --}}
        <div class="px-6 py-4 border-b border-sable/50 flex items-center gap-3">
            @if(auth()->user()?->photo_path)
                <img src="{{ Storage::url(auth()->user()->photo_path) }}"
                     alt="Mon profil"
                     class="h-10 w-10 rounded-xl object-cover flex-shrink-0">
            @else
                <div class="h-10 w-10 rounded-xl flex-shrink-0 flex items-center justify-center text-sm font-bold text-blanc-pur"
                     style="background:hsl(var(--vert-ivoire));">
                    {{ mb_strtoupper(mb_substr(auth()->user()?->first_name ?? 'U', 0, 1) . mb_substr(auth()->user()?->last_name ?? '', 0, 1)) }}
                </div>
            @endif
            <div class="min-w-0">
                <p class="text-sm font-semibold text-noir-profond truncate">
                    {{ auth()->user()?->first_name }} {{ auth()->user()?->last_name }}
                </p>
                <p class="text-xs text-gris-500 truncate">{{ auth()->user()?->email }}</p>
            </div>
        </div>

        {{-- Liens --}}
        <nav class="px-4 py-3 flex flex-col gap-1">
            @foreach($navLinks as [$label, $href, $routeName])
            @php $navActive = request()->routeIs($routeName); @endphp
            <a href="{{ $href }}"
               @click="open = false"
               class="relative flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-colors
                      {{ $navActive ? 'bg-noir-profond text-blanc-pur' : 'text-gris-500 hover:text-noir-profond hover:bg-sable/50' }}">
                {{ $label }}
                @if($label === 'Messages' && $navUnread > 0)
                <span class="ml-auto flex h-5 w-5 items-center justify-center rounded-full bg-vert-ivoire text-[9px] font-bold text-blanc-pur">
                    {{ $navUnread > 9 ? '9+' : $navUnread }}
                </span>
                @endif
            </a>
            @endforeach
        </nav>

        {{-- Déconnexion --}}
        <div class="px-6 py-4 border-t border-sable/50">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full text-center text-sm text-gris-500 hover:text-vert-ivoire transition-colors py-2">
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
        <a href="{{ route('home') }}" class="text-xs text-vert-ivoire link-underline">← Retour au site</a>
    </div>
</footer>

@livewireScripts
</body>
</html>
