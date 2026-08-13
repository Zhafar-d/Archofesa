# Railway Deployment - Panduan Perbaikan 500 Error

## ✅ Yang Sudah Diperbaiki
1. ✅ Debug routes dihapus dari `routes/web.php`
2. ✅ File `public/setup-admin.php` dihapus
3. ✅ Duplikat `</div>` di `admin/kamar/index.blade.php` diperbaiki
4. ✅ View `admin/kamar/edit.blade.php` menggunakan `getRawOriginal()` untuk form values

## 🔧 Yang Perlu Dilakukan di Railway Dashboard

### 1. Enable APP_DEBUG untuk melihat error lengkap
Masuk ke Railway dashboard → Variables → tambahkan/ubah:
```
APP_DEBUG=true
```
Simpan dan redeploy. Setelah itu akses `/admin/kamar/{id}/edit` lagi dan lihat error message lengkapnya.

### 2. Update railway.json (Manual via Railway Dashboard atau Git)
File `railway.json` perlu diupdate untuk menambahkan `cache:clear`:
```json
{
  "$schema": "https://railway.com/railway.schema.json",
  "deploy": {
    "releaseCommand": "php artisan cache:clear && php artisan config:clear && php artisan route:clear && php artisan view:clear && php artisan migrate --force && php artisan storage:link"
  }
}
```

### 3. Pastikan Environment Variables Lengkap di Railway
Cek apakah semua variable ini sudah ada di Railway dashboard:
- `APP_KEY` (generate ulang: `php artisan key:generate --show`)
- `APP_ENV=production`
- `APP_URL` (URL Railway production: https://archofesa-production.up.railway.app)
- `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- `SESSION_DRIVER=database` (karena file-based session tidak persistent di Railway)
- `CACHE_STORE=database` (karena file-based cache tidak persistent)
- `FILESYSTEM_DISK=local` (sampai Cloudinary disetup)
- `MIDTRANS_SERVER_KEY`, `MIDTRANS_CLIENT_KEY`, `MIDTRANS_IS_PRODUCTION=false`
- Firebase credentials (semua FIREBASE_* dan VITE_FIREBASE_*)

### 4. Build Vite Assets di Railway
Railway perlu build Vite assets. Tambahkan build command di Railway:
```
npm install && npm run build && php artisan config:clear && php artisan route:clear
```

Atau buat file `nixpacks.toml` di root project:
```toml
[phases.setup]
nixPkgs = ['nodejs', 'php82', 'php82Packages.composer']

[phases.install]
cmds = ['composer install --no-dev --optimize-autoloader', 'npm install']

[phases.build]
cmds = ['npm run build']

[start]
cmd = "php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan serve --host=0.0.0.0 --port=${PORT:-8000}"
```

### 5. Jalankan Manual Commands di Railway Shell
Setelah deploy, buka Railway Shell dan jalankan:
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan storage:link
```

## 🐛 Kemungkinan Penyebab 500 Error

### A. Route Cache Stale
Route cache di production mungkin masih memuat route lama atau debug routes.
**Solusi**: Jalankan `php artisan route:clear` di Railway shell

### B. Vite Manifest Missing
Layout menggunakan `@vite(['resources/css/app.css', 'resources/js/app.js'])` tapi manifest tidak ada.
**Solusi**: Build assets dengan `npm run build` sebelum deploy

### C. Storage Symlink Tidak Ada
`getRawOriginal('image_url')` mengakses storage yang symlinknya belum dibuat.
**Solusi**: Jalankan `php artisan storage:link` di Railway shell

### D. Session/Cache Driver Incompatible
File-based session/cache tidak persistent di Railway (ephemeral storage).
**Solusi**: Ubah ke database driver di Railway env:
```
SESSION_DRIVER=database
CACHE_STORE=database
```

### E. Middleware/Auth Issue
Middleware `role:admin` mungkin gagal karena session tidak tersimpan dengan benar.
**Solusi**: Pastikan SESSION_DRIVER=database dan user sudah login

## 📋 Checklist Deployment

- [ ] Push semua perubahan ke GitHub
- [ ] Railway auto-deploy dari GitHub push
- [ ] Set APP_DEBUG=true di Railway dashboard
- [ ] Update railway.json dengan cache:clear
- [ ] Pastikan npm run build berjalan di Railway
- [ ] Set SESSION_DRIVER=database di Railway
- [ ] Set CACHE_STORE=database di Railway
- [ ] Buka Railway shell dan jalankan cache clear commands
- [ ] Akses /admin/kamar/{id}/edit dan lihat error lengkap
- [ ] Fix error berdasarkan message yang muncul
- [ ] Set APP_DEBUG=false setelah selesai

## 🚀 Langkah Deployment Berikutnya

Setelah edit page berfungsi:

1. **Setup Cloudinary untuk Persistent Storage**
   - Install package: `composer require cloudinary-labs/cloudinary-laravel`
   - Tambahkan credentials Cloudinary ke Railway env
   - Update `config/filesystems.php` dengan disk cloudinary
   - Update Room model untuk menggunakan cloudinary disk

2. **Optimize Production**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. **Setup Monitoring**
   - Enable Railway logs monitoring
   - Setup error tracking (Sentry, Bugsnag, dll)

## 🆘 Troubleshooting

Jika masih 500 error setelah semua langkah:
1. Akses Railway logs: Railway Dashboard → Deployments → View Logs
2. Lihat Laravel logs: Railway Shell → `tail -f storage/logs/laravel.log`
3. Test route isolated: buat route `/test-room` yang hanya load Room model tanpa view
4. Test view isolated: buat route `/test-edit-view` yang hanya render view dengan dummy data

## 📝 Catatan Penting

- Storage di Railway adalah **ephemeral** - semua foto yang diupload akan hilang setelah redeploy
- Harus setup Cloudinary atau S3 untuk persistent file storage
- Database tidak ephemeral (persistent), jadi data booking/users aman
- Session harus menggunakan database driver, bukan file
- Cache sebaiknya database atau Redis, bukan file
