# 🐛 Panduan Debugging 500 Error - Step by Step

## Update Terbaru yang Ditambahkan

1. ✅ **5 Diagnostic Routes** untuk testing
2. ✅ **Logging di KamarController** untuk track error
3. ✅ **Custom 500 error page** dengan detail error

---

## 📋 LANGKAH TESTING (Lakukan Berurutan)

### Step 1: Test Basic Functionality
Buka URL ini di browser (ganti dengan URL Railway Anda):

```
https://archofesa-production.up.railway.app/diagnostic/basic
```

**Expected Output**:
```json
{
  "status": "ok",
  "php_version": "8.2.x",
  "laravel_version": "11.x",
  "env": "production"
}
```

✅ **Jika berhasil**: Laravel berjalan dengan baik
❌ **Jika error**: Masalah dengan PHP/Laravel setup

---

### Step 2: Test Database Connection
```
https://archofesa-production.up.railway.app/diagnostic/db
```

**Expected Output**:
```json
{
  "db_connection": "ok",
  "room_count": 18
}
```

✅ **Jika berhasil**: Database connection OK
❌ **Jika error**: Masalah koneksi database - cek Railway DB credentials

---

### Step 3: Test Room Model
```
https://archofesa-production.up.railway.app/diagnostic/room/1
```

**Expected Output**:
```json
{
  "room_found": true,
  "room_code": "K001",
  "price_raw": "1200000.00",
  "image_url_raw": "...",
  "status": "available"
}
```

✅ **Jika berhasil**: Model Room berfungsi
❌ **Jika error**: Lihat error message untuk tau masalahnya

---

### Step 4: Test View Rendering
```
https://archofesa-production.up.railway.app/diagnostic/view-test
```

**Expected Output**:
```
View rendered OK - Length: 12000 bytes
```

✅ **Jika berhasil**: View edit.blade.php bisa di-render
❌ **Jika error**: Masalah di view (kemungkinan Vite atau syntax error)

**Jika error, perhatikan**:
- `error`: Message error spesifik
- `file`: File mana yang error
- `line`: Baris berapa yang error

---

### Step 5: Test Authentication
Login dulu sebagai admin, lalu buka:
```
https://archofesa-production.up.railway.app/diagnostic/auth-test
```

**Expected Output**:
```json
{
  "authenticated": true,
  "user_id": 1,
  "user_email": "admin@archofesa.test",
  "user_role": "admin"
}
```

✅ **Jika berhasil**: Authentication berfungsi
❌ **Jika error 401**: Session tidak tersimpan (problem SESSION_DRIVER)

---

### Step 6: Test Actual Edit Page
Setelah semua diagnostic pass, test halaman edit:
```
https://archofesa-production.up.railway.app/admin/kamar/1/edit
```

**Jika masih 500**:
- Custom 500 page akan muncul dengan detail error
- Diagnostic links tersedia di halaman tersebut

---

## 🔍 Analisis Hasil

### Scenario A: Step 1-3 OK, Step 4 Error (View Test Gagal)
**Masalah**: View rendering error
**Kemungkinan**:
1. Vite manifest not found → perlu `npm run build`
2. Syntax error di blade template
3. Helper function tidak tersedia

**Solusi**:
```bash
# Di Railway Shell
php artisan view:clear
php artisan config:clear
```

**Atau di Railway Settings → Build Command**:
```bash
npm install && npm run build
```

---

### Scenario B: Step 1-4 OK, Step 5 Error (Auth Gagal)
**Masalah**: Session tidak persistent
**Solusi**: Set di Railway Variables:
```
SESSION_DRIVER=database
CACHE_STORE=database
```

---

### Scenario C: Semua Step OK, Edit Page Masih 500
**Masalah**: Middleware atau authorization issue
**Debug**:
1. Cek Railway Logs untuk error spesifik
2. Cek Laravel logs di Railway Shell:
   ```bash
   tail -100 storage/logs/laravel.log
   ```

---

## 📊 Interpretasi Error Messages

### Error: "Vite manifest not found"
```
The Vite manifest file does not exist...
```
**Fix**: Build Vite assets
```bash
npm install && npm run build
```

### Error: "Class 'Log' not found"
```
Class "Log" not found
```
**Fix**: Import statement missing (sudah diperbaiki dengan \Log)

### Error: "Session store not available"
```
Session store not available
```
**Fix**: Set `SESSION_DRIVER=database` di Railway

### Error: "SQLSTATE[HY000]"
```
SQLSTATE[HY000] [2002] Connection refused
```
**Fix**: Database credentials salah - cek Railway DB settings

### Error: "Storage disk [public] does not exist"
```
Disk [public] does not exist
```
**Fix**: Jalankan `php artisan storage:link` di Railway Shell

---

## 🚀 Railway Shell Commands

Kalau perlu akses Railway Shell untuk troubleshooting:

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Create storage symlink
php artisan storage:link

# Check Laravel version
php artisan --version

# Check environment
php artisan env

# Run tinker (interactive)
php artisan tinker
>>> \App\Models\Room::count()
>>> config('app.debug')

# View logs
tail -50 storage/logs/laravel.log

# Check disk space
df -h

# Check permissions
ls -la storage/
ls -la bootstrap/cache/
```

---

## 📝 Yang Harus Dicatat

Saat melakukan testing, catat hasilnya:

- [ ] Step 1 (Basic): ✅ / ❌ - Error: _______________
- [ ] Step 2 (DB): ✅ / ❌ - Error: _______________
- [ ] Step 3 (Room Model): ✅ / ❌ - Error: _______________
- [ ] Step 4 (View Test): ✅ / ❌ - Error: _______________
- [ ] Step 5 (Auth Test): ✅ / ❌ - Error: _______________
- [ ] Step 6 (Edit Page): ✅ / ❌ - Error: _______________

**Share hasil testing ini** supaya kita bisa identify masalah spesifiknya!

---

## 🎯 Target

Setelah semua step pass:
- Edit page berfungsi normal
- Admin bisa mengubah harga kamar
- Foto bisa diupload (meski akan hilang setelah redeploy)
- Siap untuk fase berikutnya: **Cloudinary Integration**
