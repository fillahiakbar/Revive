```markdown
# 🎌 Anime Manager CRUD - Laravel 12 + Jetstream + Tailwind CSS

Selamat datang di **Anime Manager** — aplikasi CRUD berbasis Laravel yang digunakan untuk mengelola koleksi anime, lengkap dengan fitur backend admin dan frontend publik.

![Anime Manager Screenshot](https://via.placeholder.com/1200x600?text=Anime+Manager+Screenshot)

## 🚀 Fitur Utama

- 🔒 Autentikasi aman dengan **Laravel Jetstream**
- 🎨 Desain dark mode menggunakan **Tailwind CSS**
- 📚 Input data anime secara manual via **admin dashboard**
- 🔍 Tampilkan daftar anime berdasarkan genre dan studio
- 📥 Link download (dari admin) terhubung ke detail anime dari API **Jikan**
- ⚡ Responsive layout untuk semua perangkat

---

## 🛠️ Teknologi yang Digunakan

| Teknologi        | Keterangan                      |
|------------------|----------------------------------|
| Laravel 12       | Framework backend utama         |
| Jetstream        | Autentikasi dan manajemen user  |
| Livewire         | Komponen interaktif real-time   |
| Tailwind CSS     | Styling modern dan fleksibel    |
| Filament Admin   | Panel admin CRUD instan         |
| Jikan API        | API publik data anime           |
| MySQL            | Database relasional             |

---

## 📂 Struktur Fitur

```

app/
├── Http/Controllers/
│   ├── AnimeController.php
│   ├── GenreController.php
│   └── DownloadController.php
├── Models/
│   ├── Anime.php
│   ├── Genre.php
│   └── Download.php
├── Resources/
│   └── views/
│       ├── anime/
│       ├── genres/
│       ├── downloads/
│       └── layouts/
database/
└── migrations/

````

---

## 🔧 Instalasi Lokal

```bash
git clone https://github.com/username/anime-manager.git
cd anime-manager

composer install
npm install && npm run dev

cp .env.example .env
php artisan key:generate

php artisan migrate
php artisan db:seed

php artisan serve
````

---

## ⚙️ Konfigurasi Tambahan

### Jikan API (untuk detail anime)

Tidak perlu kunci API. Langsung konsumsi endpoint seperti:

```
https://api.jikan.moe/v4/anime/{id}
```

### Admin Login (default)

```
Email    : admin@example.com
Password : password
```

---

## 📸 Cuplikan Tampilan

| Halaman      | Tampilan                                                         |
| ------------ | ---------------------------------------------------------------- |
| Beranda      | ![Home](https://via.placeholder.com/400x200?text=Beranda)        |
| Detail Anime | ![Detail](https://via.placeholder.com/400x200?text=Detail+Anime) |
| Admin Panel  | ![Admin](https://via.placeholder.com/400x200?text=Panel+Admin)   |

---

## 📃 Lisensi

MIT License © 2025 — Made with ❤️ by [Fillahi Akbar](mailto:fillahi099q@gmail.com)

---

## 🤝 Kontribusi

Pull request dan masukan sangat diterima!
Silakan buat issue jika menemukan bug atau ide pengembangan.

---

```

Kalau kamu punya nama proyek khusus atau ingin saya sesuaikan dengan fitur tambahan seperti filter genre, pagination, atau upload gambar, tinggal beri tahu saja — akan saya bantu buatkan ulang sesuai kebutuhanmu!
```
