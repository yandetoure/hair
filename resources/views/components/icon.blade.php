@props(['name' => 'sparkles'])

@php
    $paths = [
        'menu' => '<path d="M4 7h16M4 12h16M4 17h16"/>',
        'x' => '<path d="M6 6l12 12M18 6L6 18"/>',
        'bag' => '<path d="M6 7h12l1 13H5L6 7Z"/><path d="M9 7V5.5a3 3 0 0 1 6 0V7"/>',
        'check' => '<path d="M5 13l4 4L19 7"/>',
        'arrow-right' => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'plus' => '<path d="M12 5v14M5 12h14"/>',
        'minus' => '<path d="M5 12h14"/>',
        'trash' => '<path d="M4 7h16M9 7V5h6v2M6 7l1 13h10l1-13"/>',
        'phone' => '<path d="M4 5c0 8 7 15 15 15l1.5-3.5-4-2-2 2c-2-1-4-3-5-5l2-2-2-4L6 4C4.9 4 4 4.9 4 5Z"/>',
        'mail' => '<path d="M3 6h18v12H3z"/><path d="m3 7 9 6 9-6"/>',
        'map-pin' => '<path d="M12 21s7-5.5 7-11a7 7 0 1 0-14 0c0 5.5 7 11 7 11Z"/><circle cx="12" cy="10" r="2.5"/>',
        'clock' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        'calendar' => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M8 3v4M16 3v4M3 10h18"/>',
        'star' => '<path d="m12 4 2.4 5 5.6.8-4 3.9 1 5.5-5-2.7-5 2.7 1-5.5-4-3.9 5.6-.8L12 4Z"/>',
        'sparkles' => '<path d="m12 4 1.6 4.4L18 10l-4.4 1.6L12 16l-1.6-4.4L6 10l4.4-1.6L12 4Z"/><path d="M18 15l.8 2.2L21 18l-2.2.8L18 21l-.8-2.2L15 18l2.2-.8L18 15Z"/>',
        'ciseaux' => '<circle cx="6" cy="6" r="2.5"/><circle cx="6" cy="18" r="2.5"/><path d="M8 7.5 20 18M8 16.5 20 6"/>',
        'tresses' => '<path d="M9 3c0 3 6 3 6 6s-6 3-6 6 6 3 6 6"/><path d="M15 3c0 3-6 3-6 6s6 3 6 6-6 3-6 6"/>',
        'soins' => '<path d="M12 21c4.5-2 7-5.5 7-10V6l-7-3-7 3v5c0 4.5 2.5 8 7 10Z"/><path d="M9.5 12.5 11 14l3.5-3.5"/>',
        'ongles' => '<rect x="8" y="3" width="8" height="14" rx="4"/><path d="M8 17c0 2 1.8 4 4 4s4-2 4-4"/>',
        'visage' => '<circle cx="12" cy="12" r="9"/><path d="M9 10h.01M15 10h.01M9 15c1.8 1.4 4.2 1.4 6 0"/>',
        'regard' => '<path d="M2 12s3.6-6 10-6 10 6 10 6-3.6 6-10 6-10-6-10-6Z"/><circle cx="12" cy="12" r="2.8"/>',
        'spa' => '<path d="M12 21c0-5 3-9 8-10-1 5-4 8-8 10Z"/><path d="M12 21c0-5-3-9-8-10 1 5 4 8 8 10Z"/><path d="M12 21c-1.5-4 0-8 3-11"/>',
        'instagram' => '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><path d="M17 7h.01"/>',
        'facebook' => '<path d="M14 8h3V4h-3a4 4 0 0 0-4 4v2H7v4h3v6h4v-6h3l1-4h-4V8.8c0-.5.4-.8 1-.8Z"/>',
        'whatsapp' => '<path d="M4 20l1.3-4A8 8 0 1 1 8 18.7L4 20Z"/><path d="M9 9.5c0 3 2.5 5.5 5.5 5.5l1-1.6-2-1-.8.9c-1-.5-1.9-1.4-2.4-2.4l.9-.8-1-2L9 9.5Z"/>',
        'dashboard' => '<rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="10" width="7" height="11" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/>',
        'chart' => '<path d="M4 20h16"/><path d="M7 20v-6M12 20V6M17 20v-9"/>',
        'users' => '<circle cx="9" cy="8" r="3.2"/><path d="M3 20c0-3.3 2.7-5.5 6-5.5s6 2.2 6 5.5"/><path d="M16 5.5a3.2 3.2 0 0 1 0 6.4M17 14.5c2.4.5 4 2.5 4 5.5"/>',
        'box' => '<path d="m12 3 8 4v10l-8 4-8-4V7l8-4Z"/><path d="m4 7 8 4 8-4M12 11v10"/>',
        'cog' => '<circle cx="12" cy="12" r="3"/><path d="M12 3v2M12 19v2M5.6 5.6l1.4 1.4M17 17l1.4 1.4M3 12h2M19 12h2M5.6 18.4 7 17M17 7l1.4-1.4"/>',
        'logout' => '<path d="M15 12H4M8 8l-4 4 4 4"/><path d="M12 4h6a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-6"/>',
        'download' => '<path d="M12 4v10M8 11l4 4 4-4"/><path d="M4 19h16"/>',
        'printer' => '<path d="M7 8V4h10v4"/><rect x="4" y="8" width="16" height="7" rx="2"/><path d="M7 15h10v5H7z"/>',
        'search' => '<circle cx="11" cy="11" r="6"/><path d="m16 16 4 4"/>',
        'edit' => '<path d="M4 20h4l10-10-4-4L4 16v4Z"/><path d="m14 6 4 4"/>',
        'chevron-left' => '<path d="m14 6-6 6 6 6"/>',
        'chevron-right' => '<path d="m10 6 6 6-6 6"/>',
        'wallet' => '<rect x="3" y="6" width="18" height="13" rx="2.5"/><path d="M3 10h18M16 14h2"/>',
    ];
@endphp

<svg {{ $attributes->merge(['class' => 'h-5 w-5']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor"
     stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    {!! $paths[$name] ?? $paths['sparkles'] !!}
</svg>
