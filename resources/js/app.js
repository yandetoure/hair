// Menu mobile
document.addEventListener('click', (event) => {
    const toggle = event.target.closest('[data-menu-toggle]');

    if (toggle) {
        const menu = document.querySelector('[data-menu]');

        if (menu) {
            menu.classList.toggle('hidden');

            // Les menus en colonne (sidebar admin) doivent repasser en flex à l'ouverture
            if (menu.dataset.menuDisplay) {
                menu.classList.toggle(menu.dataset.menuDisplay);
            }
        }
    }
});

// Apparition douce des sections au scroll
const observer = new IntersectionObserver(
    (entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-up');
                observer.unobserve(entry.target);
            }
        });
    },
    { threshold: 0.12 }
);

document.querySelectorAll('[data-reveal]').forEach((el) => observer.observe(el));
