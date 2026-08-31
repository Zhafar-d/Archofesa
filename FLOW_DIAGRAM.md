# Dokumentasi Lengkap Diagram Sistem & Perancangan ARCHOFESA KOST

Dokumen ini merupakan panduan perancangan sistem informasi lengkap untuk **ARCHOFESA KOST**, yang mencakup:
1. **Diagram Konteks (*Context Diagram / DFD Level 0*)**
2. **Diagram Kasus Penggunaan (*Use Case Diagram*)**
3. **Diagram Alur Bisnis (*Cross-Functional Swimlane Flowchart*)**
4. **Diagram Status Mesin (*Booking Lifecycle State Machine*)**
5. **Model Data Konseptual (*Conceptual Data Model - CDM*)**
6. **Model Data Fisik (*Physical Data Model - PDM / Relasi Database MySQL*)**

---

## 1. Diagram Konteks (*Context Diagram / DFD Level 0*)

```mermaid
flowchart TD
    classDef sys fill:#1e293b,stroke:#0f172a,stroke-width:3px,color:#ffffff;
    classDef cust fill:#f0f9ff,stroke:#0284c7,stroke-width:2px,color:#0369a1;
    classDef admin fill:#fffbeb,stroke:#d97706,stroke-width:2px,color:#b45309;
    classDef owner fill:#faf5ff,stroke:#7c3aed,stroke-width:2px,color:#6d28d9;
    classDef midtrans fill:#f0fdf4,stroke:#16a34a,stroke-width:2px,color:#15803d;
    classDef auth fill:#f1f5f9,stroke:#64748b,stroke-width:2px,color:#334155;

    SYS(("0.0 SISTEM INFORMASI<br>PENGELOLAAN & RESERVASI<br>ARCHOFESA KOST")):::sys

    CUST["PENYEWA<br>(CUSTOMER)"]:::cust
    ADMIN["ADMINISTRATOR<br>(ADMIN)"]:::admin
    OWNER["PEMILIK KOS<br>(OWNER)"]:::owner
    MIDTRANS["PAYMENT GATEWAY<br>(MIDTRANS)"]:::midtrans
    AUTH_EXT["GOOGLE OAUTH /<br>FIREBASE AUTH"]:::auth

    CUST -->|"1. Data Registrasi & Login<br>2. Data Form Booking & Tgl Masuk<br>3. Data Profil & Nomor Telepon<br>4. Pengajuan Perpanjangan Sewa<br>5. Rating & Ulasan Kamar"| SYS
    SYS -->|"1. Katalog Kamar & Fasilitas<br>2. Rincian Tagihan & Invoice Midtrans<br>3. Status Tracking Real-Time<br>4. Bukti Reservasi Digital<br>5. Live Countdown Sisa Waktu Sewa"| CUST

    ADMIN -->|"1. Data Master Kamar (Harga/Fasilitas/Foto)<br>2. Verifikasi / Penolakan Booking<br>3. Penerbitan Tagihan Pembayaran<br>4. Teruskan Booking Lunas ke Owner<br>5. Update Jatuh Tempo Penghuni"| SYS
    SYS -->|"1. Notifikasi Pengajuan Booking Baru<br>2. Laporan Transaksi Pembayaran<br>3. Statistik Okupansi Kamar Kos<br>4. Daftar Penghuni Aktif & Jatuh Tempo"| ADMIN

    OWNER -->|"1. Konfirmasi Kesiapan Kamar (SIAP_HUNI)<br>2. Catatan & Validasi Kunci Kamar"| SYS
    SYS -->|"1. Notifikasi Booking Lunas Siap Huni<br>2. Laporan Keuangan & Pendapatan Kos<br>3. Laporan Okupansi & Hunian"| OWNER

    SYS -->|"1. Parameter Transaksi (Order ID, Amount)<br>2. Customer & Item Details<br>3. Request Snap Token API"| MIDTRANS
    MIDTRANS -->|"1. Webhook Notification (Settlement/Cancel)<br>2. Status Transaksi & Ref Pembayaran"| SYS

    SYS -->|"1. Permintaan Redirect OAuth"| AUTH_EXT
    AUTH_EXT -->|"1. Token Akses & Profil Akun (Email/Nama/Avatar)"| SYS
```

---

## 2. Diagram Kasus Penggunaan (*Use Case Diagram*)

```mermaid
flowchart LR
    classDef actor fill:#f8fafc,stroke:#334155,stroke-width:2px,color:#0f172a;
    classDef uc fill:#ffffff,stroke:#0284c7,stroke-width:1.5px,color:#0f172a;
    classDef ucAdmin fill:#ffffff,stroke:#d97706,stroke-width:1.5px,color:#0f172a;
    classDef ucOwner fill:#ffffff,stroke:#7c3aed,stroke-width:1.5px,color:#0f172a;
    classDef ucMidtrans fill:#ffffff,stroke:#16a34a,stroke-width:1.5px,color:#0f172a;

    subgraph SYSTEM["BATASAN SISTEM (ARCHOFESA KOST)"]
        UC_AUTH(["Login & Registrasi Akun"]):::uc
        UC_GOOGLE(["Login via Google OAuth"]):::uc
        UC_PROFILE(["Kelola Profil & WhatsApp"]):::uc

        UC_BROWSE(["Melihat Katalog & Detail Kamar"]):::uc
        UC_BOOKING(["Mengajukan Pemesanan Kamar"]):::uc
        UC_TRACK(["Melacak Status Pemesanan Real-Time"]):::uc
        UC_PAY(["Melakukan Pembayaran Online"]):::uc
        UC_SNAP(["Generate Snap Token & API"]):::ucMidtrans
        UC_BUKTI(["Mencetak Bukti Reservasi"]):::uc
        UC_COUNTDOWN(["Memantau Live Countdown Sisa Sewa"]):::uc
        UC_EXTEND(["Mengajukan Perpanjangan Sewa"]):::uc
        UC_REVIEW(["Memberikan Rating & Ulasan"]):::uc

        UC_KAMAR(["Mengelola Data Kamar (CRUD/Foto)"]):::ucAdmin
        UC_VERIFY(["Memverifikasi Booking"]):::ucAdmin
        UC_INVOICE(["Menerbitkan Tagihan"]):::ucAdmin
        UC_FORWARD(["Meneruskan Booking ke Owner"]):::ucAdmin
        UC_PENGHUNI(["Kelola Data Penghuni & Jatuh Tempo"]):::ucAdmin
        UC_TX(["Memantau Riwayat Transaksi"]):::ucAdmin

        UC_REVIEW_OWNER(["Meninjau Booking Lunas"]):::ucOwner
        UC_CONFIRM(["Konfirmasi Kesiapan Kamar (Siap Huni)"]):::ucOwner
        UC_REPORT_FIN(["Melihat Laporan Pendapatan"]):::ucOwner
        UC_REPORT_OCC(["Melihat Statistik Okupansi"]):::ucOwner
        UC_WEBHOOK(["Webhook Callback Transaksi"]):::ucMidtrans
    end

    CUST(("Penyewa<br>(Customer)")):::actor
    ADMIN(("Administrator")):::actor
    OWNER(("Pemilik Kos<br>(Owner)")):::actor
    MIDTRANS(("Midtrans<br>Gateway")):::actor
    GOOGLE(("Google<br>OAuth")):::actor

    CUST --- UC_AUTH
    CUST --- UC_PROFILE
    CUST --- UC_BROWSE
    CUST --- UC_BOOKING
    CUST --- UC_TRACK
    CUST --- UC_PAY
    CUST --- UC_BUKTI
    CUST --- UC_COUNTDOWN
    CUST --- UC_REVIEW

    GOOGLE --- UC_GOOGLE
    UC_GOOGLE -.->|&laquo;extend&raquo;| UC_AUTH

    ADMIN --- UC_AUTH
    ADMIN --- UC_KAMAR
    ADMIN --- UC_VERIFY
    ADMIN --- UC_INVOICE
    ADMIN --- UC_FORWARD
    ADMIN --- UC_PENGHUNI
    ADMIN --- UC_TX

    OWNER --- UC_AUTH
    OWNER --- UC_REVIEW_OWNER
    OWNER --- UC_CONFIRM
    OWNER --- UC_REPORT_FIN
    OWNER --- UC_REPORT_OCC

    MIDTRANS --- UC_SNAP
    MIDTRANS --- UC_WEBHOOK

    UC_BOOKING -.->|&laquo;include&raquo;| UC_AUTH
    UC_PAY -.->|&laquo;include&raquo;| UC_SNAP
    UC_COUNTDOWN -.->|&laquo;extend&raquo;| UC_EXTEND
    UC_VERIFY -.->|&laquo;include&raquo;| UC_INVOICE
    UC_WEBHOOK -.->|&laquo;include&raquo;| UC_TX
```

---

## 3. Model Data Konseptual (*Conceptual Data Model - CDM*)

CDM memetakan struktur data logis independen dari DBMS fisik:

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

## 4. Model Data Fisik (*Physical Data Model - PDM / MySQL*)

PDM memetakan skema nyata database MySQL pada Laravel Migration termasuk tipe data presisi, Primary Key (PK), Foreign Key (FK), dan Relasi:

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

## 5. File XML untuk Draw.io
Semua diagram tersedia dalam format XML Draw.io siap pakai:
- 📄 **[CDM_PDM_DIAGRAM.xml](file:///c:/xampp/htdocs/Archofesa/CDM_PDM_DIAGRAM.xml)** — Multi-Tab: Tab 1 (CDM) & Tab 2 (PDM MySQL).
- 📄 **[USE_CASE_DIAGRAM.xml](file:///c:/xampp/htdocs/Archofesa/USE_CASE_DIAGRAM.xml)** — Use Case Diagram Lengkap.
- 📄 **[CONTEXT_DIAGRAM.xml](file:///c:/xampp/htdocs/Archofesa/CONTEXT_DIAGRAM.xml)** — Diagram Konteks (DFD Level 0).
- 📄 **[FLOW_DIAGRAM.xml](file:///c:/xampp/htdocs/Archofesa/FLOW_DIAGRAM.xml)** — Multi-Tab Master Diagram.
