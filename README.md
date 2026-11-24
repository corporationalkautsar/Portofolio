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

### QR code di ID card

QR tidak lagi mengisi alamat halaman yang sedang dibuka. Isinya sekarang **MECARD**, jadi setelah dipindai dari HP, kontak bisa langsung disimpan:

- Nama
- Email
- WhatsApp
- Lokasi
- URL portofolio (dari setting `qr_url`)

Default `qr_url` = `https://github.com/corporationalkautsar/Portofolio`. Bisa diganti lewat **Admin → Settings → qr_url** (misalnya ke domain production setelah deploy).

### Akun admin vs akun biasa

- **Admin** (`is_admin = true`): bisa login di `/admin` untuk mengelola profile, project, kategori, skill, social link, setting, dan pesan kontak.
- **Akun biasa** (`is_admin = false`): tidak bisa masuk `/admin` (403). Saat ini tidak ada registrasi publik, jadi akun biasa hanya bisa dibuat manual dan tidak punya fungsi di halaman depan.

Buat akun admin dengan:

```bash
php artisan portfolio:admin
```

Perintah akan menanyakan email, nama, dan password (minimal 12 karakter). Seeder **tidak** membuat akun admin otomatis.

### Kenapa foto ID card bisa hilang setelah clone?

Foto asalnya ada di `public/images/profile/muhammad-iqbal-fadholi.png` dan **sudah ikut ter-commit**. Yang tidak ikut adalah database SQLite, sehingga tabel `profiles` kosong dan `profile?.photo_url` bernilai null. Sekarang komponen `ProfileIdCard.vue` sudah punya fallback langsung ke foto asli, jadi foto tetap muncul walau database belum di-seed. Untuk data lengkap (nama, bio, kontak, project), tetap jalankan `php artisan migrate --seed`.

## Test

```bash
php artisan test
```

## Kontak

- Email: akunmlbb223434@gmail.com
- WhatsApp: 081515274402
- Instagram: balllxy_
- Lokasi: Besuki, Situbondo, Jawa Timur, Indonesia

## License

MIT.
