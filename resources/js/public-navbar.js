// Mengatur dropdown navigasi publik pada layar mobile dan tablet.
document.addEventListener('DOMContentLoaded', () => {
    const navbar = document.getElementById('navbar');
    const toggle = document.getElementById('mobileNavToggle');
    const menu = document.getElementById('mobileNavMenu');

    if (!navbar || !toggle || !menu) return;

    const openIcon = toggle.querySelector('[data-mobile-nav-open]');
    const closeIcon = toggle.querySelector('[data-mobile-nav-close]');

    // Menyamakan status visual dan atribut aksesibilitas menu mobile.
    function setMenuOpen(isOpen) {
        menu.classList.toggle('hidden', !isOpen);
        menu.setAttribute('aria-hidden', String(!isOpen));
        toggle.setAttribute('aria-expanded', String(isOpen));
        toggle.setAttribute('aria-label', isOpen ? 'Tutup menu navigasi' : 'Buka menu navigasi');
        openIcon?.classList.toggle('hidden', isOpen);
        closeIcon?.classList.toggle('hidden', !isOpen);
    }

    toggle.addEventListener('click', () => {
        setMenuOpen(toggle.getAttribute('aria-expanded') !== 'true');
    });

    menu.addEventListener('click', (event) => {
        if (event.target.closest('a')) setMenuOpen(false);
    });

    document.addEventListener('click', (event) => {
        if (!navbar.contains(event.target)) setMenuOpen(false);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') setMenuOpen(false);
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) setMenuOpen(false);
    });
});
