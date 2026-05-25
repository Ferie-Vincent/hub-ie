const TARGET = new Date('2026-06-22T09:00:00+00:00');
const TZ_OFFSET = 0; // Africa/Abidjan is UTC+0

function updateCountdown() {
    const now = new Date();
    const diff = TARGET - now;

    if (diff <= 0) {
        ['cd-days', 'cd-hours', 'cd-minutes', 'cd-seconds'].forEach((id) => {
            const el = document.getElementById(id);
            if (el) el.textContent = '00';
        });
        return;
    }

    const days    = Math.floor(diff / (1000 * 60 * 60 * 24));
    const hours   = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((diff % (1000 * 60)) / 1000);

    const pad = (n) => String(n).padStart(2, '0');

    const setEl = (id, val) => {
        const el = document.getElementById(id);
        if (el) el.textContent = pad(val);
    };

    setEl('cd-days',    days);
    setEl('cd-hours',   hours);
    setEl('cd-minutes', minutes);
    setEl('cd-seconds', seconds);
}

updateCountdown();
setInterval(updateCountdown, 1000);
