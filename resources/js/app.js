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

            const scheduleSection = scheduleContainer.closest('#home-training-schedule');

            if (scheduleSection instanceof HTMLElement) {
                scheduleSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
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

        const interval = Math.max(Number(carousel.dataset.carouselInterval) || 2500, 1000);
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
    document.querySelectorAll('[data-travel-package-image-editor]').forEach((editor) => {
        if (!(editor instanceof HTMLElement)) {
            return;
        }

        const inputs = Array.from(editor.querySelectorAll('[data-travel-package-image-input]'))
            .filter((input) => input instanceof HTMLInputElement);
        const urlsInput = editor.querySelector('[data-travel-package-image-urls]');
        const preview = editor.querySelector('[data-travel-package-image-preview]');
        const settingsFields = editor.querySelector('[data-new-background-settings]');

        if (!(preview instanceof HTMLElement) || !(settingsFields instanceof HTMLElement)) {
            return;
        }

        let previewUrls = [];
        let urlRenderTimeout = null;
        const fileSettings = new WeakMap();
        const remoteSettings = new Map();

        const defaultSettings = () => ({ positionX: 50, positionY: 50, scale: 100 });
        const normalizeSettings = (settings) => ({
            positionX: Math.max(0, Math.min(100, Number(settings?.positionX) || 0)),
            positionY: Math.max(0, Math.min(100, Number(settings?.positionY) || 0)),
            scale: Math.max(100, Math.min(200, Number(settings?.scale) || 100)),
        });
        const selectedFiles = () => inputs.flatMap((input) => Array.from(input.files || []).map((file, fileIndex) => ({
            file,
            fileIndex,
            input,
        })));
        const selectedRemoteUrls = () => urlsInput instanceof HTMLTextAreaElement
            ? Array.from(new Set(urlsInput.value.split(/\r?\n/).map((url) => url.trim()).filter(Boolean)))
            : [];

        const appendSettingsFields = (fragment, fieldName, index, settings) => {
            Object.entries({
                position_x: settings.positionX,
                position_y: settings.positionY,
                scale: settings.scale,
            }).forEach(([settingName, value]) => {
                const input = document.createElement('input');

                input.type = 'hidden';
                input.name = `${fieldName}[${index}][${settingName}]`;
                input.value = String(value);
                fragment.append(input);
            });
        };

        const syncSettingsFields = () => {
            const fragment = document.createDocumentFragment();

            selectedFiles().forEach(({ file }, index) => {
                appendSettingsFields(
                    fragment,
                    'travel_package_background_upload_settings',
                    index,
                    fileSettings.get(file) || defaultSettings(),
                );
            });
            selectedRemoteUrls().forEach((url, index) => {
                appendSettingsFields(
                    fragment,
                    'travel_package_background_url_settings',
                    index,
                    remoteSettings.get(url) || defaultSettings(),
                );
            });
            settingsFields.replaceChildren(fragment);
        };

        const createPreviewCard = ({ imageUrl, labelText, alt, settings, draftKind, draftIndex, remove }) => {
            const card = document.createElement('article');
            const imageContainer = document.createElement('div');
            const image = document.createElement('img');
            const label = document.createElement('p');
            const actions = document.createElement('div');
            const editButton = document.createElement('button');
            const removeButton = document.createElement('button');

            card.className = 'overflow-hidden rounded-xl border border-slate-200 bg-white [content-visibility:auto] [contain-intrinsic-size:190px]';
            imageContainer.className = 'h-32 overflow-hidden bg-slate-100 [contain:paint]';
            image.src = imageUrl;
            image.alt = alt;
            image.loading = 'lazy';
            image.decoding = 'async';
            image.fetchPriority = 'low';
            image.style.objectPosition = `${settings.positionX}% ${settings.positionY}%`;
            image.style.transformOrigin = `${settings.positionX}% ${settings.positionY}%`;
            image.style.transform = `scale(${settings.scale / 100})`;
            image.className = 'h-full w-full object-cover';
            label.textContent = labelText;
            label.className = 'truncate px-3 pt-3 text-xs font-semibold text-slate-600';
            actions.className = 'flex gap-2 p-3';
            editButton.type = 'button';
            editButton.textContent = 'Atur gambar';
            editButton.dataset.backgroundEditorOpen = '';
            editButton.dataset.backgroundDraftKind = draftKind;
            editButton.dataset.backgroundDraftIndex = String(draftIndex);
            editButton.dataset.backgroundUrl = imageUrl;
            editButton.dataset.positionX = String(settings.positionX);
            editButton.dataset.positionY = String(settings.positionY);
            editButton.dataset.scale = String(settings.scale);
            editButton.className = 'flex-1 rounded-lg bg-[#E2F6F2] px-2 py-2 text-xs font-bold text-[#087F78] hover:bg-[#C9EEE8]';
            removeButton.type = 'button';
            removeButton.textContent = 'Hapus';
            removeButton.setAttribute('aria-label', `Hapus ${labelText} dari pilihan background`);
            removeButton.className = 'rounded-lg bg-rose-50 px-3 py-2 text-xs font-bold text-rose-700 transition-colors hover:bg-rose-100 focus:outline-none focus:ring-2 focus:ring-rose-500/30';
            removeButton.addEventListener('click', remove);
            imageContainer.append(image);
            actions.append(editButton, removeButton);
            card.append(imageContainer, label, actions);

            return card;
        };

        const renderPreviews = () => {
            previewUrls.forEach((url) => URL.revokeObjectURL(url));
            previewUrls = [];
            preview.replaceChildren();

            const files = selectedFiles();
            const remoteUrls = selectedRemoteUrls();

            files.forEach(({ file, fileIndex, input }, index) => {
                const imageUrl = URL.createObjectURL(file);
                const settings = fileSettings.get(file) || defaultSettings();

                previewUrls.push(imageUrl);
                preview.append(createPreviewCard({
                    imageUrl,
                    labelText: file.name,
                    alt: `Preview background baru ${index + 1}`,
                    settings,
                    draftKind: 'file',
                    draftIndex: index,
                    remove: () => {
                        const retainedFiles = new DataTransfer();

                        Array.from(input.files || []).forEach((selectedFile, selectedFileIndex) => {
                            if (selectedFileIndex !== fileIndex) {
                                retainedFiles.items.add(selectedFile);
                            }
                        });

                        input.files = retainedFiles.files;
                        renderPreviews();
                    },
                }));
            });

            remoteUrls.forEach((url, index) => {
                const settings = remoteSettings.get(url) || defaultSettings();

                preview.append(createPreviewCard({
                    imageUrl: url,
                    labelText: url,
                    alt: `Preview URL background ${index + 1}`,
                    settings,
                    draftKind: 'url',
                    draftIndex: index,
                    remove: () => {
                        if (!(urlsInput instanceof HTMLTextAreaElement)) {
                            return;
                        }

                        remoteSettings.delete(url);
                        urlsInput.value = remoteUrls
                            .filter((remoteUrl, remoteUrlIndex) => remoteUrlIndex !== index)
                            .join('\n');
                        renderPreviews();
                    },
                }));
            });

            syncSettingsFields();
            preview.classList.toggle('hidden', preview.childElementCount === 0);
        };

        editor.addEventListener('travel-package-draft-settings-updated', (event) => {
            const detail = event.detail;

            if (! detail || ! ['file', 'url'].includes(detail.kind)) {
                return;
            }

            const settings = normalizeSettings(detail.settings);

            if (detail.kind === 'file') {
                const selectedFile = selectedFiles()[detail.index]?.file;

                if (selectedFile instanceof File) {
                    fileSettings.set(selectedFile, settings);
                }
            } else {
                const selectedUrl = selectedRemoteUrls()[detail.index];

                if (typeof selectedUrl === 'string') {
                    remoteSettings.set(selectedUrl, settings);
                }
            }

            syncSettingsFields();
        });

        inputs.forEach((input) => input.addEventListener('change', renderPreviews));

        if (urlsInput instanceof HTMLTextAreaElement) {
            urlsInput.addEventListener('input', () => {
                window.clearTimeout(urlRenderTimeout);
                urlRenderTimeout = window.setTimeout(renderPreviews, 250);
            });
        }

        editor.closest('form')?.addEventListener('submit', syncSettingsFields);
        renderPreviews();
    });
};

const initializeHomepageHeroPreviews = () => {
    document.querySelectorAll('[data-homepage-hero-editor]').forEach((editor) => {
        if (!(editor instanceof HTMLElement)) {
            return;
        }

        const fileInput = editor.querySelector('[data-homepage-hero-file]');
        const urlInput = editor.querySelector('[data-homepage-hero-url]');
        const removeInput = editor.querySelector('[data-homepage-hero-remove]');
        const preview = editor.querySelector('[data-homepage-hero-preview]');
        const emptyState = editor.querySelector('[data-homepage-hero-empty]');

        if (!(fileInput instanceof HTMLInputElement)
            || !(urlInput instanceof HTMLInputElement)
            || !(preview instanceof HTMLImageElement)
            || !(emptyState instanceof HTMLElement)) {
            return;
        }

        let objectUrl = null;

        const renderPreview = () => {
            if (objectUrl !== null) {
                URL.revokeObjectURL(objectUrl);
                objectUrl = null;
            }

            const selectedFile = fileInput.files?.[0];
            const shouldRemove = removeInput instanceof HTMLInputElement && removeInput.checked;
            let imageUrl = shouldRemove ? '' : urlInput.value.trim();

            if (! shouldRemove && selectedFile instanceof File) {
                objectUrl = URL.createObjectURL(selectedFile);
                imageUrl = objectUrl;
            } else if (! shouldRemove && imageUrl === '') {
                imageUrl = editor.dataset.existingHero || '';
            }

            if (imageUrl === '') {
                preview.removeAttribute('src');
                preview.classList.add('hidden');
                emptyState.classList.remove('hidden');

                return;
            }

            preview.src = imageUrl;
            preview.classList.remove('hidden');
            emptyState.classList.add('hidden');
        };

        fileInput.addEventListener('change', renderPreview);
        urlInput.addEventListener('input', renderPreview);

        if (removeInput instanceof HTMLInputElement) {
            removeInput.addEventListener('change', renderPreview);
        }
    });
};

const initializeHomepageBackgroundManagers = () => {
    document.querySelectorAll('[data-homepage-background-manager]').forEach((manager) => {
        if (!(manager instanceof HTMLElement)) {
            return;
        }

        const deleteUrl = manager.dataset.backgroundDeleteUrl;
        const updateUrl = manager.dataset.backgroundUpdateUrl;
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const list = manager.querySelector('[data-background-list]');
        const storedSection = list?.parentElement;
        const emptyState = manager.querySelector('[data-background-empty]');
        const selectAll = manager.querySelector('[data-background-select-all]');
        const deleteSelectedButton = manager.querySelector('[data-background-delete-selected]');
        const selectionCount = manager.querySelector('[data-background-selection-count]');
        const managerStatus = manager.querySelector('[data-background-manager-status]');
        const modal = manager.querySelector('[data-background-editor-modal]');
        const editorCanvas = manager.querySelector('[data-background-editor-canvas]');
        const editorImage = manager.querySelector('[data-background-editor-image]');
        const positionXInput = manager.querySelector('[data-background-position-x]');
        const positionYInput = manager.querySelector('[data-background-position-y]');
        const scaleInput = manager.querySelector('[data-background-scale]');
        const positionXOutput = manager.querySelector('[data-background-position-x-output]');
        const positionYOutput = manager.querySelector('[data-background-position-y-output]');
        const scaleOutput = manager.querySelector('[data-background-scale-output]');
        const editorStatus = manager.querySelector('[data-background-editor-status]');
        const editorSaveButton = manager.querySelector('[data-background-editor-save]');
        let activeEditorButton = null;
        let activeBackgroundSource = '';
        let activeDraftKind = '';
        let activeDraftIndex = -1;
        let dragState = null;

        const showStatus = (element, message, isError = false) => {
            if (!(element instanceof HTMLElement)) {
                return;
            }

            element.textContent = message;
            element.classList.remove('hidden', 'border-rose-200', 'bg-rose-50', 'text-rose-700', 'border-emerald-200', 'bg-emerald-50', 'text-emerald-700');
            element.classList.add(
                'border',
                ...(isError
                    ? ['border-rose-200', 'bg-rose-50', 'text-rose-700']
                    : ['border-emerald-200', 'bg-emerald-50', 'text-emerald-700']),
            );
        };

        const request = async (url, method, payload) => {
            const response = await fetch(url, {
                method,
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || '',
                },
                body: JSON.stringify(payload),
            });
            const responseBody = await response.json().catch(() => ({}));

            if (! response.ok) {
                const validationMessage = responseBody.errors
                    ? Object.values(responseBody.errors).flat()[0]
                    : null;

                throw new Error(validationMessage || responseBody.message || `Permintaan gagal (HTTP ${response.status}).`);
            }

            return responseBody;
        };

        const backgroundCards = () => Array.from(manager.querySelectorAll('[data-background-card]'));
        const selectedInputs = () => Array.from(manager.querySelectorAll('[data-background-select]:checked'));

        const updateSelection = () => {
            const cards = backgroundCards();
            const selected = selectedInputs();

            if (selectionCount instanceof HTMLElement) {
                selectionCount.textContent = selected.length === 0
                    ? 'Belum ada gambar dipilih.'
                    : `${selected.length} gambar dipilih.`;
            }

            if (deleteSelectedButton instanceof HTMLButtonElement) {
                deleteSelectedButton.disabled = selected.length === 0;
            }

            if (selectAll instanceof HTMLInputElement) {
                selectAll.checked = cards.length > 0 && selected.length === cards.length;
                selectAll.indeterminate = selected.length > 0 && selected.length < cards.length;
                selectAll.disabled = cards.length === 0;
            }

            storedSection?.classList.toggle('hidden', cards.length === 0);
            emptyState?.classList.toggle('hidden', cards.length !== 0);
        };

        const deleteBackgrounds = async (backgrounds) => {
            if (! deleteUrl || backgrounds.length === 0) {
                return;
            }

            const confirmation = backgrounds.length === 1
                ? 'Hapus background ini sekarang?'
                : `Hapus ${backgrounds.length} background yang dipilih sekarang?`;

            if (! window.confirm(confirmation)) {
                return;
            }

            if (deleteSelectedButton instanceof HTMLButtonElement) {
                deleteSelectedButton.disabled = true;
            }

            try {
                const response = await request(deleteUrl, 'DELETE', { backgrounds });

                backgroundCards().forEach((card) => {
                    if (backgrounds.includes(card.dataset.backgroundSource || '')) {
                        card.remove();
                    }
                });
                showStatus(managerStatus, response.message || 'Background berhasil dihapus.');
            } catch (error) {
                showStatus(managerStatus, error instanceof Error ? error.message : 'Background gagal dihapus.', true);
            } finally {
                updateSelection();
            }
        };

        const editorElementsAreValid = modal instanceof HTMLElement
            && editorCanvas instanceof HTMLElement
            && editorImage instanceof HTMLImageElement
            && positionXInput instanceof HTMLInputElement
            && positionYInput instanceof HTMLInputElement
            && scaleInput instanceof HTMLInputElement
            && positionXOutput instanceof HTMLOutputElement
            && positionYOutput instanceof HTMLOutputElement
            && scaleOutput instanceof HTMLOutputElement
            && editorSaveButton instanceof HTMLButtonElement;

        const renderEditorPreview = () => {
            if (! editorElementsAreValid) {
                return;
            }

            const positionX = Number(positionXInput.value);
            const positionY = Number(positionYInput.value);
            const scale = Number(scaleInput.value);

            editorImage.style.objectPosition = `${positionX}% ${positionY}%`;
            editorImage.style.transformOrigin = `${positionX}% ${positionY}%`;
            editorImage.style.transform = `scale(${scale / 100})`;
            positionXOutput.value = String(positionX);
            positionYOutput.value = String(positionY);
            scaleOutput.value = String(scale);
        };

        const currentEditorSettings = () => ({
            position_x: Number(positionXInput.value),
            position_y: Number(positionYInput.value),
            scale: Number(scaleInput.value),
        });

        const updateActiveCard = (settings) => {
            const card = activeEditorButton?.closest('[data-background-card], article');
            const cardImage = card?.querySelector('[data-background-card-image], img');

            if (cardImage instanceof HTMLImageElement) {
                cardImage.style.objectPosition = `${settings.position_x}% ${settings.position_y}%`;
                cardImage.style.transformOrigin = `${settings.position_x}% ${settings.position_y}%`;
                cardImage.style.transform = `scale(${settings.scale / 100})`;
            }

            if (activeEditorButton instanceof HTMLButtonElement) {
                activeEditorButton.dataset.positionX = String(settings.position_x);
                activeEditorButton.dataset.positionY = String(settings.position_y);
                activeEditorButton.dataset.scale = String(settings.scale);
            }
        };

        const closeEditor = () => {
            if (!(modal instanceof HTMLElement)) {
                return;
            }

            modal.classList.add('hidden');
            modal.classList.remove('flex');
            activeEditorButton = null;
            activeBackgroundSource = '';
            activeDraftKind = '';
            activeDraftIndex = -1;
            dragState = null;
        };

        manager.addEventListener('change', (event) => {
            if (!(event.target instanceof HTMLInputElement)) {
                return;
            }

            if (event.target.matches('[data-background-select-all]')) {
                manager.querySelectorAll('[data-background-select]').forEach((input) => {
                    if (input instanceof HTMLInputElement) {
                        input.checked = event.target.checked;
                    }
                });
            }

            if (event.target.matches('[data-background-select], [data-background-select-all]')) {
                updateSelection();
            }
        });

        manager.addEventListener('click', (event) => {
            if (!(event.target instanceof Element)) {
                return;
            }

            const deleteOneButton = event.target.closest('[data-background-delete-one]');
            if (deleteOneButton instanceof HTMLButtonElement) {
                deleteBackgrounds([deleteOneButton.dataset.backgroundSource || '']);

                return;
            }

            if (event.target.closest('[data-background-delete-selected]')) {
                deleteBackgrounds(selectedInputs().map((input) => input.value));

                return;
            }

            const editorButton = event.target.closest('[data-background-editor-open]');
            if (editorButton instanceof HTMLButtonElement && editorElementsAreValid) {
                activeEditorButton = editorButton;
                activeBackgroundSource = editorButton.dataset.backgroundSource || '';
                activeDraftKind = editorButton.dataset.backgroundDraftKind || '';
                activeDraftIndex = Number(editorButton.dataset.backgroundDraftIndex ?? -1);
                editorImage.src = editorButton.dataset.backgroundUrl || '';
                positionXInput.value = editorButton.dataset.positionX || '50';
                positionYInput.value = editorButton.dataset.positionY || '50';
                scaleInput.value = editorButton.dataset.scale || '100';
                editorSaveButton.textContent = activeDraftKind === '' ? 'Simpan posisi' : 'Terapkan pengaturan';
                editorStatus?.classList.add('hidden');
                renderEditorPreview();
                modal.classList.remove('hidden');
                modal.classList.add('flex');

                return;
            }

            if (event.target.closest('[data-background-editor-close]') || event.target === modal) {
                closeEditor();
            }
        });

        if (editorElementsAreValid) {
            [positionXInput, positionYInput, scaleInput].forEach((input) => {
                input.addEventListener('input', renderEditorPreview);
            });

            editorCanvas.addEventListener('pointerdown', (event) => {
                const bounds = editorCanvas.getBoundingClientRect();

                dragState = {
                    pointerId: event.pointerId,
                    startX: event.clientX,
                    startY: event.clientY,
                    positionX: Number(positionXInput.value),
                    positionY: Number(positionYInput.value),
                    width: bounds.width,
                    height: bounds.height,
                };
                editorCanvas.setPointerCapture(event.pointerId);
            });

            editorCanvas.addEventListener('pointermove', (event) => {
                if (dragState === null || dragState.pointerId !== event.pointerId) {
                    return;
                }

                const nextX = dragState.positionX - ((event.clientX - dragState.startX) / dragState.width) * 100;
                const nextY = dragState.positionY - ((event.clientY - dragState.startY) / dragState.height) * 100;
                positionXInput.value = String(Math.max(0, Math.min(100, Math.round(nextX))));
                positionYInput.value = String(Math.max(0, Math.min(100, Math.round(nextY))));
                renderEditorPreview();
            });

            const endDrag = (event) => {
                if (dragState?.pointerId === event.pointerId) {
                    dragState = null;
                }
            };

            editorCanvas.addEventListener('pointerup', endDrag);
            editorCanvas.addEventListener('pointercancel', endDrag);

            editorSaveButton.addEventListener('click', async () => {
                const settings = currentEditorSettings();

                if (activeDraftKind !== '' && activeDraftIndex >= 0) {
                    updateActiveCard(settings);
                    manager.dispatchEvent(new CustomEvent('travel-package-draft-settings-updated', {
                        detail: {
                            kind: activeDraftKind,
                            index: activeDraftIndex,
                            settings: {
                                positionX: settings.position_x,
                                positionY: settings.position_y,
                                scale: settings.scale,
                            },
                        },
                    }));
                    showStatus(managerStatus, 'Pengaturan gambar diterapkan dan akan disimpan bersama gambar.');
                    closeEditor();

                    return;
                }

                if (! updateUrl || activeBackgroundSource === '') {
                    return;
                }

                editorSaveButton.disabled = true;

                try {
                    const response = await request(updateUrl, 'PUT', {
                        background: activeBackgroundSource,
                        ...settings,
                    });
                    updateActiveCard(response.settings);
                    showStatus(managerStatus, response.message || 'Pengaturan gambar berhasil disimpan.');
                    closeEditor();
                } catch (error) {
                    showStatus(editorStatus, error instanceof Error ? error.message : 'Pengaturan gambar gagal disimpan.', true);
                } finally {
                    editorSaveButton.disabled = false;
                }
            });
        }

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && modal instanceof HTMLElement && ! modal.classList.contains('hidden')) {
                closeEditor();
            }
        });

        updateSelection();
    });
};

initializeTravelPackageCarousels();
initializeTravelPackageImagePreviews();
initializeHomepageHeroPreviews();
initializeHomepageBackgroundManagers();

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
