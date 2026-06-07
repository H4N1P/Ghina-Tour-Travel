const DRAG_THRESHOLD = 6;

/**
 * Mengaktifkan navigasi arrow, drag mouse, serta swipe sentuh pada satu carousel.
 */
function initializeCarousel(carousel) {
    const track = carousel.querySelector('[data-carousel-track]');
    const previousButton = carousel.querySelector('[data-carousel-prev]');
    const nextButton = carousel.querySelector('[data-carousel-next]');
    const itemSelector = carousel.dataset.carouselItem || '[data-carousel-item]';

    if (!track) {
        return;
    }

    let pointerId = null;
    let startX = 0;
    let startY = 0;
    let startScrollLeft = 0;
    let isDragging = false;
    let isHorizontalDrag = false;
    let suppressClickUntil = 0;
    let dragPointerId = null;

    /**
     * Menghitung jarak satu kartu termasuk gap antar kartu.
     */
    const itemDistance = () => {
        const item = track.querySelector(itemSelector);
        const gap = Number.parseFloat(getComputedStyle(track).gap) || 0;

        return item ? item.getBoundingClientRect().width + gap : track.clientWidth;
    };

    /**
     * Menyesuaikan status tombol arrow dengan posisi scroll saat ini.
     */
    const updateButtons = () => {
        const maxScrollLeft = Math.max(0, track.scrollWidth - track.clientWidth);
        const tolerance = 2;

        if (previousButton) {
            previousButton.disabled = track.scrollLeft <= tolerance;
        }

        if (nextButton) {
            nextButton.disabled = track.scrollLeft >= maxScrollLeft - tolerance;
        }
    };

    /**
     * Menggeser carousel satu kartu menggunakan tombol arrow.
     */
    const scrollOneItem = (direction) => {
        track.scrollBy({
            left: itemDistance() * direction,
            behavior: 'smooth',
        });
    };

    /**
     * Menyelesaikan drag dan mengembalikan state visual carousel.
     */
    const finishDrag = () => {
        if (isDragging) {
            suppressClickUntil = Date.now() + 500;
            dragPointerId = pointerId;
        }

        const activePointerId = pointerId;
        pointerId = null;
        isDragging = false;
        isHorizontalDrag = false;
        track.classList.remove('is-dragging');

        if (activePointerId !== null && track.hasPointerCapture?.(activePointerId)) {
            track.releasePointerCapture(activePointerId);
        }
    };

    previousButton?.addEventListener('click', () => scrollOneItem(-1));
    nextButton?.addEventListener('click', () => scrollOneItem(1));

    track.addEventListener('pointerdown', (event) => {
        if (event.button !== 0 || pointerId !== null) {
            return;
        }

        pointerId = event.pointerId;
        startX = event.clientX;
        startY = event.clientY;
        startScrollLeft = track.scrollLeft;
        isDragging = false;
        isHorizontalDrag = false;
    });

    track.addEventListener('pointermove', (event) => {
        if (event.pointerId !== pointerId) {
            return;
        }

        const deltaX = event.clientX - startX;
        const deltaY = event.clientY - startY;

        if (!isDragging && Math.hypot(deltaX, deltaY) < DRAG_THRESHOLD) {
            return;
        }

        if (!isDragging) {
            isHorizontalDrag = Math.abs(deltaX) > Math.abs(deltaY);
            isDragging = isHorizontalDrag;

            if (!isHorizontalDrag) {
                finishDrag();

                return;
            }

            track.setPointerCapture?.(event.pointerId);
            track.classList.add('is-dragging');
        }

        event.preventDefault();
        track.scrollLeft = startScrollLeft - deltaX;
    });

    track.addEventListener('pointerup', finishDrag);
    track.addEventListener('pointercancel', finishDrag);
    track.addEventListener('lostpointercapture', finishDrag);

    track.addEventListener('click', (event) => {
        const clickPointerId = event.pointerId ?? 1;
        const isSuppressedDragClick = Date.now() <= suppressClickUntil
            && (dragPointerId === null || clickPointerId === dragPointerId);

        if (!isSuppressedDragClick) {
            return;
        }

        event.preventDefault();
        event.stopPropagation();
        suppressClickUntil = 0;
        dragPointerId = null;
    }, true);

    track.addEventListener('dragstart', (event) => event.preventDefault());
    track.addEventListener('scroll', updateButtons, { passive: true });
    window.addEventListener('resize', updateButtons);
    updateButtons();
}

/**
 * Mengaktifkan seluruh carousel publik yang tersedia pada halaman.
 */
function initializePublicCarousels() {
    document.querySelectorAll('[data-public-carousel]').forEach(initializeCarousel);
}

document.addEventListener('DOMContentLoaded', initializePublicCarousels);
