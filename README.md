# Portofolio — Muhammad Iqbal Fadholi

Portofolio pribadi berbasis **Laravel + Inertia + Vue 3 + Tailwind**, dengan konten bilingual (Indonesia / Inggris), ID card interaktif, dan animasi ringan.

## Fitur

- Halaman utama: hero, about, skills, projects, contact.
- Bahasa ID/EN (switcher globe di kanan atas).
- ID card drag-able dengan foto asli, QR code, dan flip.
- Project Odara & Libraria tampil terdepan.
- Scroll progress, tech marquee, animated counter, dan jam lokal Besuki (WIB).
- Form kontak tersimpan di database.
- Panel admin untuk mengelola profile, project, skill, dan settings.

## Setup setelah `git clone`

> Penting: file `.env`, `vendor/`, `node_modules/`, `public/build/`, dan `database/database.sqlite` **tidak ikut ter-commit**. Tanpa langkah di bawah, foto di ID card bisa memakai fallback dan data halaman kosong.

```bash
git clone https://github.com/corporationalkautsar/Portofolio.git
cd Portofolio

# 1. Dependensi PHP
composer install

# 2. Dependensi JS + build aset (wajib, karena public/build tidak disimpan)
npm install
npm run build

# 3. Environment
cp .env.example .env
php artisan key:generate

# 4. Database SQLite (file-nya tidak ikut clone)
touch database/database.sqlite
php artisan migrate --seed

# 5. Storage link (untuk aset yang di-upload lewat admin)
php artisan storage:link

# 6. Jalankan
php artisan serve
```

Buka `http://127.0.0.1:8000`.

### Catatan Windows / Laragon

- `cp` dan `touch` bisa diganti lewat PowerShell:

```powershell
Copy-Item .env.example .env
New-Item -ItemType File -Path database\database.sqlite -Force
```

- Jalankan migrate + seed:

```powershell
php artisan migrate --seed
```

### Kenapa foto ID card bisa hilang setelah clone?

Foto asalnya ada di `public/images/profile/muhammad-iqbal-fadholi.png` dan **sudah ikut ter-commit**. Yang tidak ikut adalah database SQLite, sehingga tabel `profiles` kosong dan `profile?.photo_url` bernilai null. Sekarang komponen `ProfileIdCard.vue` sudah punya fallback langsung ke foto asli, jadi foto tetap muncul walau database belum di-seed. Untuk data lengkap (nama, bio, kontak, project), tetap jalankan `php artisan migrate --seed`.

## Test

```bash
php artisan test
```

## Akun admin

Seeder **tidak** membuat akun admin. Buat sendiri setelah migrate:

```bash
php artisan portfolio:admin
```

Perintah akan menanyakan email, nama, dan password (minimal 12 karakter). Akun yang dibuat otomatis berstatus admin dan bisa login di `/admin/login`.

## Kontak

- Email: akunmlbb223434@gmail.com
- WhatsApp: 081515274402
- Instagram: balllxy_
- Lokasi: Besuki, Situbondo, Jawa Timur, Indonesia

## License

MIT.
