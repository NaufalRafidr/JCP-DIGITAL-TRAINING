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

const initializeTravelPackageCarousels = () => {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

    document.querySelectorAll('[data-travel-package-carousel]').forEach((carousel) => {
        if (!(carousel instanceof HTMLElement)) {
            return;
        }

        const slides = Array.from(carousel.querySelectorAll('[data-travel-package-slide]'));
        const dots = Array.from(carousel.querySelectorAll('[data-travel-package-dot]'));

        if (slides.length < 2) {
            return;
        }

        const interval = Math.max(Number(carousel.dataset.carouselInterval) || 2500, 2000);
        let activeIndex = 0;
        let intervalId = null;

        const showSlide = (requestedIndex) => {
            activeIndex = (requestedIndex + slides.length) % slides.length;

            slides.forEach((slide, index) => {
                const isActive = index === activeIndex;

                slide.classList.toggle('opacity-100', isActive);
                slide.classList.toggle('opacity-0', ! isActive);
                slide.setAttribute('aria-hidden', String(! isActive));
            });

            dots.forEach((dot, index) => {
                const isActive = index === activeIndex;

                dot.classList.toggle('w-7', isActive);
                dot.classList.toggle('w-2.5', ! isActive);
                dot.classList.toggle('opacity-100', isActive);
                dot.classList.toggle('opacity-55', ! isActive);
                dot.setAttribute('aria-current', String(isActive));
            });
        };

        const stopCarousel = () => {
            if (intervalId !== null) {
                window.clearInterval(intervalId);
                intervalId = null;
            }
        };

        const startCarousel = () => {
            stopCarousel();

            if (prefersReducedMotion.matches || document.hidden) {
                return;
            }

            intervalId = window.setInterval(() => showSlide(activeIndex + 1), interval);
        };

        dots.forEach((dot) => {
            dot.addEventListener('click', () => {
                showSlide(Number(dot.dataset.slideIndex) || 0);
                startCarousel();
            });
        });

        carousel.addEventListener('mouseenter', stopCarousel);
        carousel.addEventListener('mouseleave', startCarousel);
        carousel.addEventListener('focusin', stopCarousel);
        carousel.addEventListener('focusout', (event) => {
            if (!(event.relatedTarget instanceof Node) || ! carousel.contains(event.relatedTarget)) {
                startCarousel();
            }
        });
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                stopCarousel();
            } else {
                startCarousel();
            }
        });
        prefersReducedMotion.addEventListener('change', startCarousel);
        startCarousel();
    });
};

const initializeTravelPackageImagePreviews = () => {
    document.querySelectorAll('[data-travel-package-image-input]').forEach((input) => {
        if (!(input instanceof HTMLInputElement)) {
            return;
        }

        const preview = input.parentElement?.querySelector('[data-travel-package-image-preview]');

        if (!(preview instanceof HTMLElement)) {
            return;
        }

        let previewUrls = [];

        input.addEventListener('change', () => {
            previewUrls.forEach((url) => URL.revokeObjectURL(url));
            previewUrls = [];
            preview.replaceChildren();

            Array.from(input.files || []).forEach((file, index) => {
                const imageUrl = URL.createObjectURL(file);
                const card = document.createElement('div');
                const image = document.createElement('img');
                const label = document.createElement('p');

                previewUrls.push(imageUrl);
                card.className = 'overflow-hidden rounded-xl border border-slate-200 bg-white';
                image.src = imageUrl;
                image.alt = `Preview background baru ${index + 1}`;
                image.className = 'h-28 w-full object-cover';
                label.textContent = file.name;
                label.className = 'truncate px-3 py-2 text-xs font-semibold text-slate-600';
                card.append(image, label);
                preview.append(card);
            });

            preview.classList.toggle('hidden', preview.childElementCount === 0);
        });
    });
};

initializeTravelPackageCarousels();
initializeTravelPackageImagePreviews();

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
