# 🔧 Fix 500 Error di Halaman Edit Kamar

## 📊 Status Saat Ini
- ✅ Homepage berfungsi
- ✅ Login berfungsi
- ✅ Dashboard admin berfungsi
- ✅ Halaman list kamar (`/admin/kamar`) berfungsi
- ❌ Halaman edit kamar (`/admin/kamar/{id}/edit`) → **500 Internal Server Error**

## 🎯 Penyebab Masalah

Halaman edit menggunakan layout `layouts.admin` yang memuat:
```blade
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

Railway **belum menjalankan `npm run build`** sehingga file Vite manifest (`public/build/manifest.json`) tidak ada. Ketika Laravel mencoba load Vite assets, terjadi error 500.

---

## ✅ SOLUSI 1: Build Vite Assets di Railway (RECOMMENDED)

Saya sudah membuat file `nixpacks.toml` yang akan otomatis build Vite assets saat deploy.

### Langkah:
1. **File sudah di-commit** (nixpacks.toml)
2. **Push ke GitHub** (akan saya lakukan)
3. **Railway akan otomatis:**
   - Install Node.js dependencies (`npm ci`)
   - Build Vite assets (`npm run build`)
   - Deploy aplikasi

### Setelah Deploy:
Test halaman edit lagi. Seharusnya sudah berfungsi!

---

## ✅ SOLUSI 2: Diagnostic Vite (Cek Dulu Masalahnya)

Sebelum push, kita bisa cek dulu apakah Vite manifest ada atau tidak.

### Test URL ini setelah push:
```
https://archofesa-production.up.railway.app/diagnostic/vite-check
```

**Expected Output:**
```json
{
  "build_directory_exists": true,
  "manifest_exists": true,
  "manifest_path": "/app/public/build/manifest.json",
  "build_files": ["manifest.json", "app-xxx.css", "app-xxx.js"],
  "npm_build_needed": false
}
```

Jika `manifest_exists: false` → Vite belum di-build, perlu nixpacks.toml

---

## ✅ SOLUSI 3: Manual Build Command di Railway (Alternatif)

Jika nixpacks.toml tidak bekerja:

### 1. Buka Railway Dashboard
- Settings → Deploy
- Tambahkan **Build Command**:
  ```bash
  npm ci && npm run build && composer install --optimize-autoloader --no-dev
  ```

### 2. Tambahkan **Start Command**:
  ```bash
  php artisan serve --host=0.0.0.0 --port=$PORT
  ```

### 3. Redeploy
Railway akan otomatis redeploy dengan command baru.

---

## ✅ SOLUSI 4: Fallback - Gunakan CDN Tailwind (Quick Fix)

Jika Vite build gagal, kita bisa sementara gunakan CDN Tailwind untuk testing.

### Edit `resources/views/layouts/admin.blade.php`:

Ganti:
```blade
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

Dengan:
```blade
@if(config('app.env') === 'production' && !file_exists(public_path('build/manifest.json')))
    {{-- Fallback: CDN Tailwind untuk production tanpa Vite build --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              gold: '#c9a227',
            }
          }
        }
      }
    </script>
@else
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@endif
```

**Catatan:** Ini hanya temporary fix untuk testing. Sebaiknya tetap build Vite dengan SOLUSI 1.

---

## 🔍 Troubleshooting

### Q: Setelah deploy masih 500 error
**A:** Jalankan diagnostic:
1. Akses `/diagnostic/vite-check`
2. Cek apakah `manifest_exists: true`
3. Jika false, berarti npm run build belum jalan

### Q: nixpacks.toml tidak bekerja
**A:** Gunakan SOLUSI 3 (manual build command di Railway dashboard)

### Q: Build berhasil tapi masih 500
**A:** Mungkin ada error lain. Set `APP_DEBUG=true` di Railway variables untuk melihat error detail.

### Q: Halaman lain juga ikut error setelah deploy
**A:** Jalankan command di Railway Shell:
```bash
php artisan route:clear
php artisan config:clear
php artisan view:clear
```

---

## 📋 Checklist Deploy

Setelah push + deploy, lakukan test ini:

- [ ] Akses `/diagnostic/vite-check` → cek manifest_exists: true
- [ ] Akses `/admin/kamar` → list kamar muncul
- [ ] Klik tombol "Ubah" → halaman edit muncul (bukan 500)
- [ ] Edit harga kamar → simpan → sukses
- [ ] Logout dan login lagi → test lagi

---

## 🎯 Yang Akan Saya Lakukan Sekarang

1. ✅ Commit nixpacks.toml
2. ✅ Commit route diagnostic vite-check
3. ✅ Push ke GitHub
4. ⏳ Railway otomatis deploy (~3-5 menit)
5. ⏳ Anda test `/diagnostic/vite-check`
6. ⏳ Anda test `/admin/kamar/{id}/edit`

---

## 💡 Penjelasan Teknis

**Kenapa Vite penting?**
- Tailwind CSS perlu di-compile dari source ke CSS final
- Vite melakukan bundling dan minifikasi assets
- Tanpa Vite build, browser tidak bisa load CSS/JS

**Kenapa tidak error di local?**
- Di local, Anda jalankan `npm run dev` (Vite dev server)
- Di production, perlu `npm run build` untuk generate static files

**Kenapa halaman lain tidak error?**
- Halaman lain mungkin menggunakan layout berbeda atau tidak pakai Vite
- Atau Vite directive dibungkus try-catch

---

Setelah push, tunggu Railway deploy lalu test halaman edit lagi! 🚀
