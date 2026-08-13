# 🚨 SOLUSI 500 SERVER ERROR - Railway

## Status: Website menampilkan "500 | SERVER ERROR"

Ini berarti ada masalah di server Railway. Mari kita troubleshoot step by step.

---

## 🔧 SOLUSI BERTAHAP

### STEP 1: Test PHP Bypass Laravel (PALING MUDAH)

Saya sudah buat file test khusus yang bypass Laravel untuk cek apakah PHP berjalan.

**Akses URL ini:**
```
https://archofesa-production.up.railway.app/test-railway.php
```

#### Expected Results:

✅ **Jika muncul JSON response** seperti:
```json
{
  "status": "ok",
  "message": "Railway PHP is working!",
  "php_version": "8.2.x",
  ...
}
```
→ **PHP berjalan OK**, masalahnya di Laravel. Lanjut ke STEP 2.

❌ **Jika 404 atau error lain**:
→ File tidak ke-push ke Railway. Lakukan:
```bash
git add public/test-railway.php
git commit -m "Add railway test file"
git push origin main
```
Tunggu Railway deploy, coba lagi.

❌ **Jika masih 500**:
→ Masalah fundamental di Railway. Perlu cek Railway logs (lihat CARA_CEK_LOGS.txt)

---

### STEP 2: Cek Railway Environment Variables

**Kemungkinan APP_KEY tidak ada/kosong** (ini penyebab 500 paling umum!)

#### Cara Fix:

1. **Di local, generate key baru:**
   ```bash
   php artisan key:generate --show
   ```
   Copy hasil outputnya (format: `base64:xxxxxxx...`)

2. **Tambahkan ke Railway:**
   - Buka Railway Dashboard → Variables
   - Klik "New Variable"
   - Name: `APP_KEY`
   - Value: paste hasil step 1
   - Klik "Add"

3. **Redeploy Railway:**
   Railway akan otomatis redeploy setelah variable ditambahkan

4. **Test lagi website**

---

### STEP 3: Set APP_DEBUG=true (Sementara)

Untuk melihat error spesifiknya:

1. Railway Dashboard → Variables
2. Tambahkan atau ubah:
   - Name: `APP_DEBUG`
   - Value: `true`
3. Tunggu redeploy
4. Akses website lagi
5. **Screenshot error lengkap yang muncul**
6. Kirim screenshot ke saya

**⚠️ PENTING**: Set kembali ke `false` setelah masalah solved!

---

### STEP 4: Pastikan Build Command Benar

Railway perlu build Vite assets.

1. **Railway Dashboard → Settings → Build Command**
2. Set menjadi:
   ```bash
   npm install && npm run build && composer install --optimize-autoloader --no-dev
   ```

3. **Railway Dashboard → Settings → Start Command**
4. Pastikan ada:
   ```bash
   php artisan serve --host=0.0.0.0 --port=$PORT
   ```

5. **Redeploy** (klik "Redeploy" atau push ke GitHub)

---

### STEP 5: Jalankan Migration Manual

Mungkin migration belum jalan.

1. **Buka Railway Shell**
2. **Jalankan commands:**
   ```bash
   php artisan migrate:status
   ```
   Lihat apakah ada migration yang belum jalan (status "No")

3. **Jalankan migration:**
   ```bash
   php artisan migrate --force
   ```

4. **Test website lagi**

---

## 📋 CHECKLIST ENVIRONMENT VARIABLES

Pastikan variable berikut ADA di Railway Variables:

### Required:
- [ ] `APP_KEY` (format: base64:xxxx)
- [ ] `APP_ENV` (value: production)
- [ ] `APP_URL` (value: https://archofesa-production.up.railway.app)
- [ ] `DB_CONNECTION` (value: mysql)
- [ ] `DB_HOST` (dari Railway MySQL service)
- [ ] `DB_PORT` (dari Railway MySQL service)
- [ ] `DB_DATABASE` (dari Railway MySQL service)
- [ ] `DB_USERNAME` (dari Railway MySQL service)
- [ ] `DB_PASSWORD` (dari Railway MySQL service)

### Recommended:
- [ ] `SESSION_DRIVER` (value: database)
- [ ] `CACHE_STORE` (value: database)
- [ ] `LOG_CHANNEL` (value: stack)
- [ ] `FILESYSTEM_DISK` (value: local)

### Optional (Midtrans):
- [ ] `MIDTRANS_SERVER_KEY`
- [ ] `MIDTRANS_CLIENT_KEY`
- [ ] `MIDTRANS_IS_PRODUCTION` (value: false)

---

## 🐛 KEMUNGKINAN ERROR & SOLUSI

### Error: "No application encryption key has been specified"
**Solusi**: Set APP_KEY di Railway Variables (lihat STEP 2)

### Error: "SQLSTATE[HY000] [2002] Connection refused"
**Solusi**: 
1. Pastikan Railway MySQL service sudah di-provision
2. Variables database (DB_HOST, DB_PORT, dll) otomatis dari MySQL service
3. Jangan manual input, use Railway's ${{MySQL.XXX}} references

### Error: "Class 'Illuminate\Foundation\Application' not found"
**Solusi**: composer install tidak jalan
1. Railway Settings → Build Command
2. Tambahkan: `composer install --optimize-autoloader --no-dev`

### Error: "The Mix manifest does not exist" atau "Vite manifest not found"
**Solusi**: 
1. Railway Settings → Build Command
2. Tambahkan: `npm install && npm run build`

### Error: "Failed opening required bootstrap/../vendor/autoload.php"
**Solusi**: 
1. Cek Railway Settings → Root Directory (harusnya kosong atau "/")
2. Start Command harusnya: `php artisan serve --host=0.0.0.0 --port=$PORT`

---

## 🔍 DEBUGGING WORKFLOW

Ikuti urutan ini:

1. ✅ Test `/test-railway.php` → apakah PHP jalan?
2. ✅ Cek Railway Variables → apakah APP_KEY ada?
3. ✅ Set APP_DEBUG=true → lihat error spesifik
4. ✅ Cek Railway Logs → lihat error saat deploy/runtime
5. ✅ Fix berdasarkan error message
6. ✅ Set APP_DEBUG=false kembali

---

## 📸 INFO YANG PERLU DIKIRIM

Untuk troubleshooting lebih lanjut, kirim:

1. **Screenshot hasil akses `/test-railway.php`**
2. **Screenshot Railway Variables list** (boleh disensor value sensitif)
3. **Screenshot Railway Deployment Logs** (bagian error)
4. **Screenshot dengan APP_DEBUG=true** (error message lengkap)

Dengan info ini, saya bisa kasih solusi spesifik!

---

## 🚀 AFTER FIX

Setelah 500 error solved:
1. Test homepage
2. Test login admin
3. Test halaman edit kamar
4. Setup Cloudinary untuk persistent storage

---

**PRIORITAS SEKARANG**: 
1. Akses `/test-railway.php`
2. Generate dan set APP_KEY
3. Set APP_DEBUG=true
4. Screenshot error dan kirim
