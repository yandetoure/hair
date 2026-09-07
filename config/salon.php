<?php

return [
    // Identité du salon (modifiable sans toucher au code)
    'name' => 'Maison Éclat',
    'tagline' => 'Salon de coiffure, beauté & soins',
    'baseline' => "L'art de sublimer votre beauté naturelle",
    'phone' => '+225 07 00 00 00 00',
    'whatsapp' => '+225 07 00 00 00 00',
    'email' => 'contact@maison-eclat.com',
    'address' => 'Cocody Angré, 7e Tranche — Abidjan',
    'instagram' => 'https://instagram.com',
    'facebook' => 'https://facebook.com',

    // Monnaie
    'currency' => 'FCFA',

    // Horaires d'ouverture : 1 = lundi ... 7 = dimanche. null = fermé
    'hours' => [
        1 => ['09:00', '19:00'],
        2 => ['09:00', '19:00'],
        3 => ['09:00', '19:00'],
        4 => ['09:00', '19:00'],
        5 => ['09:00', '20:00'],
        6 => ['08:00', '20:00'],
        7 => null,
    ],

    // Moteur de réservation
    'slot_interval' => 30,      // pas des créneaux, en minutes
    'min_notice_hours' => 2,    // délai minimum avant un RDV
    'max_days_ahead' => 60,     // horizon de réservation
    'capacity' => 3,            // nombre de postes / RDV simultanés

    // Boutique
    'delivery_fee' => 2000,
    'free_delivery_from' => 50000,
];
