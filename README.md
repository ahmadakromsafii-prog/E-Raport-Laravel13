<<<<<<< HEAD
# e-Rapor Sekolah — Laravel 13

Aplikasi dasar e-Rapor berdasarkan diagram alur yang diberikan: Admin, Guru, dan Siswa; input nilai; validasi; revisi; persetujuan; publikasi; serta tampilan/cetak rapor.

## Kebutuhan
- PHP 8.3+ (Laravel 13) dan ekstensi Laravel umum (pdo_mysql, mbstring, openssl, tokenizer, xml, ctype, json, fileinfo)
- Composer 2
- Node.js 20+ dan npm
- MySQL/MariaDB

## Instalasi Windows (XAMPP) atau Ubuntu
1. Ekstrak folder proyek ke direktori web.
2. Buka terminal pada folder proyek:
   ```bash
   composer install
   copy .env.example .env
   ```
   Di Linux gunakan `cp .env.example .env`.
3. Buat database MySQL bernama `rapor`, lalu atur DB_DATABASE, DB_USERNAME, DB_PASSWORD di `.env`.
4. Jalankan:
   ```bash
   php artisan key:generate
   php artisan migrate --seed
   npm install
   npm run build
   ```
5. Jalankan lokal:
   ```bash
   php artisan serve
   ```
   Buka http://127.0.0.1:8000
## akun demo
yayasan
    username : yayasan
    password : yayasan123
kepala sekolah sd
    username : kepalasd
    password : kepalasd
kepala sekolah smp
    username : kepalasmp
    password : kepalasmp
kepala sekolah sma
    username : kepalasma
    password : kepalasma
untuk user guru dan siswa ditambah sendiri

## Uji coba Langsung tanpa unduh file
https://lawrence-advertise-not-advocacy.trycloudflare.com/login
dikarenakan ini domain gratis maka ketika minta seed anyway di klik saja jangan go back

## Alur status nilai
- `draft`: guru menyimpan nilai.
- `submitted`: guru mengirim nilai untuk diperiksa admin.
- `revision`: admin mengembalikan nilai dengan catatan perbaikan.
- `approved`: admin menyetujui nilai.
- `published`: admin memublikasikan nilai agar dapat dilihat siswa dan masuk rapor.

## Catatan implementasi dan pengembangan lanjutan
Ini adalah fondasi aplikasi Laravel 13 yang dapat dijalankan, bukan paket Laravel yang sudah berisi `vendor/` atau hasil build `public/build`. Folder tersebut dibuat melalui Composer dan npm saat instalasi.
Sebelum produksi, tambahkan: CRUD edit lengkap untuk seluruh data master, impor Excel, pembatasan guru berdasarkan penugasan mapel/kelas, validasi bahwa siswa benar-benar anggota kelas yang dipilih, audit log perubahan nilai, konfigurasi identitas sekolah dan tanda tangan, backup database, pengujian otomatis, kebijakan password, HTTPS, serta pengaturan publikasi per kelas/mapel. Akun demo hanya untuk uji coba.

## Versi framework
Dependensi Composer menggunakan `laravel/framework: ^13.0` dan `php: ^8.3`, sesuai persyaratan resmi Laravel 13. Composer akan memasang rilis patch Laravel 13 terbaru yang kompatibel saat `composer install` dijalankan.
=======
# E-Raport-Laravel13
MATA KULIAH BASIS DATA TERAPAN SEMESTER 5
>>>>>>> f72eb5136a1a54d7a46d19049fb19eaa95600e08
