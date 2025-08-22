// /public/js/slider.js
class HeroSlider {
    constructor() {
        this.slides = document.querySelectorAll('[data-slide]');
        this.index = 0;
        this.init();
    }

    init() {
        if (!this.slides.length) return;

        this.updateContent();
        document.getElementById('next-slide')?.addEventListener('click', () => this.next());
        document.getElementById('prev-slide')?.addEventListener('click', () => this.prev());
    }

    updateContent() {
        this.slides.forEach((slide, i) => {
            slide.style.opacity = i === this.index ? '1' : '0';
        });

        const current = this.slides[this.index];
        if (!current) return;

        document.getElementById('slide-title').textContent = current.dataset.title || '';
        document.getElementById('slide-choice').textContent = current.dataset.choice || '';
        document.getElementById('slide-type').textContent = current.dataset.type || '';
        document.getElementById('slide-duration').textContent = current.dataset.duration || '';
        document.getElementById('slide-year').textContent = current.dataset.year || '';
        document.getElementById('slide-quality').textContent = current.dataset.quality || '';
        document.getElementById('slide-episodes').textContent = current.dataset.episodes || '';
        document.getElementById('slide-description').textContent = current.dataset.description || '';
        document.getElementById('hero-link')?.setAttribute('href', current.dataset.link || '#');
    }

    next() {
        this.index = (this.index + 1) % this.slides.length;
        this.updateContent();
    }

    prev() {
        this.index = (this.index - 1 + this.slides.length) % this.slides.length;
        this.updateContent();
    }
}

document.addEventListener('DOMContentLoaded', () => new HeroSlider());
