<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' — ' : '' }}Hub Import-Export 2026</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Fraunces:ital,opsz,wght@0,9..144,700;1,9..144,700&family=Manrope:wght@400;500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased" style="background-color:hsl(var(--noir-profond));color:hsl(var(--blanc-casse));font-family:'Manrope',sans-serif;">

<div class="min-h-screen flex flex-col items-center justify-center px-4 py-12"
     style="background:radial-gradient(ellipse 80% 60% at 50% 0%,hsl(220 40% 10%/0.8) 0%,hsl(var(--noir-profond)) 70%);">

    {{-- Marque --}}
    <div class="mb-8 text-center">
        <a href="{{ url('/') }}" class="inline-block">
            <span class="block text-xs font-medium tracking-[0.2em] uppercase mb-1"
                  style="color:hsl(var(--orange-ivoire));">
                Ministère du Commerce · Côte d'Ivoire
            </span>
            <span class="block text-2xl font-bold"
                  style="font-family:'Fraunces',serif;color:hsl(var(--blanc-casse));">
                Hub Import-Export&nbsp;<em style="color:hsl(var(--or-clair));">2026</em>
            </span>
            <span class="block mx-auto mt-2 h-px w-16"
                  style="background:hsl(var(--orange-ivoire));"></span>
        </a>
    </div>

    {{-- Carte formulaire --}}
    <div class="w-full max-w-md rounded-2xl p-8 shadow-2xl"
         style="background:hsl(var(--gris-acier)/0.9);border:1px solid hsl(var(--gris-medium)/0.6);backdrop-filter:blur(16px);">
        {{ $slot }}
    </div>

    {{-- Retour accueil --}}
    <p class="mt-6 text-sm" style="color:hsl(var(--blanc-casse)/0.5);">
        <a href="{{ url('/') }}"
           style="color:hsl(var(--blanc-casse)/0.6);"
           class="hover:underline transition-colors">
            ← Retour à l'accueil
        </a>
    </p>

</div>
</body>
</html>
