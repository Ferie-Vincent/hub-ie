import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Filament/**/*.php',
        './vendor/filament/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                'noir-profond':  'hsl(var(--noir-profond) / <alpha-value>)',
                'noir-doux':     'hsl(var(--noir-doux) / <alpha-value>)',
                'blanc-pur':     'hsl(var(--blanc-pur) / <alpha-value>)',
                'blanc-creme':   'hsl(var(--blanc-creme) / <alpha-value>)',
                'orange-ivoire': 'hsl(var(--orange-ivoire) / <alpha-value>)',
                'orange-brule':  'hsl(var(--orange-brule) / <alpha-value>)',
                'orange-soft':   'hsl(var(--orange-soft) / <alpha-value>)',
                'orange-soft-bg':'hsl(var(--orange-soft-bg) / <alpha-value>)',
                'vert-ivoire':   'hsl(var(--vert-ivoire) / <alpha-value>)',
                'vert-fonce':    'hsl(var(--vert-fonce) / <alpha-value>)',
                'vert-soft':     'hsl(var(--vert-soft) / <alpha-value>)',
                'vert-soft-bg':  'hsl(var(--vert-soft-bg) / <alpha-value>)',
                'sable':         'hsl(var(--sable) / <alpha-value>)',
                'gris-500':      'hsl(var(--gris-500) / <alpha-value>)',
            },

            fontFamily: {
                sans:    ['Inter', ...defaultTheme.fontFamily.sans],
                serif:   ['Playfair Display', ...defaultTheme.fontFamily.serif],
                fraunces:['Fraunces', ...defaultTheme.fontFamily.serif],
                manrope: ['Manrope', ...defaultTheme.fontFamily.sans],
                mono:    ['JetBrains Mono', ...defaultTheme.fontFamily.mono],
            },

            maxWidth: {
                'hub': '1280px',
            },

            boxShadow: {
                'card': '0 4px 24px 0 rgba(0,0,0,0.06)',
            },

            keyframes: {
                // draw — SVG stroke-dashoffset (logo HIE)
                draw: {
                    from: { strokeDashoffset: '3000' },
                    to:   { strokeDashoffset: '0' },
                },
                // fade-up — scroll reveal
                'fade-up': {
                    from: { opacity: '0', transform: 'translateY(12px)' },
                    to:   { opacity: '1', transform: 'translateY(0)' },
                },
                // v10-fade-in — tab content change
                'v10-fade-in': {
                    from: { opacity: '0', transform: 'translateY(6px)' },
                    to:   { opacity: '1', transform: 'translateY(0)' },
                },
                // marquee — partenaires scroll
                marquee: {
                    from: { transform: 'translateX(0)' },
                    to:   { transform: 'translateX(-50%)' },
                },
                // pulse — dot countdown
                pulse: {
                    '0%, 100%': { opacity: '1' },
                    '50%':      { opacity: '0.5' },
                },
                // v10-pulse — halo orange
                'v10-pulse': {
                    '0%, 100%': { boxShadow: '0 0 0 0 rgba(232, 116, 28, 0)' },
                    '50%':      { boxShadow: '0 0 0 10px rgba(232, 116, 28, 0)' },
                    '25%':      { boxShadow: '0 0 0 6px rgba(232, 116, 28, 0.4)' },
                },
                // v10-green-pulse — halo vert
                'v10-green-pulse': {
                    '0%, 100%': { boxShadow: '0 0 0 0 rgba(0, 154, 68, 0)' },
                    '50%':      { boxShadow: '0 0 0 8px rgba(0, 154, 68, 0)' },
                    '25%':      { boxShadow: '0 0 0 5px rgba(0, 154, 68, 0.4)' },
                },
                // v10-accent-rule — ligne sous mot (via pseudo-élément CSS, animé en CSS pur)
                'v10-accent-rule': {
                    from: { transform: 'scaleX(0)', transformOrigin: 'left' },
                    to:   { transform: 'scaleX(1)', transformOrigin: 'left' },
                },
                // v10-float — flottement icône hover
                'v10-float': {
                    '0%, 100%': { transform: 'translateY(0) rotate(0deg)' },
                    '50%':      { transform: 'translateY(-6px) rotate(-2deg)' },
                },
                // v10-draw — redessinage SVG hover
                'v10-draw': {
                    from: { strokeDashoffset: '200' },
                    to:   { strokeDashoffset: '0' },
                },
                // hub-float-subtle — flottement ambiant des portraits/éléments décoratifs (§II.9)
                'hub-float-subtle': {
                    '0%, 100%': { transform: 'translateY(0px)' },
                    '50%':      { transform: 'translateY(-8px)' },
                },
                // v10-bar-in — barre verticale gauche (.v10-prog-row hover, §II.5.12)
                'v10-bar-in': {
                    from: { height: '0%', opacity: '0' },
                    to:   { height: '70%', opacity: '1' },
                },
                // v10-line-in — ligne horizontale bas (.v10-format-card hover, §II.5.11)
                'v10-line-in': {
                    from: { transform: 'scaleX(0)', transformOrigin: 'left' },
                    to:   { transform: 'scaleX(1)', transformOrigin: 'left' },
                },
            },

            animation: {
                'draw':             'draw 2.8s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                'fade-up':          'fade-up 0.8s cubic-bezier(0.16, 1, 0.3, 1) both',
                'v10-fade-in':      'v10-fade-in 0.35s ease-out both',
                'marquee':          'marquee 30s linear infinite',
                'pulse-dot':        'pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                'v10-pulse':        'v10-pulse 2s ease infinite',
                'v10-green-pulse':  'v10-green-pulse 2.4s ease infinite',
                'v10-accent-rule':  'v10-accent-rule 1.4s cubic-bezier(0.16, 1, 0.3, 1) 0.4s forwards',
                'v10-float':        'v10-float 3s ease-in-out infinite',
                'v10-draw':         'v10-draw 1.2s cubic-bezier(0.16, 1, 0.3, 1) both',
                'hub-float-subtle': 'hub-float-subtle 6s ease-in-out infinite',
                'v10-bar-in':       'v10-bar-in 0.4s cubic-bezier(0.16, 1, 0.3, 1) both',
                'v10-line-in':      'v10-line-in 0.6s cubic-bezier(0.16, 1, 0.3, 1) both',
            },
        },
    },

    safelist: [
        'kicker-sable',
        'kicker-violet',
        'prog-tag-sable',
        'prog-tag-violet',
        'prog-tag-blue',
    ],

    plugins: [forms],
};
