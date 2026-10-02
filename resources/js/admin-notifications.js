/**
 * Admin Real-Time Order Notifications
 * Uses Laravel Echo + Laravel Reverb (WebSocket)
 *
 * Listens on the private channel "admin.orders" for the
 * "order.placed" event and renders a toast notification.
 */

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// ─── Echo / Reverb Setup ────────────────────────────────────────────────────

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST ?? window.location.hostname,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'http') === 'https',
    enabledTransports: ['ws', 'wss'],
    // Reconnect automatically on disconnect
    disableStats: true,
});

// ─── Duplicate-prevention ───────────────────────────────────────────────────

const seenOrderIds = new Set();

// ─── Channel Subscription ───────────────────────────────────────────────────

window.Echo
    .private('admin.orders')
    .listen('.order.placed', (data) => {
        // Guard against duplicate events (e.g. multiple queue workers)
        if (seenOrderIds.has(data.id)) {
            console.warn('[AdminNotifications] Duplicate event ignored:', data.order_number);
            return;
        }
        seenOrderIds.add(data.id);

        console.info('[AdminNotifications] New order received:', data);

        showOrderToast(data);
        updatePendingBadge();
        playNotificationSound();
    });

// ─── Toast Notification ─────────────────────────────────────────────────────

function showOrderToast(order) {
    const container = getOrCreateToastContainer();

    const toast = document.createElement('div');
    toast.className = 'rt-toast';
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.innerHTML = `
        <div class="rt-toast-icon">📦</div>
        <div class="rt-toast-body">
            <div class="rt-toast-title">New Order Received</div>
            <div class="rt-toast-order">${escapeHtml(order.order_number)}</div>
            <div class="rt-toast-meta">
                <span>${escapeHtml(order.customer)}</span>
                <span class="rt-toast-amount">${formatCurrency(order.total)}</span>
            </div>
        </div>
        <a href="${escapeHtml(order.url)}" class="rt-toast-btn">View</a>
        <button class="rt-toast-close" aria-label="Dismiss">&times;</button>
    `;

    // Dismiss on close button
    toast.querySelector('.rt-toast-close').addEventListener('click', () => dismissToast(toast));

    container.appendChild(toast);

    // Trigger entrance animation on next frame
    requestAnimationFrame(() => toast.classList.add('rt-toast--visible'));

    // Auto-dismiss after 8 seconds
    setTimeout(() => dismissToast(toast), 8000);
}

function dismissToast(toast) {
    toast.classList.remove('rt-toast--visible');
    toast.classList.add('rt-toast--hiding');
    toast.addEventListener('transitionend', () => toast.remove(), { once: true });
}

function getOrCreateToastContainer() {
    let container = document.getElementById('rt-toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'rt-toast-container';
        container.setAttribute('aria-label', 'Notifications');
        document.body.appendChild(container);
    }
    return container;
}

// ─── Pending Badge Update ───────────────────────────────────────────────────

function updatePendingBadge() {
    // Increment the sidebar "Orders" badge count in real time
    const badge = document.querySelector('.admin-sidebar .badge-count');
    if (badge) {
        const current = parseInt(badge.textContent, 10) || 0;
        badge.textContent = current + 1;
        badge.style.display = '';
    } else {
        // Badge doesn't exist yet — create it next to the Orders nav link
        const ordersLink = document.querySelector('a[href*="/admin/orders"]');
        if (ordersLink) {
            const newBadge = document.createElement('span');
            newBadge.className = 'badge-count';
            newBadge.textContent = '1';
            ordersLink.appendChild(newBadge);
        }
    }
}

// ─── Notification Sound ─────────────────────────────────────────────────────

function playNotificationSound() {
    try {
        // Short 440 Hz beep via Web Audio API — no external file needed
        const ctx = new (window.AudioContext || window.webkitAudioContext)();
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();

        osc.connect(gain);
        gain.connect(ctx.destination);

        osc.type = 'sine';
        osc.frequency.setValueAtTime(880, ctx.currentTime);
        gain.gain.setValueAtTime(0.15, ctx.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.4);

        osc.start(ctx.currentTime);
        osc.stop(ctx.currentTime + 0.4);
    } catch (_) {
        // Audio not available — silently ignore
    }
}

// ─── Helpers ────────────────────────────────────────────────────────────────

function escapeHtml(str) {
    const div = document.createElement('div');
    div.appendChild(document.createTextNode(String(str ?? '')));
    return div.innerHTML;
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('en-EG', {
        style: 'currency',
        currency: 'EGP',
        minimumFractionDigits: 0,
    }).format(amount);
}
