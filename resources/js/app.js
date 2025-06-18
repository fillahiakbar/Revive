import './bootstrap';

document.addEventListener("DOMContentLoaded", function () {
    // Ambil elemen DOM
    const bgElement = document.getElementById("anime-background");
    const titleEl = document.getElementById("anime-title");
    const synopsisEl = document.getElementById("anime-synopsis");
    const typeEl = document.getElementById("anime-type");
    const durationEl = document.getElementById("anime-duration");
    const yearEl = document.getElementById("anime-year");
    const episodesEl = document.getElementById("anime-episodes");
    const rankEl = document.getElementById("anime-rank");

    const nextBtn = document.getElementById("next-bg");
    const prevBtn = document.getElementById("prev-bg");

    // Jika elemen-elemen utama slider tidak ditemukan, jangan jalankan script
    if (!bgElement || !titleEl || !synopsisEl || !typeEl || !durationEl || !yearEl || !episodesEl || !rankEl) {
        return;
    }

    // Data anime slider
    const backgrounds = [
        {
            image: "/img/attackontitan.jpg",
            title: "Attack on Titan Final Season",
            synopsis: "تستمر الحرب من أجل جزيرة باراديس بينما تصل المعركة بين مارلي وإلديا إلى ذروتها، كاشفة عن أسرار مروعة ومصائر مأساوية.",
            type: "TV",
            duration: "24m",
            year: "2023",
            episodes: "12",
            rank: "#1 اختيارات"
        },
        {
            image: "/img/onepiece.jpg",
            title: "One Piece",
            synopsis: "يتبع القصة مغامرات مونكي دي. لوفي، شاب يطمح ليصبح ملك القراصنة، بينما يسافر عبر البحار الخطرة بحثاً عن الكنز الأسطوري 'ون بيس' مع طاقمه المتنوع.",
            type: "TV",
            duration: "23m",
            year: "1999",
            episodes: "1000+",
            rank: "#2 الأسطوري"
        },
        {
            image: "/img/jujutsu.jpg",
            title: "Jujutsu Kaisen Season 2",
            synopsis: "يعود يوجي إيتادوري مع غوجو ساتورو لمواجهة لعنات أقوى وكشف أسرار الماضي التي تهدد توازن عالم الجوجوتسو.",
            type: "TV",
            duration: "24m",
            year: "2023",
            episodes: "24",
            rank: "#3 المفضلة"
        },
        {
            image: "/img/sololeveling.jpg",
            title: "Solo Leveling",
            synopsis: "في عالم يصطاد فيه الصيادون الوحوش لحماية البشرية، يتحول سونغ جين وو من أضعف صياد إلى الأقوى بعد اكتسابه قدرات غامضة.",
            type: "TV",
            duration: "25m",
            year: "2024",
            episodes: "12",
            rank: "#4 الجديد"
        }
    ];

    let currentIndex = 0;

    function showSlide(index) {
        const anime = backgrounds[index];

        // Pastikan semua elemen ada sebelum mengisi kontennya
        if (!bgElement || !titleEl || !synopsisEl || !typeEl || !durationEl || !yearEl || !episodesEl || !rankEl) {
            return;
        }

        bgElement.style.backgroundImage = `url('${anime.image}')`;
        titleEl.textContent = anime.title;
        synopsisEl.textContent = anime.synopsis;
        typeEl.textContent = anime.type;
        durationEl.textContent = anime.duration;
        yearEl.textContent = anime.year;
        episodesEl.textContent = `${anime.episodes} Eps`;
        rankEl.textContent = anime.rank;
    }

    // Tampilkan slide pertama
    showSlide(currentIndex);

    // Set interval perpindahan otomatis
    let interval = setInterval(() => {
        currentIndex = (currentIndex + 1) % backgrounds.length;
        showSlide(currentIndex);
    }, 5000);

    // Fungsi reset interval jika pengguna klik manual
    function resetInterval() {
        clearInterval(interval);
        interval = setInterval(() => {
            currentIndex = (currentIndex + 1) % backgrounds.length;
            showSlide(currentIndex);
        }, 5000);
    }

    // Event klik tombol navigasi (jika tombol ada)
    if (nextBtn) {
        nextBtn.addEventListener("click", () => {
            currentIndex = (currentIndex + 1) % backgrounds.length;
            showSlide(currentIndex);
            resetInterval();
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener("click", () => {
            currentIndex = (currentIndex - 1 + backgrounds.length) % backgrounds.length;
            showSlide(currentIndex);
            resetInterval();
        });
    }
});

