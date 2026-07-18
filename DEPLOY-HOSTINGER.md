# Deploy "piket-mfls" ke Hostinger via Git Deploy (hPanel)

## Fakta penting dari dokumentasi resmi Hostinger

1. **Fitur Git di hPanel cuma clone/copy file — TIDAK menjalankan
   `composer install` atau `npm run build` sama sekali.** Jadi hasil build
   Vite (`public/build`) dan folder Composer (`vendor/`) harus SUDAH ada di
   git repo kamu, kecuali kamu punya SSH untuk menjalankannya manual di
   server.
2. **Document root TIDAK BISA diubah** di paket Web/Cloud Hostinger (beda
   dari VPS). Jadi trik umum "arahkan document root ke folder `public`"
   tidak tersedia di panelnya. Solusi resminya: seluruh project (termasuk
   `app/`, `artisan`, `composer.json`, dst) di-deploy langsung ke
   `public_html`, lalu ditambahkan `.htaccess` di root yang me-redirect
   semua request ke folder `public/` di dalamnya.

Sumber: [How to deploy a Git repository in Hostinger](https://www.hostinger.com/support/1583302-how-to-deploy-a-git-repository-in-hostinger/) dan [How to Deploy Laravel at Hostinger](https://www.hostinger.com/support/6152127-how-to-deploy-laravel-8-at-hostinger/).

## Apa yang sudah saya perbaiki di project ini

- `.gitignore`: baris `/public/build` dikomentari jadi `#/public/build` agar
  git mau melacak hasil build Vite.
- `package.json`: bagian `"dependencies"` yang salah (berisi ~100 paket
  internal termasuk `fsevents`, khusus macOS) sudah dihapus. Ini bikin
  `npm install` **selalu gagal** di Linux/Windows (`EBADPLATFORM`). Sudah
  saya tes ulang `npm install` + `npm run build` di Linux — sukses.
- `public/build/` sudah berisi hasil build production yang valid.
- `.htaccess` baru di **root project** (bukan di dalam `public/`) berisi
  rule redirect ke folder `public/`, sesuai instruksi resmi Hostinger di
  atas.

## Langkah deploy

### 1. Timpa file-file ini di project lokal kamu
Dari paket zip yang saya kasih, salin ke root project kamu (menimpa yang lama):
- `.gitignore`
- `package.json`
- `package-lock.json`
- `htaccess-root-taruh-di-root-project` ← rename file ini jadi `.htaccess`,
  lalu taruh di **root project**, sejajar dengan `artisan` dan
  `composer.json` (BUKAN di dalam folder `public/`, karena `public/` sudah
  punya `.htaccess` sendiri bawaan Laravel — jangan ditimpa yang itu)
- folder `public/build/`

### 2. Cek: kamu punya akses SSH atau tidak?
Buka hPanel → ketik "SSH" di kolom pencarian. Kalau ada menu **SSH Access**
dengan opsi untuk enable/lihat kredensial, berarti kamu punya akses SSH.

**Kalau PUNYA SSH** (paket Business ke atas biasanya ada):
- Biarkan `/vendor` tetap di `.gitignore` (tidak usah dikomentari).
- Setelah setiap deploy, SSH ke server lalu jalankan:
  ```bash
  cd domains/namadomainkamu.com/public_html
  composer install --no-dev --optimize-autoloader
  php artisan migrate --force
  php artisan storage:link
  php artisan config:cache
  ```

**Kalau TIDAK PUNYA SSH:**
- Folder `vendor/` **wajib** ikut di-commit ke git juga (sama seperti
  `public/build`), karena tidak ada cara lain untuk generate-nya di server.
  Buka `.gitignore`, comment baris `/vendor` jadi `#/vendor`, lalu di
  komputer kamu jalankan:
  ```bash
  composer install --no-dev --optimize-autoloader
  git add vendor -f
  ```
- `php artisan migrate` tidak bisa dijalankan tanpa SSH — kamu perlu bikin
  route/controller sementara yang memanggil `Artisan::call('migrate')`,
  lalu akses sekali via browser, atau (lebih aman) hubungi Hostinger untuk
  opsi cron job "Once" yang menjalankan `php artisan migrate --force`.

### 3. Commit & push
```bash
git add .gitignore package.json package-lock.json public/build .htaccess
git commit -m "fix: benerin package.json, un-ignore build assets, tambah htaccess root"
git push
```

### 4. Setting di hPanel → Advanced → Git
- **Root directory**: isi `public_html` (bukan subfolder, karena document
  root tidak bisa diarahkan ke subfolder/public di paket Web/Cloud).
- Klik **Deploy** (atau **Redeploy** kalau repo sudah pernah terhubung).

### 5. Setup `.env` di server
Lewat File Manager, buka `public_html/.env` (kalau belum ada, copy dari
`.env.example`), lalu isi:
```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://namadomainkamu.com
DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=nama_database_dari_hpanel
DB_USERNAME=user_dari_hpanel
DB_PASSWORD=password_dari_hpanel
```
Database MySQL dibuat di hPanel → **Databases → MySQL Databases**.
`APP_KEY` bisa digenerate lokal (`php artisan key:generate --show`) lalu
ditempel manual ke `.env` server kalau tidak ada SSH.

## Kalau masih belum jalan setelah ini
Kirim pesan error yang muncul di browser (atau screenshot layar putih/500),
itu akan lebih cepat ketemu akar masalahnya dibanding tebak-tebak konfigurasi.
