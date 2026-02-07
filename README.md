# 🌿 Smart Waste Bank - Sistem Kasir Bank Sampah Berbasis IoT

**Tugas Akhir** - Sistem Kasir Bank Sampah dengan integrasi IoT (Timbangan Digital + Kamera Deteksi Sampah)

## 📋 Fitur Utama

### 🔐 Authentication & Authorization
- Login untuk Admin dan Kasir
- Role-based access control
- Middleware protection

### 👨‍💼 Dashboard Admin
- **Statistik Real-time**:
  - Total transaksi hari ini
  - Total sampah terkumpul (kg)
  - Saldo kas bank sampah
- **Grafik Cash Flow** (30 hari terakhir)
- **CRUD Master Data**:
  - Harga Sampah (Waste Prices)
  - Data Customer
  - Data User (Admin & Kasir)

### 💰 Fitur Kasir (Smart Transaction)
- **Live Scale Display** (Timbangan Digital)
  - Polling real-time setiap 1 detik
  - Status indicator (Stable/Stabilizing/Waiting)
  - Simulasi IoT untuk development
- **Shopping Cart System**
  - Add/Remove items
  - Auto-calculate subtotal
  - Customer selection
- **Checkout & Print**
  - Generate transaction code otomatis
  - Update cash flow
  - Cetak struk PDF (DomPDF)
- **Riwayat Transaksi**

### 🤖 API Endpoints untuk IoT
- `POST /api/iot/update-reading` - Update data dari timbangan & kamera
- `POST /api/iot/reset-scale` - Reset/tare timbangan
- `GET /api/iot/device/{id}/status` - Cek status device

---

## 🛠️ Tech Stack

- **Backend**: Laravel 11
- **Database**: MySQL
- **Frontend**: Blade Templates + Tailwind CSS 4
- **JavaScript**: Vanilla JS (AJAX polling)
- **PDF Generation**: DomPDF
- **Chart**: Chart.js

---

## 📦 Instalasi

### 1. Prerequisites
Pastikan sudah terinstal:
- PHP >= 8.2
- Composer
- MySQL
- Node.js & NPM

### 2. Clone & Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### 3. Environment Setup

```bash
# Copy .env.example
cp .env.example .env

# Generate application key
php artisan key:generate
```

**Edit `.env`** dan sesuaikan konfigurasi database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smart_waste_bank
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Database Migration & Seeding

```bash
# Buat database terlebih dahulu di MySQL
# mysql -u root -p
# CREATE DATABASE smart_waste_bank;

# Jalankan migration
php artisan migrate

# Jalankan seeder (data dummy)
php artisan db:seed
```

**Data Dummy yang dibuat:**
- 1 Admin: `admin` / `admin123`
- 1 Kasir: `kasir` / `kasir123`
- 2 Jenis Sampah: PET (Rp 3.000/kg), NON-PET (Rp 1.500/kg)
- 2 Customer contoh
- 1 IoT Device: SCALE-001

### 5. Build Assets

```bash
npm run build
```

### 6. Jalankan Server

```bash
php artisan serve
```

Aplikasi akan berjalan di: **http://localhost:8000**

---

## 🎯 Cara Menggunakan

### Login
1. Buka browser: `http://localhost:8000/login`
2. Login sebagai:
   - **Admin**: `admin` / `admin123`
   - **Kasir**: `kasir` / `kasir123`

### Admin Dashboard
Setelah login sebagai admin, Anda bisa:
- Lihat statistik di dashboard
- Kelola harga sampah di menu "Harga Sampah"
- Tambah/edit/hapus customer di menu "Customer"
- Kelola user (admin/kasir) di menu "Users"

### Kasir - Melakukan Transaksi

#### Simulasi Input IoT (Developer Mode)
Karena alat IoT belum tersambung, gunakan form simulasi:
1. Masuk ke halaman **Kasir**
2. Di panel "Live Scale", lihat kotak kuning **"DEVELOPER MODE"**
3. Isi:
   - **Berat**: Contoh `1.5` (kg)
   - **Jenis Sampah**: Pilih `PET` atau `NON-PET`
4. Klik tombol **"Simulate"**
5. Data akan muncul di display timbangan

#### Alur Transaksi
1. **Pilih Customer** dari dropdown
2. **Input data timbangan** (via simulasi)
3. Tunggu status berubah menjadi **"🟢 Stable"**
4. Klik **"🔒 Lock & Add to Cart"**
5. Item masuk ke keranjang (panel kanan)
6. Ulangi langkah 2-5 untuk menambah item lain
7. Klik **"✅ CHECKOUT"**
8. Modal sukses muncul
9. Klik **"🖨️ Cetak Struk"** untuk download PDF

### Print Struk PDF
- Setelah checkout berhasil, klik tombol "Cetak Struk"
- File PDF akan terbuka di tab baru
- Format struk include: No. Transaksi, Tanggal, Kasir, Customer, Detail Items, Total

---

## 🔌 Integrasi dengan IoT (Future Implementation)

### Endpoint API untuk ESP32/Raspberry Pi

**POST** `/api/iot/update-reading`

**Request Body (JSON):**
```json
{
  "device_id": "SCALE-001",
  "detected_class": "PET",
  "weight_value": 1.5,
  "is_stable": true
}
```

**Response:**
```json
{
  "success": true,
  "message": "Reading updated successfully",
  "timestamp": "2026-02-05T10:30:00Z"
}
```

### Cara Integrasi dari Hardware
1. **ESP32/Arduino** mengirim HTTP POST request ke endpoint di atas
2. Body JSON berisi data dari:
   - **Load Cell** (HX711) → `weight_value`
   - **Camera + TensorFlow Lite** → `detected_class`
   - **Algoritma Stabilitas** → `is_stable`
3. Web akan auto-update via AJAX polling setiap 1 detik

**Contoh Code ESP32 (Arduino IDE):**
```cpp
#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>

const char* ssid = "YOUR_WIFI";
const char* password = "YOUR_PASSWORD";
const char* serverUrl = "http://192.168.1.100:8000/api/iot/update-reading";

void sendToServer(float weight, String wasteClass, bool isStable) {
  HTTPClient http;
  http.begin(serverUrl);
  http.addHeader("Content-Type", "application/json");
  
  StaticJsonDocument<200> doc;
  doc["device_id"] = "SCALE-001";
  doc["detected_class"] = wasteClass;
  doc["weight_value"] = weight;
  doc["is_stable"] = isStable;
  
  String jsonString;
  serializeJson(doc, jsonString);
  
  int httpResponseCode = http.POST(jsonString);
  http.end();
}
```

---

## 📊 Database Schema

### Users
- `id`, `username`, `password`, `full_name`, `role` (admin/kasir)

### Waste Prices
- `waste_type` (PK), `price_per_kg`, `updated_at`

### Customers
- `id`, `customer_code`, `full_name`, `phone_number`, `address`, `total_transactions`

### Transactions
- `id`, `transaction_code`, `cashier_id`, `customer_id`, `total_amount`, `created_at`

### Transaction Items
- `id`, `transaction_id`, `waste_type`, `weight`, `price_per_kg`, `subtotal`

### Cash Flow
- `id`, `date`, `type` (DEBIT/KREDIT), `amount`, `description`, `current_balance`

### IoT Devices
- `device_id` (PK), `status`, `detected_class`, `weight_value`, `is_stable`

### Current Readings
- `id`, `device_id`, `detected_class`, `weight_value`, `is_stable`, `updated_at`

---

## 🎨 UI/UX Design Theme

### Color Palette (Nature/Go Green)
- **Primary Green**: `#2D5016`
- **Secondary Green**: `#4A7C2C`
- **Light Green**: `#7FB069`
- **Cream Background**: `#FFFBF5`
- **Wood Brown**: `#8B4513`

### Typography
- Font: Default system sans-serif (clear & readable)
- Heading: Bold weights
- Body: Regular weights

### Components
- **Rounded corners** untuk modern look
- **Shadow effects** untuk depth
- **Gradient backgrounds** pada cards
- **Icon-first design** (emoji + SVG icons)

---

## 🐛 Troubleshooting

### Migration Error
```bash
# Reset database
php artisan migrate:fresh --seed
```

### Asset Not Loading
```bash
# Rebuild assets
npm run build
```

### PDF Generation Error
```bash
# Install DomPDF jika belum
composer require barryvdh/laravel-dompdf
```

### Permission Error (Linux/Mac)
```bash
chmod -R 775 storage bootstrap/cache
```

---

## 📝 Todo / Future Improvements

- [ ] Real IoT hardware integration (ESP32 + Load Cell + Camera)
- [ ] Machine Learning model training untuk klasifikasi sampah
- [ ] Laporan PDF (Harian, Bulanan, Tahunan)
- [ ] Export data ke Excel
- [ ] Notifikasi email untuk transaksi
- [ ] Dashboard customer (tracking poin)
- [ ] Multi-device support (multiple scales)
- [ ] Real-time notification (WebSocket/Pusher)

---

## 👨‍💻 Developer

**Nama**: [Nama Mahasiswa]  
**NIM**: [NIM]  
**Prodi**: [Program Studi]  
**Universitas**: [Nama Universitas]  

**Tugas Akhir**: Sistem Kasir Bank Sampah Berbasis IoT  
**Tahun**: 2026

---

## 📄 License

This project is for educational purposes (Tugas Akhir).

---

## 🙏 Acknowledgments

- Laravel Framework
- Tailwind CSS
- Chart.js
- DomPDF
- IoT Community

---

**Happy Coding! 🌿♻️**