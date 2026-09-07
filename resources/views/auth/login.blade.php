<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Espace équipe — {{ config('salon.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans">
<div class="grid min-h-screen lg:grid-cols-2">
    <div class="relative hidden overflow-hidden bg-espresso-900 lg:block">
        <div class="texture-gradient absolute inset-0 opacity-70"></div>
        <div class="pattern-dots absolute inset-0 opacity-30"></div>
        <div class="relative flex h-full flex-col justify-between p-14 text-cream-100">
            <span class="font-display text-2xl font-semibold text-gold-300">{{ config('salon.name') }}</span>
            <div>
                <h1 class="font-display text-5xl font-semibold leading-tight">
                    Le tableau de bord<br><span class="italic text-gold-300">de votre salon</span>
                </h1>
                <p class="mt-5 max-w-md text-cream-100/70">
                    Agenda du jour, de la semaine et du mois, commandes boutique, chiffre d'affaires
                    et rapports exportables.
                </p>
            </div>
            <span class="text-xs uppercase tracking-[0.24em] text-cream-100/40">{{ config('salon.address') }}</span>
        </div>
    </div>

    <div class="flex items-center justify-center px-6 py-16">
        <div class="w-full max-w-sm">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-xs uppercase tracking-[0.2em] text-espresso-700/55 hover:text-gold-600">
                <x-icon name="chevron-left" class="h-4 w-4" /> Retour au site
            </a>

            <h2 class="mt-8 font-display text-4xl font-semibold text-espresso-900">Connexion</h2>
            <p class="mt-2 text-sm text-espresso-700/70">Espace réservé à l'équipe du salon.</p>

            @if ($errors->any())
                <div class="mt-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
                @csrf
                <div>
                    <label class="label" for="email">Adresse e-mail</label>
                    <input type="email" name="email" id="email" class="field" required autofocus value="{{ old('email') }}">
                </div>
                <div>
                    <label class="label" for="password">Mot de passe</label>
                    <input type="password" name="password" id="password" class="field" required>
                </div>
                <label class="flex items-center gap-2 text-sm text-espresso-700/75">
                    <input type="checkbox" name="remember" class="rounded accent-[#c19a3c]"> Rester connectée
                </label>
                <button class="btn-gold w-full py-4">Se connecter</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
