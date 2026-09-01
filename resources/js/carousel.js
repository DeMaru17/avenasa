/**
 * Smooth Continuous Streaming Carousel with Desktop Drag & Mobile Touch
 * Preserves the continuous smooth movement from Update 1 while adding full desktop mouse drag & touch swipe.
 */
export function smoothCarousel(config = {}) {
    const SPEED = config.speed ?? 0.55;

    return {
        speed: SPEED,
        offset: 0,
        halfWidth: 0,
        isPaused: false,
        isHovered: false,
        isFocused: false,
        isDragging: false,
        hasDragged: false,
        preventClick: false,
        rafId: null,

        // Pointer tracking
        pointerId: null,
        startX: 0,
        startY: 0,
        startOffset: 0,
        dragDirectionLocked: null,
        clickTimer: null,
        prefersReducedMotion: false,

        init() {
            this.prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            if (this.prefersReducedMotion) {
                this.speed = 0;
            }

            this.$nextTick(() => {
                this.recalculate();
                this.start();
            });
        },

        destroy() {
            if (this.rafId) {
                cancelAnimationFrame(this.rafId);
                this.rafId = null;
            }
            if (this.clickTimer) {
                clearTimeout(this.clickTimer);
                this.clickTimer = null;
            }
        },

        recalculate() {
            if (this.$refs.track) {
                this.halfWidth = this.$refs.track.scrollWidth / 2;
                if (this.halfWidth > 0) {
                    while (this.offset < 0) this.offset += this.halfWidth;
                    while (this.offset >= this.halfWidth) this.offset -= this.halfWidth;
                    this.$refs.track.style.transform = `translate3d(-${this.offset}px, 0, 0)`;
                }
            }
        },

        start() {
            if (this.rafId) cancelAnimationFrame(this.rafId);
            const tick = () => {
                if (!this.isPaused && !this.isHovered && !this.isFocused && !this.isDragging && this.halfWidth > 0 && this.speed > 0) {
                    this.offset += this.speed;
                    if (this.offset >= this.halfWidth) {
                        this.offset -= this.halfWidth;
                    }
                    if (this.$refs.track) {
                        this.$refs.track.style.transform = `translate3d(-${this.offset}px, 0, 0)`;
                    }
                }
                this.rafId = requestAnimationFrame(tick);
            };
            this.rafId = requestAnimationFrame(tick);
        },

        handlePointerDown(e) {
            if (e.button !== undefined && e.button !== 0) return; // Only primary mouse button
            if (this.halfWidth <= 0) return;

            this.isDragging = true;
            this.hasDragged = false;
            this.preventClick = false;
            this.dragDirectionLocked = null;
            this.pointerId = e.pointerId;
            this.startX = e.clientX;
            this.startY = e.clientY;
            this.startOffset = this.offset;
        },

        handlePointerMove(e) {
            if (!this.isDragging) return;

            const currentX = e.clientX;
            const currentY = e.clientY;
            const deltaX = currentX - this.startX;
            const deltaY = currentY - this.startY;

            // Determine gesture intent for touch devices
            if (!this.dragDirectionLocked && (Math.abs(deltaX) > 6 || Math.abs(deltaY) > 6)) {
                if (Math.abs(deltaY) > Math.abs(deltaX)) {
                    this.dragDirectionLocked = 'vertical';
                    this.isDragging = false;
                    return;
                } else {
                    this.dragDirectionLocked = 'horizontal';
                }
            }

            if (this.dragDirectionLocked === 'vertical') {
                return;
            }

            if (Math.abs(deltaX) > 6) {
                if (!this.hasDragged) {
                    this.hasDragged = true;
                    this.preventClick = true;

                    // Only capture pointer once actual dragging threshold is reached
                    if (e.currentTarget?.setPointerCapture && this.pointerId !== null && this.pointerId !== undefined) {
                        try {
                            e.currentTarget.setPointerCapture(this.pointerId);
                        } catch (_) {}
                    }
                }
            }

            if (this.hasDragged) {
                let tempOffset = this.startOffset - deltaX;
                while (tempOffset < 0) tempOffset += this.halfWidth;
                while (tempOffset >= this.halfWidth) tempOffset -= this.halfWidth;

                this.offset = tempOffset;
                if (this.$refs.track) {
                    this.$refs.track.style.transform = `translate3d(-${this.offset}px, 0, 0)`;
                }
            }
        },

        handlePointerUp(e) {
            if (!this.isDragging) return;
            this.isDragging = false;

            if (e.currentTarget?.releasePointerCapture && this.pointerId !== null && this.pointerId !== undefined) {
                try {
                    e.currentTarget.releasePointerCapture(this.pointerId);
                } catch (_) {}
            }
            this.pointerId = null;

            if (this.hasDragged) {
                if (this.clickTimer) clearTimeout(this.clickTimer);
                this.clickTimer = setTimeout(() => {
                    this.preventClick = false;
                    this.hasDragged = false;
                }, 150);
            } else {
                this.preventClick = false;
                this.hasDragged = false;
            }
        },

        handlePointerCancel(e) {
            if (!this.isDragging) return;
            this.isDragging = false;
            this.hasDragged = false;
            this.preventClick = false;
            this.pointerId = null;
        },

        handleMouseEnter() {
            this.isHovered = true;
        },

        handleMouseLeave() {
            this.isHovered = false;
        },

        handleFocusIn() {
            this.isFocused = true;
        },

        handleFocusOut() {
            this.isFocused = false;
        },

        handleClickCapture(e) {
            if (this.preventClick || this.hasDragged) {
                e.preventDefault();
                e.stopPropagation();
            }
        }
    };
}
