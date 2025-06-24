document.addEventListener("DOMContentLoaded", function () {
    const slides = document.querySelectorAll('[data-slide]');
    const totalSlides = slides.length;

    const nextButton = document.getElementById('next-slide');
    const prevButton = document.getElementById('prev-slide');

    let currentIndex = 0;
    let interval;

    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.toggle('opacity-100', i === index);
            slide.classList.toggle('opacity-0', i !== index);
        });
    }

    function nextSlide() {
        currentIndex = (currentIndex + 1) % totalSlides;
        showSlide(currentIndex);
    }

    function prevSlide() {
        currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
        showSlide(currentIndex);
    }

    function startAutoSlide() {
        interval = setInterval(nextSlide, 8000); // setiap 8 detik
    }

    function stopAutoSlide() {
        clearInterval(interval);
    }

    // Button event
    nextButton?.addEventListener('click', () => {
        stopAutoSlide();
        nextSlide();
        startAutoSlide();
    });

    prevButton?.addEventListener('click', () => {
        stopAutoSlide();
        prevSlide();
        startAutoSlide();
    });

    // Inisialisasi
    showSlide(currentIndex);
    startAutoSlide();
});
