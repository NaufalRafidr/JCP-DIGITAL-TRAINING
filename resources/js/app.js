const updateHomeSchedulePage = (scheduleContainer, requestedPage) => {
    const rows = Array.from(scheduleContainer.querySelectorAll('[data-schedule-row]'));
    const pageSize = Number(scheduleContainer.dataset.schedulePageSize) || 10;
    const totalPages = Math.max(Math.ceil(rows.length / pageSize), 1);
    const currentPage = Math.min(Math.max(requestedPage, 1), totalPages);
    const firstRow = (currentPage - 1) * pageSize;

    rows.forEach((row, index) => {
        row.hidden = index < firstRow || index >= firstRow + pageSize;
    });

    const previousButton = scheduleContainer.querySelector('[data-schedule-previous]');
    const nextButton = scheduleContainer.querySelector('[data-schedule-next]');
    const pageStatus = scheduleContainer.querySelector('[data-schedule-page-status]');

    if (previousButton instanceof HTMLButtonElement) {
        previousButton.disabled = currentPage === 1;
        previousButton.dataset.schedulePage = String(currentPage - 1);
    }

    if (nextButton instanceof HTMLButtonElement) {
        nextButton.disabled = currentPage === totalPages;
        nextButton.dataset.schedulePage = String(currentPage + 1);
    }

    if (pageStatus instanceof HTMLElement) {
        pageStatus.textContent = `Halaman ${currentPage} dari ${totalPages}`;
    }
};

const initializeHomeSchedulePagination = () => {
    document.querySelectorAll('[data-home-schedule]').forEach((scheduleContainer) => {
        if (!(scheduleContainer instanceof HTMLElement)) {
            return;
        }

        updateHomeSchedulePage(scheduleContainer, 1);
    });
};

document.addEventListener('click', (event) => {
    if (!(event.target instanceof Element)) {
        return;
    }

    const scheduleButton = event.target.closest('[data-schedule-previous], [data-schedule-next]');

    if (scheduleButton instanceof HTMLButtonElement) {
        const scheduleContainer = scheduleButton.closest('[data-home-schedule]');
        const requestedPage = Number(scheduleButton.dataset.schedulePage);

        if (scheduleContainer instanceof HTMLElement && Number.isInteger(requestedPage)) {
            updateHomeSchedulePage(scheduleContainer, requestedPage);
        }

        return;
    }
});

initializeHomeSchedulePagination();

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
