# Dokumentasi Lengkap Diagram Sistem & Perancangan ARCHOFESA KOST

Dokumen ini merupakan panduan perancangan sistem informasi lengkap untuk **ARCHOFESA KOST**, yang mencakup:
1. **DFD Level 0 (*Context Diagram / Diagram Konteks*)**
2. **DFD Level 1 (*Dekomposisi Proses Utama & Data Store*)**
3. **DFD Level 2 (*Dekomposisi Detail Proses 3.0 Transaksi Reservasi*)**
4. **Diagram Kasus Penggunaan (*Use Case Diagram*)**
5. **Diagram Alur Bisnis (*Cross-Functional Swimlane Flowchart*)**
6. **Diagram Status Mesin (*Booking Lifecycle State Machine*)**
7. **Model Data Konseptual (*Conceptual Data Model - CDM*)**
8. **Model Data Fisik (*Physical Data Model - PDM / Relasi Database MySQL*)**

---

## 1. DFD Level 0 (Diagram Konteks / Context Diagram)

Diagram Konteks mendefinisikan batas sistem (*system boundary*) secara menyeluruh, 1 proses sentral `0.0`, dan interaksi dengan 5 entitas eksternal (*terminator*).

```mermaid
flowchart TD
    classDef sys fill:#1e293b,stroke:#0f172a,stroke-width:2px,color:#ffffff;
    classDef ext fill:#ffffff,stroke:#000000,stroke-width:1.5px,color:#000000;

    SYS(("0.0 SISTEM INFORMASI<br>PENGELOLAAN & RESERVASI<br>ARCHOFESA KOST")):::sys

    CUST["PENYEWA<br>(CUSTOMER)"]:::ext
    ADMIN["ADMINISTRATOR<br>(ADMIN)"]:::ext
    OWNER["PEMILIK KOS<br>(OWNER)"]:::ext
    MIDTRANS["PAYMENT GATEWAY<br>(MIDTRANS)"]:::ext
    AUTH_EXT["GOOGLE OAUTH /<br>FIREBASE AUTH"]:::ext

    CUST -->|"1. Data Akun & Profil<br>2. Form Booking & Tgl Masuk<br>3. Pengajuan Perpanjang Sewa<br>4. Ulasan & Rating"| SYS
    SYS -->|"1. Katalog Kamar & Fasilitas<br>2. Invoice & Snap Token<br>3. Status Tracking Realtime<br>4. Bukti Reservasi Digital<br>5. Live Countdown Sisa Sewa"| CUST

    ADMIN -->|"1. Master Kamar (CRUD/Harga/Foto)<br>2. Verifikasi Booking & Tagihan<br>3. Teruskan Booking ke Owner<br>4. Update Jatuh Tempo"| SYS
    SYS -->|"1. Notifikasi Booking Baru<br>2. Laporan Status Pembayaran<br>3. Data Okupansi & Statistik<br>4. Daftar Penghuni & Jatuh Tempo"| ADMIN

    OWNER -->|"1. Konfirmasi Kesiapan Kamar (SIAP_HUNI)<br>2. Catatan Kunci & Kamar"| SYS
    SYS -->|"1. Notifikasi Booking Lunas Siap Huni<br>2. Laporan Pendapatan Bersih<br>3. Statistik Okupansi & Hunian"| OWNER

    SYS -->|"1. Order ID, Gross Amount, Item Info<br>2. Request Snap Token API"| MIDTRANS
    MIDTRANS -->|"1. Webhook (Settlement/Cancel/Expire)<br>2. Status & No. Referensi"| SYS

    SYS -->|"1. Request Redirect OAuth SSO"| AUTH_EXT
    AUTH_EXT -->|"1. Access Token & Profil (Email/Nama)"| SYS
```

---

## 2. DFD Level 1 (Dekomposisi Proses Utama)

DFD Level 1 memecah Proses `0.0` menjadi **6 Sub-Proses Utama** dan menghubungkannya dengan **5 Data Store Database**.

```mermaid
flowchart TD
    classDef proc fill:#ffffff,stroke:#000000,stroke-width:1.5px,color:#000000;
    classDef ds fill:#f8fafc,stroke:#000000,stroke-width:1px,color:#000000;
    classDef ext fill:#ffffff,stroke:#000000,stroke-width:2px,color:#000000;

    CUST["PENYEWA (CUSTOMER)"]:::ext
    ADMIN["ADMIN (ADMINISTRATOR)"]:::ext
    OWNER["OWNER (PEMILIK)"]:::ext
    MIDTRANS["MIDTRANS GATEWAY"]:::ext

    P1(("1.0 Manajemen<br>Autentikasi & Profil")):::proc
    P2(("2.0 Manajemen<br>Master Kamar")):::proc
    P3(("3.0 Transaksi<br>Pemesanan Kamar")):::proc
    P4(("4.0 Pembayaran &<br>Webhook Gateway")):::proc
    P5(("5.0 Validasi &<br>Konfirmasi Owner")):::proc
    P6(("6.0 Monitoring Hunian,<br>Countdown & Laporan")):::proc

    D1[("D1: users")]:::ds
    D2[("D2: rooms")]:::ds
    D3[("D3: bookings")]:::ds
    D4[("D4: payments")]:::ds
    D5[("D5: konfirmasi_owner")]:::ds

    %% Aliran Proses 1.0
    CUST -->|"Data Akun & Profil"| P1
    P1 -->|"Simpan/Update Akun"| D1

    %% Aliran Proses 2.0
    ADMIN -->|"CRUD Data Kamar"| P2
    P2 -->|"Update Master Kamar"| D2

    %% Aliran Proses 3.0
    CUST -->|"Pilih Kamar & Tgl Masuk"| P3
    D2 -.->|"Kueri Ketersediaan"| P3
    P3 -->|"Simpan Booking (pending)"| D3
    ADMIN -->|"Verifikasi & Tagihan"| P3

    %% Aliran Proses 4.0
    CUST -->|"Bayar via Snap"| P4
    P4 <-->|"Snap Token & Webhook Settlement"| MIDTRANS
    P4 -->|"Simpan Lunas"| D4
    P4 -->|"Update: dibayar"| D3

    %% Aliran Proses 5.0
    OWNER -->|"Konfirmasi Kesiapan Kamar"| P5
    P5 -->|"Simpan Log Konfirmasi"| D5
    P5 -->|"Update: SIAP_HUNI"| D3

    %% Aliran Proses 6.0
    D3 -.->|"Baca Tgl Masuk & Masa Sewa"| P6
    P6 -.->|"Live Countdown Sisa Sewa"| CUST
    P6 -.->|"Laporan Okupansi & Keuangan"| ADMIN
    P6 -.->|"Laporan Pendapatan Bersih"| OWNER
```

---

## 3. DFD Level 2 (Dekomposisi Proses 3.0 Transaksi Pemesanan)

DFD Level 2 merinci alur transaksi reservasi kamar dari pemilihan kamar, input formulir, validasi admin, hingga penerbitan tagihan.

```mermaid
flowchart TD
    classDef proc fill:#ffffff,stroke:#000000,stroke-width:1.5px,color:#000000;
    classDef ds fill:#f8fafc,stroke:#000000,stroke-width:1px,color:#000000;
    classDef ext fill:#ffffff,stroke:#000000,stroke-width:2px,color:#000000;

    CUST["PENYEWA (CUSTOMER)"]:::ext
    ADMIN["ADMIN (ADMINISTRATOR)"]:::ext

    P31(("3.1 Pilih Kamar &<br>Cek Ketersediaan")):::proc
    P32(("3.2 Input Data Sewa<br>& Tanggal Masuk")):::proc
    P33(("3.3 Verifikasi Berkas<br>& Validasi Admin")):::proc
    P34(("3.4 Penerbitan Tagihan<br>& Status Menunggu Bayar")):::proc

    D2[("D2: rooms")]:::ds
    D3[("D3: bookings")]:::ds

    CUST -->|"1. Kueri Pilih Kamar"| P31
    D2 -.->|"2. Status Ketersediaan (available)"| P31
    P31 -->|"3. Kamar Terpilih"| P32
    CUST -->|"4. Form Booking & Tgl Masuk"| P32
    P32 -->|"5. Simpan (status: pending)"| D3

    D3 -.->|"6. Data Booking Pending"| P33
    ADMIN -->|"7. Aksi Verifikasi / Tolak"| P33
    P33 -->|"8. Booking Terverifikasi"| P34
    P34 -->|"9. Update (status: menunggu_pembayaran)"| D3
    P34 -.->|"10. Notifikasi Tagihan & Tombol Bayar"| CUST
```

---

## 4. Model Data Konseptual (*Conceptual Data Model - CDM*)

```mermaid
erDiagram
    USER ||--o{ BOOKING : "mengajukan (1,1 to 0,N)"
    ROOM ||--o{ BOOKING : "dipesan dalam (1,1 to 0,N)"
    USER ||--o{ PAYMENT : "melakukan (1,1 to 0,N)"
    BOOKING ||--o{ PAYMENT : "menghasilkan (0,1 to 0,N)"
    USER ||--o{ REVIEW : "menulis (1,1 to 0,N)"
    BOOKING ||--o| REVIEW : "dinilai dalam (0,1 to 0,1)"
    BOOKING ||--o| OWNER_CONFIRMATION : "dikonfirmasi dalam (1,1 to 0,1)"
    USER ||--o{ OWNER_CONFIRMATION : "divalidasi oleh (1,1 to 0,N)"

    USER {
        Integer id PK
        String name
        String email UK
        String password
        String role
        String phone
        Text address
        String avatar
        String google_id
        Datetime email_verified_at
    }

    ROOM {
        Integer id PK
        String room_code UK
        String size
        Money price_monthly
        String status
        Text description
        String image_url
    }

    BOOKING {
        Integer id PK
        String room_code
        Money monthly_rate
        String status
        String payment_method
        String payment_status
        Date move_in_date
        Date move_out_date
        Text notes
        Text admin_notes
        Text owner_notes
    }

    PAYMENT {
        Integer id PK
        Money amount
        String currency
        String status
        String payment_method
        Datetime paid_at
        String reference
        String midtrans_order_id
        Text gateway_response
    }

    REVIEW {
        Integer id PK
        Integer rating
        Text comment
        String status
    }

    OWNER_CONFIRMATION {
        Integer id PK
        String status
        Datetime confirmed_at
    }
```

---

## 5. Model Data Fisik (*Physical Data Model - PDM / MySQL*)

```mermaid
erDiagram
    users ||--o{ bookings : "user_id (CASCADE)"
    rooms ||--o{ bookings : "room_id (SET NULL)"
    users ||--o{ payments : "user_id (CASCADE)"
    bookings ||--o{ payments : "booking_id (SET NULL)"
    users ||--o{ reviews : "user_id (CASCADE)"
    bookings ||--o| reviews : "booking_id (SET NULL)"
    bookings ||--o| konfirmasi_owner : "booking_id (CASCADE)"
    users ||--o{ konfirmasi_owner : "owner_id (CASCADE)"

    users {
        BIGINT_UNSIGNED id PK "AUTO_INCREMENT"
        VARCHAR_255 name "NOT NULL"
        VARCHAR_255 email UK "NOT NULL"
        VARCHAR_255 password "NULL"
        VARCHAR_255 role "DEFAULT 'customer'"
        VARCHAR_20 phone "NULL"
        TEXT address "NULL"
        VARCHAR_255 avatar "NULL"
        VARCHAR_255 google_id UK "NULL"
        TIMESTAMP email_verified_at "NULL"
        TIMESTAMP created_at "NULL"
        TIMESTAMP updated_at "NULL"
    }

    rooms {
        BIGINT_UNSIGNED id PK "AUTO_INCREMENT"
        VARCHAR_255 room_code UK "NOT NULL"
        VARCHAR_255 size "DEFAULT '3x4m'"
        DECIMAL_10_2 price_monthly "DEFAULT 0.00"
        VARCHAR_255 status "DEFAULT 'available'"
        TEXT description "NULL"
        VARCHAR_255 image_url "NULL"
        TIMESTAMP created_at "NULL"
        TIMESTAMP updated_at "NULL"
    }

    bookings {
        BIGINT_UNSIGNED id PK "AUTO_INCREMENT"
        BIGINT_UNSIGNED user_id FK "NOT NULL"
        BIGINT_UNSIGNED room_id FK "NULL"
        VARCHAR_255 room_code "NULL"
        DECIMAL_10_2 monthly_rate "DEFAULT 0.00"
        VARCHAR_255 status "DEFAULT 'pending'"
        VARCHAR_255 payment_method "NULL"
        VARCHAR_255 payment_status "DEFAULT 'pending'"
        DATE move_in_date "NULL"
        DATE move_out_date "NULL"
        TEXT notes "NULL"
        TEXT admin_notes "NULL"
        TEXT owner_notes "NULL"
        TIMESTAMP created_at "NULL"
        TIMESTAMP updated_at "NULL"
    }

    payments {
        BIGINT_UNSIGNED id PK "AUTO_INCREMENT"
        BIGINT_UNSIGNED user_id FK "NOT NULL"
        BIGINT_UNSIGNED booking_id FK "NULL"
        DECIMAL_10_2 amount "DEFAULT 0.00"
        VARCHAR_255 currency "DEFAULT 'IDR'"
        VARCHAR_255 status "DEFAULT 'pending'"
        VARCHAR_255 payment_method "NULL"
        DATETIME paid_at "NULL"
        VARCHAR_255 reference "NULL"
        VARCHAR_255 midtrans_order_id "NULL"
        TEXT gateway_response "NULL"
        TIMESTAMP created_at "NULL"
        TIMESTAMP updated_at "NULL"
    }

    reviews {
        BIGINT_UNSIGNED id PK "AUTO_INCREMENT"
        BIGINT_UNSIGNED user_id FK "NOT NULL"
        BIGINT_UNSIGNED booking_id FK "NULL"
        TINYINT rating "DEFAULT 5"
        TEXT comment "NULL"
        VARCHAR_255 status "DEFAULT 'approved'"
        TIMESTAMP created_at "NULL"
        TIMESTAMP updated_at "NULL"
    }

    konfirmasi_owner {
        BIGINT_UNSIGNED id PK "AUTO_INCREMENT"
        BIGINT_UNSIGNED booking_id FK "NOT NULL"
        BIGINT_UNSIGNED owner_id FK "NOT NULL"
        VARCHAR_255 status "DEFAULT 'dikonfirmasi'"
        TIMESTAMP confirmed_at "NULL"
        TIMESTAMP created_at "NULL"
        TIMESTAMP updated_at "NULL"
    }
```

---

## 6. Daftar File XML Draw.io Siap Pakai
- 📄 **[DFD_LEVEL_DIAGRAM.xml](file:///c:/xampp/htdocs/Archofesa/DFD_LEVEL_DIAGRAM.xml)** — Multi-Tab DFD Lengkap:
  - **Tab 1:** DFD Level 0 (Context Diagram)
  - **Tab 2:** DFD Level 1 (6 Sub-Proses Utama & 5 Data Store)
  - **Tab 3:** DFD Level 2 (Dekomposisi Proses 3.0 Reservasi)
- 📄 **[CDM_DIAGRAM.xml](file:///c:/xampp/htdocs/Archofesa/CDM_DIAGRAM.xml)** — Conceptual Data Model (Format Tabel B&W).
- 📄 **[CDM_PDM_DIAGRAM.xml](file:///c:/xampp/htdocs/Archofesa/CDM_PDM_DIAGRAM.xml)** — Multi-Tab CDM & PDM MySQL.
- 📄 **[USE_CASE_DIAGRAM.xml](file:///c:/xampp/htdocs/Archofesa/USE_CASE_DIAGRAM.xml)** — Use Case Diagram.
