REDESAIN E-RAPOR - CATATAN
==========================

Perubahan utama:
- Tampilan login dan layout utama diperbarui dengan tema hijau-teal, krem, dan aksen emas.
- Menambahkan animasi masuk, hover, elemen dekoratif, dan animasi kartu.
- Menggunakan Bootstrap 5.3.8, Bootstrap Icons, Google Fonts Plus Jakarta Sans,
  Animate.css, dan AOS melalui CDN.
- Menghapus pemanggilan @vite dari halaman login dan layout aplikasi agar halaman
  tersebut tidak lagi bergantung pada public/build/manifest.json.
- Route, controller, model, database, dan alur autentikasi tidak diubah.

Catatan:
- Karena aset UI menggunakan CDN, perangkat harus terhubung ke internet agar
  font, ikon, Bootstrap, dan library animasi dapat dimuat.
- Jalankan project seperti biasa:
    php artisan serve
- Tidak perlu menjalankan npm run build untuk halaman login dan layout utama.
