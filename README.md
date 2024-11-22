# Nord Night

![Nord Night Logo](/logo.jpg) <!-- Replace with the actual path to your logo -->

## Deskripsi

Nord Night adalah starter pack aplikasi web yang dibangun dengan Laravel dan Filament. Proyek ini dirancang untuk memberikan pengalaman pengguna yang menarik dan interaktif, serta menyediakan fondasi yang kuat untuk pengembangan aplikasi web yang lebih lanjut. Aplikasi ini mengintegrasikan fitur-fitur modern untuk memudahkan pengguna dalam menjelajahi konten yang berkaitan dengan tema malam utara.

## Fitur Utama

- **Antarmuka Pengguna yang Menarik**: Desain yang responsif dan modern untuk pengalaman pengguna yang optimal.
- **Manajemen Pengguna**: Fitur untuk mendaftar, masuk, dan mengelola profil pengguna.
- **Manajemen Error**: Fitur untuk melihat error yang terjadi di website.
- **Manajemen .ENV**: Fitur untuk memudahkan melakukan modifikasi environment variable di website.
- **Komponen Fleksibel**: Komponen siap pakai yang sangat flexible, developer dapat dengan mudah melakukan kostumisasi dengan bebas.

## Prerequisites

Sebelum memulai, pastikan Anda memiliki:

- PHP >= 8.0
- Composer
- Laravel >= 9.x
- Database (MySQL, PostgreSQL, dll.)

## Instalasi

Ikuti langkah-langkah berikut untuk menginstal dan menjalankan proyek ini:

1. **Clone Repository**:
```
    git clone https://github.com/CloudMyn/Nord-Night.git
```

2. **Instal Dependensi**:
```
    composer install
```

3. **Buat File `.env`**:

   Salin file `.env.example` menjadi `.env` dan sesuaikan konfigurasi database Anda.

```
    cp .env.example .env
```

4. **Generate Kunci Aplikasi**:

```
    php artisan key:generate
```

5. **Migrasi Database**:

```
    php artisan migrate
```

6. **Jalankan Database Seeder**:

```
    php artisan db:seed
```

7. **Jalankan Server**:

```
    php artisan serve
```

   Aplikasi akan tersedia di `http://localhost:8000`.

## Penggunaan

Setelah aplikasi berjalan, Anda dapat mengaksesnya melalui browser. Daftar atau masuk untuk mulai menjelajahi fitur-fitur yang tersedia.

## Kontribusi

Kontribusi sangat diterima! Silakan buat pull request atau buka isu jika Anda menemukan bug atau memiliki saran.

## Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

## Kontak

Untuk pertanyaan lebih lanjut, silakan hubungi:

- **Abdi Nat**: [cloudmyn46@gmail.com](mailto:cloudmyn46@gmail.com)
- **GitHub**: [cloudmyn](https://github.com/cloudmyn)

---

Terima kasih telah menggunakan Nord Night! Selamat menjelajahi!
