# 🚀 Panduan Setup Midtrans di Railway

## ❌ MASALAH SAAT INI
```
The ServerKey/ClientKey is null, You need to set the server-key from Config
```

Error ini muncul karena Railway belum memiliki environment variables untuk Midtrans.

---

## ✅ SOLUSI - Tambahkan Environment Variables di Railway

### 1️⃣ Buka Railway Dashboard
- Masuk ke: https://railway.app
- Pilih project: **archofesa-production**

### 2️⃣ Tambahkan Variables Midtrans
Klik tab **"Variables"** di Railway project, lalu tambahkan 3 variables berikut:

```
MIDTRANS_SERVER_KEY=SB-Mid-server-037y4EYaXZzwCcd319T1oKiS
MIDTRANS_CLIENT_KEY=SB-Mid-client-82FiFvkuZsSQ_EDk
MIDTRANS_IS_PRODUCTION=false
```

**CARA MENAMBAHKAN:**
1. Klik tombol **"+ New Variable"** atau **"Add Variable"**
2. Masukkan nama variable (contoh: `MIDTRANS_SERVER_KEY`)
3. Masukkan value (contoh: `SB-Mid-server-037y4EYaXZzwCcd319T1oKiS`)
4. Klik **"Add"**
5. Ulangi untuk 3 variables di atas

### 3️⃣ Deploy Ulang (Automatic)
Setelah variables ditambahkan:
- Railway akan **otomatis re-deploy** aplikasi
- Tunggu hingga deployment selesai (biasanya 2-5 menit)
- Cek status deployment di tab **"Deployments"**

### 4️⃣ Verifikasi Setup
Setelah deployment selesai, test payment dengan cara:
1. Login sebagai user (bukan admin/owner)
2. Buka halaman **"Pembayaran Saya"** atau **Customer Dashboard**
3. Klik tombol **"Bayar"** pada payment yang pending
4. Jika berhasil, akan muncul **popup Midtrans Snap**

---

## 🔧 FILE YANG SUDAH DIPERBAIKI

### ✅ `nixpacks.toml` - Auto Clear Cache
File ini sudah diupdate agar setiap kali Railway start, config cache akan otomatis di-clear:

```toml
[start]
cmd = "php artisan config:clear && php artisan cache:clear && php artisan serve --host=0.0.0.0 --port=${PORT:-8000}"
```

**Ini artinya:** Kamu tidak perlu manual run command di Railway Shell lagi!

---

## 📋 CHECKLIST

- [ ] Buka Railway Dashboard
- [ ] Tambahkan variable: `MIDTRANS_SERVER_KEY`
- [ ] Tambahkan variable: `MIDTRANS_CLIENT_KEY`
- [ ] Tambahkan variable: `MIDTRANS_IS_PRODUCTION`
- [ ] Tunggu auto re-deploy selesai
- [ ] Push `nixpacks.toml` yang baru ke GitHub
- [ ] Tunggu Railway deploy ulang
- [ ] Test payment page

---

## 🧪 TEST SETELAH SETUP

### Test 1: Cek Config di Railway
Buka Railway Shell dan jalankan:
```bash
php artisan tinker
```

Lalu ketik:
```php
config('midtrans.server_key')
config('midtrans.client_key')
config('midtrans.is_production')
```

**Expected Output:**
```
"SB-Mid-server-037y4EYaXZzwCcd319T1oKiS"
"SB-Mid-client-82FiFvkuZsSQ_EDk"
false
```

Jika masih `null`, berarti variables belum di-set atau cache belum clear.

### Test 2: Test Payment Flow
1. Login sebagai user regular
2. Pastikan ada booking dengan payment pending
3. Klik tombol "Bayar"
4. Harus muncul popup Midtrans (bukan error 500)

---

## 🐛 TROUBLESHOOTING

### Masalah: Masih error "ServerKey is null" setelah add variables
**Solusi:**
1. Pastikan Railway sudah re-deploy setelah add variables
2. Cek di Railway tab **"Variables"** apakah 3 variables sudah ada
3. Buka Railway Shell dan run:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```
4. Refresh browser dan test lagi

### Masalah: Payment error tapi config sudah benar
**Solusi:**
1. Cek di Midtrans Dashboard apakah ServerKey masih aktif
2. Pastikan `MIDTRANS_IS_PRODUCTION=false` (untuk sandbox mode)
3. Cek Railway logs untuk error detail:
   ```bash
   # Di Railway Shell
   tail -f storage/logs/laravel.log
   ```

### Masalah: Railway tidak auto re-deploy
**Solusi:**
1. Klik **"Manual Deploy"** di Railway
2. Atau push commit baru ke GitHub (contoh: update README)

---

## 📞 BANTUAN

Jika masih ada masalah:
1. Screenshot error page
2. Screenshot Railway Variables tab
3. Paste Railway logs dari deployment
4. Tanya ke AI assistant dengan informasi di atas

---

## ✨ SETELAH SELESAI

Setelah setup berhasil, payment flow akan berfungsi:
1. User bisa klik "Bayar" tanpa error
2. Popup Midtrans Snap akan muncul
3. User bisa pilih metode pembayaran (Credit Card, GoPay, Bank Transfer, dll)
4. Setelah bayar, status payment akan update otomatis

**Selamat! Sistem payment sudah siap! 🎉**
