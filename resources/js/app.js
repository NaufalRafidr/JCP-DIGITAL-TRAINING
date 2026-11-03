document.addEventListener('click', (event) => {
    if (!(event.target instanceof Element)) {
        return;
    }

    const scheduleLink = event.target.closest('[data-home-schedule] a[rel="prev"], [data-home-schedule] a[rel="next"]');

    if (scheduleLink instanceof HTMLAnchorElement) {
        event.preventDefault();

        const pageUrl = new URL(scheduleLink.href, window.location.href);
        const scheduleContainer = scheduleLink.closest('[data-home-schedule]');

        if (!(scheduleContainer instanceof HTMLElement)) {
            return;
        }

        scheduleContainer.querySelector('[data-home-schedule-error]')?.remove();
        scheduleContainer.setAttribute('aria-busy', 'true');

        fetch(pageUrl, {
            headers: {
                Accept: 'text/html',
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error('Gagal memuat halaman jadwal.');
                }

                return response.text();
            })
            .then((html) => {
                const documentParser = new DOMParser();
                const parsedDocument = documentParser.parseFromString(html, 'text/html');
                const nextSchedule = parsedDocument.querySelector('[data-home-schedule]');

                if (!(nextSchedule instanceof HTMLElement)) {
                    throw new Error('Bagian jadwal tidak ditemukan.');
                }

                scheduleContainer.replaceWith(nextSchedule);
                window.history.pushState({}, '', pageUrl);
            })
            .catch(() => {
                scheduleContainer.removeAttribute('aria-busy');

                const errorMessage = document.createElement('p');
                errorMessage.dataset.homeScheduleError = 'true';
                errorMessage.className = 'mt-4 text-center text-sm font-semibold text-rose-600';
                errorMessage.textContent = 'Jadwal gagal dimuat. Silakan coba lagi.';
                scheduleContainer.append(errorMessage);
            });

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
