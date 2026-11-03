document.addEventListener('click', (event) => {
    if (!(event.target instanceof Element)) {
        return;
    }

    const toggleButton = event.target.closest('[data-password-toggle]');

    if (!(toggleButton instanceof HTMLButtonElement)) {
        return;
    }

    const input = document.getElementById(toggleButton.dataset.passwordToggle);

    if (!(input instanceof HTMLInputElement)) {
        return;
    }

    const shouldShowPassword = input.type === 'password';
    input.type = shouldShowPassword ? 'text' : 'password';
    toggleButton.setAttribute('aria-label', shouldShowPassword ? 'Sembunyikan password' : 'Tampilkan password');
    toggleButton.setAttribute('aria-pressed', String(shouldShowPassword));
    toggleButton.querySelector('[data-password-eye]')?.classList.toggle('hidden', ! shouldShowPassword);
    toggleButton.querySelector('[data-password-eye-off]')?.classList.toggle('hidden', shouldShowPassword);
});

const replaceHomeSchedule = async (url, container, updateHistory = true) => {
    const response = await fetch(url, {
        headers: {
            Accept: 'text/html',
            'X-Requested-With': 'XMLHttpRequest',
        },
    });

    if (! response.ok) {
        throw new Error(`Gagal memuat jadwal (${response.status})`);
    }

    container.innerHTML = await response.text();

    if (updateHistory) {
        window.history.pushState({ homeSchedule: true }, '', url);
    }
};

document.addEventListener('click', async (event) => {
    if (!(event.target instanceof Element)) {
        return;
    }

    const paginationLink = event.target.closest('[data-home-schedule-pagination] a');
    const scheduleContainer = event.target.closest('[data-home-schedule]');

    if (!(paginationLink instanceof HTMLAnchorElement) || !(scheduleContainer instanceof HTMLElement)) {
        return;
    }

    event.preventDefault();
    scheduleContainer.classList.add('pointer-events-none', 'opacity-60');

    try {
        await replaceHomeSchedule(paginationLink.href, scheduleContainer);
    } catch {
        window.location.assign(paginationLink.href);
    } finally {
        scheduleContainer.classList.remove('pointer-events-none', 'opacity-60');
    }
});

window.addEventListener('popstate', async () => {
    const scheduleContainer = document.querySelector('[data-home-schedule]');

    if (!(scheduleContainer instanceof HTMLElement) || window.location.pathname !== '/') {
        return;
    }

    scheduleContainer.classList.add('pointer-events-none', 'opacity-60');

    try {
        await replaceHomeSchedule(window.location.href, scheduleContainer, false);
    } catch {
        window.location.reload();
    } finally {
        scheduleContainer.classList.remove('pointer-events-none', 'opacity-60');
    }
});
