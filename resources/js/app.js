import './bootstrap';
import './analytics';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    // Header scroll shadow effect
    const header = document.getElementById('site-header');
    if (header) {
        const updateHeaderScroll = () => {
            if (window.scrollY > 10) {
                header.classList.add('bg-white', 'border-b', 'border-slate-200', 'shadow-sm');
                header.classList.remove('bg-white/95', 'backdrop-blur-sm');
            } else {
                header.classList.remove('bg-white', 'border-b', 'border-slate-200', 'shadow-sm');
                header.classList.add('bg-white/95', 'backdrop-blur-sm');
            }
        };

        window.addEventListener('scroll', updateHeaderScroll, { passive: true });
        updateHeaderScroll();
    }

    // Accessible Mobile Navigation Menu
    const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
    const mobileMenuDrawer = document.getElementById('mobile-menu-drawer');

    if (mobileMenuToggle && mobileMenuDrawer) {
        const openIcon = document.getElementById('mobile-menu-open-icon');
        const closeIcon = document.getElementById('mobile-menu-close-icon');

        const toggleMenu = (open) => {
            const isCurrentlyExpanded = mobileMenuToggle.getAttribute('aria-expanded') === 'true';
            const shouldOpen = open !== undefined ? open : !isCurrentlyExpanded;

            mobileMenuToggle.setAttribute('aria-expanded', shouldOpen ? 'true' : 'false');

            if (shouldOpen) {
                mobileMenuDrawer.classList.remove('hidden');
                if (openIcon) openIcon.classList.add('hidden');
                if (closeIcon) closeIcon.classList.remove('hidden');
            } else {
                mobileMenuDrawer.classList.add('hidden');
                if (openIcon) openIcon.classList.remove('hidden');
                if (closeIcon) closeIcon.classList.add('hidden');
            }
        };

        mobileMenuToggle.addEventListener('click', () => toggleMenu());

        // Close on ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && mobileMenuToggle.getAttribute('aria-expanded') === 'true') {
                toggleMenu(false);
                mobileMenuToggle.focus();
            }
        });

        // Close when clicking outside drawer
        document.addEventListener('click', (e) => {
            if (
                mobileMenuToggle.getAttribute('aria-expanded') === 'true' &&
                !mobileMenuDrawer.contains(e.target) &&
                !mobileMenuToggle.contains(e.target)
            ) {
                toggleMenu(false);
            }
        });
    }
});
