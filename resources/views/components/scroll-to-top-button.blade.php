<button
    id="scrollToTopBtn"
    class="hidden fixed bottom-4 right-4 bg-blue-600 hover:bg-blue-800 text-white p-3 rounded-full shadow-lg z-50 transition-transform transform hover:scale-110"
    onclick="scrollToTop()"
    aria-label="Scroll to top"
>
    <i class="fas fa-arrow-up"></i>
</button>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const scrollToTopBtn = document.getElementById("scrollToTopBtn");

        window.addEventListener("scroll", () => {
            if (window.scrollY > 100) {
                scrollToTopBtn.classList.remove("hidden");
            } else {
                scrollToTopBtn.classList.add("hidden");
            }
        });

        window.scrollToTop = function () {
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        };
    });
</script>
