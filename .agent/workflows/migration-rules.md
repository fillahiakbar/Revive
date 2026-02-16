---
description: Selective Migration Execution
---

No Bulk Migrations: Dilarang keras menjalankan perintah php artisan migrate secara umum. Hal ini untuk menghindari eksekusi file migration yang tidak sengaja atau yang sudah ada sebelumnya.

Single Path Migration: Setiap kali membuat atau memperbarui tabel database, instruksikan atau jalankan migrasi hanya untuk file spesifik tersebut menggunakan parameter --path.

Command Format: Gunakan format perintah berikut:
php artisan migrate --path=/database/migrations/[nama_file_migration].php

Validation: Sebelum menjalankan migrasi, pastikan file migration tersebut tidak duplikat dengan skema yang sudah ada di folder migration lainnya.

Documentation: Beritahu pengguna file mana yang baru saja dimigrasi secara spesifik.