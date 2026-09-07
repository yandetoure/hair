<?php

use Illuminate\Support\Carbon;

if (! function_exists('fcfa')) {
    /** Formate un montant entier dans la devise du salon : 25 000 FCFA */
    function fcfa(int|float|null $amount): string
    {
        return number_format((float) $amount, 0, ',', ' ').' '.config('salon.currency');
    }
}

if (! function_exists('fr_date')) {
    /** Date lisible en français : « lundi 7 septembre 2026 » */
    function fr_date(Carbon $date, string $format = 'EEEE d MMMM y'): string
    {
        return $date->locale('fr')->isoFormat(match ($format) {
            'EEEE d MMMM y' => 'dddd D MMMM YYYY',
            'd MMM y' => 'D MMM YYYY',
            'MMMM y' => 'MMMM YYYY',
            default => $format,
        });
    }
}
