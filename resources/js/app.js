import './bootstrap';
import Alpine from 'alpinejs';
import Swup from 'swup';
import SwupScriptsPlugin from '@swup/scripts-plugin';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

window.Alpine = Alpine;
window.gsap = gsap;
window.ScrollTrigger = ScrollTrigger;

/* ─── Flash Sale Timer ─── */
Alpine.data('flashTimer', (endDate) => ({
    parts: [
        { label: 'H', value: '00' },
        { label: 'M', value: '00' },
        { label: 'S', value: '00' },
    ],
    start() {
        const tick = () => {
            const diff = new Date(endDate) - new Date();
            if (diff <= 0) {
                this.parts = [
                    { label: 'H', value: '00' },
                    { label: 'M', value: '00' },
                    { label: 'S', value: '00' },
                ];
                return;
            }

            this.parts = [
                { label: 'H', value: String(Math.floor(diff / 3600000)).padStart(2, '0') },
                { label: 'M', value: String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0') },
                { label: 'S', value: String(Math.floor((diff % 60000) / 1000)).padStart(2, '0') },
            ];
        };

        tick();
        setInterval(tick, 1000);
    },
}));

/* ─── Theme ─── */
const theme = document.body?.dataset.theme || localStorage.getItem('theme') || 'light';
document.documentElement.classList.toggle('dark', theme === 'dark');
localStorage.setItem('theme', theme);

/* ─── Scroll Reveal (Intersection Observer) ─── */
document.addEventListener('DOMContentLoaded', () => {
    const reveals = document.querySelectorAll('.reveal');
    if (reveals.length === 0) return;

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.08, rootMargin: '0px 0px -40px 0px' }
    );

    reveals.forEach((el) => observer.observe(el));
});

/* ─── Navbar Scroll Effect ─── */
document.addEventListener('DOMContentLoaded', () => {
    const navbar = document.getElementById('main-navbar');
    if (!navbar) return;

    let lastScroll = 0;
    const onScroll = () => {
        const y = window.scrollY;
        navbar.classList.toggle('shadow-elevated', y > 20);
        navbar.classList.toggle('border-transparent', y <= 20);
        lastScroll = y;
    };

    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
});

Alpine.start();

/* ─── Swup Initialization ─── */
const swup = new Swup({
    containers: ['#swup'],
    animationSelector: '[class*="transition-"]',
    linkSelector: 'a[href]:not([data-no-swup]):not([target="_blank"]):not([href^="#"]):not([href^="mailto:"]):not([href^="tel:"])',
    plugins: [new SwupScriptsPlugin({
        head: true,
        body: true
    })]
});

// Refresh plugins on page turn
swup.hooks.on('page:view', () => {
    if (window.ScrollTrigger) {
        window.ScrollTrigger.refresh();
    }
});
