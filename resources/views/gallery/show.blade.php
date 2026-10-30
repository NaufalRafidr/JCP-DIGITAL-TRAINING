@extends('layouts.app')

@section('title', $folder->name.' • Galeri Kegiatan')

@section('content')
<section class="bg-brand-ivory py-10 text-[#10243A] sm:py-14">
    <div class="mx-auto max-w-[1400px] space-y-8 px-4 sm:px-6 lg:px-10">
        <div class="border-b border-slate-200 pb-7">
            <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
                <div class="min-w-0 flex-1">
                @if($folder->category)
                    <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-[#087F78]">{{ $folder->category->short_name ?: $folder->category->name }}</p>
                @endif
                <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-[#10243A] sm:text-5xl">{{ $folder->name }}</h1>
                @if($folder->description)
                    <p class="mt-3 w-full text-sm leading-6 text-slate-600">{{ $folder->description }}</p>
                @endif
                </div>
                <div class="shrink-0 text-left sm:text-right">
                    <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-[#087F78]">{{ $photos->total() }} foto</p>
                    @if($photos->hasPages())
                        <p class="mt-2 text-xs text-slate-500">Menampilkan {{ $photos->firstItem() }}–{{ $photos->lastItem() }}</p>
                    @endif
                </div>
            </div>
        </div>

        @if($photos->isEmpty())
            <div class="rounded-3xl border border-dashed border-slate-300 p-12 text-center text-slate-500">Folder ini belum memiliki foto.</div>
        @else
            <div class="columns-1 gap-4 sm:columns-2 lg:columns-3" aria-label="Koleksi foto {{ $folder->name }}">
                @foreach($photos as $photo)
                    <button
                        type="button"
                        class="gallery-photo-trigger group mb-4 block w-full break-inside-avoid overflow-hidden rounded-2xl border border-slate-200 bg-white text-left shadow-sm transition-shadow hover:shadow-md focus:outline-none focus:ring-2 focus:ring-[#087F78] focus:ring-offset-2 focus:ring-offset-brand-ivory"
                        data-gallery-photo
                        data-index="{{ $loop->index }}"
                        data-full-url="{{ $photo->image_url ?: Storage::disk('public')->url($photo->path) }}"
                        data-caption="{{ $photo->caption ?: 'Foto kegiatan '.$folder->name }}"
                        aria-label="Perbesar foto {{ $loop->iteration }}"
                    >
                        <span class="relative block overflow-hidden">
                            <img src="{{ $photo->image_url ?: Storage::disk('public')->url($photo->thumbnail_path ?: $photo->path) }}" alt="{{ $photo->caption ?: 'Foto kegiatan '.$folder->name }}" loading="lazy" decoding="async" width="1600" height="1200" class="block h-auto w-full object-cover transition duration-500 group-hover:scale-[1.03]">
                            <span class="absolute inset-0 flex items-center justify-center bg-[#10243A]/0 transition-colors duration-300 group-hover:bg-[#10243A]/35">
                                <span class="flex h-12 w-12 scale-90 items-center justify-center rounded-full bg-[#087F78] text-white opacity-0 shadow-xl transition duration-300 group-hover:scale-100 group-hover:opacity-100" aria-hidden="true">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="6.5"></circle><path stroke-linecap="round" d="m16 16 4.5 4.5M11 8v6M8 11h6"></path></svg>
                                </span>
                            </span>
                        </span>
                        @if($photo->caption)
                            <span class="block px-4 py-3 text-xs font-semibold text-slate-600">{{ $photo->caption }}</span>
                        @endif
                    </button>
                @endforeach
            </div>

            @if($photos->hasPages())
                <div class="border-t border-slate-200 pt-6">
                    {{ $photos->links() }}
                </div>
            @endif
        @endif

        <div class="flex justify-center border-t border-slate-200 pt-8">
            <a href="{{ route('gallery.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-[#087F78] px-6 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-[#066761] focus:outline-none focus:ring-2 focus:ring-[#087F78] focus:ring-offset-2 focus:ring-offset-brand-ivory">
                &larr; Kembali
            </a>
        </div>
    </div>
</section>

<div id="galleryLightbox" class="fixed inset-0 z-[100] isolate hidden items-center justify-center overflow-hidden bg-white/70 p-4 backdrop-blur-xl sm:p-8" role="dialog" aria-modal="true" aria-labelledby="galleryLightboxCaption" aria-hidden="true">
    <div id="galleryLightboxBackdrop" class="pointer-events-none absolute inset-0 z-0 scale-110 bg-cover bg-center opacity-45 blur-3xl" aria-hidden="true"></div>
    <div class="pointer-events-none absolute inset-0 z-0 bg-white/55 backdrop-blur-xl" aria-hidden="true"></div>

    <button id="galleryLightboxClose" type="button" class="absolute right-4 top-4 z-20 flex h-11 w-11 items-center justify-center rounded-full bg-white text-2xl text-[#10243A] shadow-sm transition hover:bg-[#087F78] hover:text-white focus:outline-none focus:ring-2 focus:ring-[#087F78]" aria-label="Tutup foto">&times;</button>

    <button id="galleryLightboxPrevious" type="button" class="absolute left-3 top-1/2 z-20 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full bg-white text-3xl text-[#10243A] shadow-sm transition hover:bg-[#087F78] hover:text-white focus:outline-none focus:ring-2 focus:ring-[#087F78] sm:left-6" aria-label="Foto sebelumnya">&larr;</button>
    <button id="galleryLightboxNext" type="button" class="absolute right-3 top-1/2 z-20 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full bg-white text-3xl text-[#10243A] shadow-sm transition hover:bg-[#087F78] hover:text-white focus:outline-none focus:ring-2 focus:ring-[#087F78] sm:right-6" aria-label="Foto berikutnya">&rarr;</button>

    <div class="relative z-10 flex max-h-full max-w-[92vw] flex-col items-center justify-center gap-4 px-10 sm:px-16">
        <img id="galleryLightboxImage" src="" alt="" class="max-h-[84vh] max-w-[86vw] rounded-xl object-contain shadow-2xl" decoding="async">
        <div class="text-center">
            <p id="galleryLightboxCaption" class="text-sm font-semibold text-[#10243A]"></p>
            <p id="galleryLightboxCounter" class="mt-1 text-xs text-slate-600"></p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const triggers = Array.from(document.querySelectorAll('[data-gallery-photo]'));
        const lightbox = document.getElementById('galleryLightbox');
        const backdrop = document.getElementById('galleryLightboxBackdrop');
        const image = document.getElementById('galleryLightboxImage');
        const caption = document.getElementById('galleryLightboxCaption');
        const counter = document.getElementById('galleryLightboxCounter');
        const closeButton = document.getElementById('galleryLightboxClose');
        const previousButton = document.getElementById('galleryLightboxPrevious');
        const nextButton = document.getElementById('galleryLightboxNext');

        if (!lightbox || !backdrop || !image || !caption || !counter || triggers.length === 0) {
            return;
        }

        let currentIndex = 0;
        let touchStartX = 0;

        const showPhoto = (index) => {
            currentIndex = (index + triggers.length) % triggers.length;
            const trigger = triggers[currentIndex];

            image.src = trigger.dataset.fullUrl;
            backdrop.style.backgroundImage = `url("${trigger.dataset.fullUrl}")`;
            image.alt = trigger.dataset.caption;
            caption.textContent = trigger.dataset.caption;
            counter.textContent = `${currentIndex + 1} / ${triggers.length}`;

            [currentIndex - 1, currentIndex + 1].forEach((nearbyIndex) => {
                const nearbyTrigger = triggers[(nearbyIndex + triggers.length) % triggers.length];
                const preloadedImage = new Image();
                preloadedImage.src = nearbyTrigger.dataset.fullUrl;
            });
        };

        const openLightbox = (index) => {
            showPhoto(index);
            lightbox.classList.remove('hidden');
            lightbox.classList.add('flex');
            lightbox.setAttribute('aria-hidden', 'false');
            document.body.classList.add('overflow-hidden');
            closeButton?.focus();
        };

        const closeLightbox = () => {
            lightbox.classList.add('hidden');
            lightbox.classList.remove('flex');
            lightbox.setAttribute('aria-hidden', 'true');
            image.src = '';
            backdrop.style.backgroundImage = '';
            document.body.classList.remove('overflow-hidden');
        };

        triggers.forEach((trigger, index) => {
            trigger.addEventListener('click', () => openLightbox(index));
        });

        closeButton?.addEventListener('click', closeLightbox);
        previousButton?.addEventListener('click', () => showPhoto(currentIndex - 1));
        nextButton?.addEventListener('click', () => showPhoto(currentIndex + 1));

        lightbox.addEventListener('click', (event) => {
            if (event.target === lightbox) {
                closeLightbox();
            }
        });

        lightbox.addEventListener('pointerdown', (event) => {
            touchStartX = event.clientX;
        });

        lightbox.addEventListener('pointerup', (event) => {
            const swipeDistance = event.clientX - touchStartX;

            if (Math.abs(swipeDistance) < 50) {
                return;
            }

            showPhoto(swipeDistance > 0 ? currentIndex - 1 : currentIndex + 1);
        });

        document.addEventListener('keydown', (event) => {
            if (lightbox.classList.contains('hidden')) {
                return;
            }

            if (event.key === 'Escape') {
                closeLightbox();
            }

            if (event.key === 'ArrowLeft') {
                showPhoto(currentIndex - 1);
            }

            if (event.key === 'ArrowRight') {
                showPhoto(currentIndex + 1);
            }
        });
    });
</script>
@endpush
