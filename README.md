# Toko Penjualan - Point of Sale Application

Aplikasi web berbasis Laravel untuk mengelola penjualan barang dengan fitur CRUD kategori, produk, dan transaksi penjualan.

## 📋 Persyaratan Sistem

- PHP 8.1+
- Composer
- MySQL 5.7+ / MariaDB 10.4+
- Node.js 16+ (untuk compiling assets)
- Git

## 🚀 Instalasi

### 1. Clone Repository
```bash
git clone <repository-url>
cd toko-penjualan
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Konfigurasi Database di `.env`
DB_CONNECTION=mysql

DB_HOST=127.0.0.1

DB_PORT=3306

DB_DATABASE=toko_penjualan

DB_USERNAME=root

DB_PASSWORD=

### 5. Buat Database
```bash
mysql -u root
CREATE DATABASE toko_penjualan;
EXIT;
```

### 6. Run Migrations & Seeders
```bash
php artisan migrate --seed
```

### 7. Compile Assets
```bash
npm run build
```

### 8. Jalankan Aplikasi
```bash
php artisan serve
```

Akses aplikasi di: **http://localhost:8000**

---

## 🔐 Kredensial Default
Email: admin@toko.com

Password: password

---

## 📁 Struktur Project
toko-penjualan/

├── app/

│   ├── Models/

│   │   ├── User.php

│   │   ├── Category.php

│   │   ├── Product.php

│   │   ├── Sale.php

│   │   └── SaleItem.php

│   └── Http/Controllers/

│       ├── CategoryController.php

│       ├── ProductController.php

│       └── SaleController.php

├── database/

│   ├── migrations/

│   └── seeders/

├── resources/views/

│   ├── layouts/

│   ├── categories/

│   ├── products/

│   ├── sales/

│   └── home.blade.php

├── routes/

│   └── web.php

└── README.md

---

## ✨ Fitur Utama

### 1. **Autentikasi**
- Login dengan email dan password
- Session management
- Logout functionality

### 2. **Master Data - Kategori**
- CREATE: Tambah kategori baru
- READ: Lihat semua kategori
- UPDATE: Edit kategori
- DELETE: Hapus kategori

### 3. **Master Data - Produk**
- CREATE: Tambah produk (dengan kategori)
- READ: Lihat semua produk
- UPDATE: Edit produk
- DELETE: Hapus produk
- Relasi dengan kategori (One-to-Many)

### 4. **Transaksi - Penjualan**
- CREATE: Buat invoice penjualan dengan multiple items
- READ: Lihat semua penjualan & detail
- UPDATE: Edit transaksi penjualan
- DELETE: Hapus transaksi
- Automatic stock deduction
- Stock restoration saat hapus transaksi
- Relasi dengan produk & user

### 5. **Dashboard**
- Total penjualan hari ini
- Jumlah transaksi hari ini
- Produk stok rendah (< 10)
- Total produk
- Penjualan minggu ini

---

## 🗄️ Database Schema

### Tabel: users
| Column | Type |
|--------|------|
| id | BIGINT |
| name | VARCHAR |
| email | VARCHAR (unique) |
| password | VARCHAR |
| created_at | TIMESTAMP |
| updated_at | TIMESTAMP |

### Tabel: categories
| Column | Type |
|--------|------|
| id | BIGINT |
| name | VARCHAR (unique) |
| created_at | TIMESTAMP |
| updated_at | TIMESTAMP |

### Tabel: products
| Column | Type |
|--------|------|
| id | BIGINT |
| category_id | BIGINT (FK) |
| name | VARCHAR |
| sku | VARCHAR (unique) |
| price | DECIMAL(10,2) |
| stock | INT |
| created_at | TIMESTAMP |
| updated_at | TIMESTAMP |

### Tabel: sales
| Column | Type |
|--------|------|
| id | BIGINT |
| user_id | BIGINT (FK) |
| sale_date | DATE |
| total | DECIMAL(12,2) |
| created_at | TIMESTAMP |
| updated_at | TIMESTAMP |

### Tabel: sale_items
| Column | Type |
|--------|------|
| id | BIGINT |
| sale_id | BIGINT (FK) |
| product_id | BIGINT (FK) |
| quantity | INT |
| price | DECIMAL(10,2) |
| subtotal | DECIMAL(12,2) |
| created_at | TIMESTAMP |
| updated_at | TIMESTAMP |

---

## 🔗 Relasi Database
users (1) ──→ (∞) sales

categories (1) ──→ (∞) products

products (1) ──→ (∞) sale_items

sales (1) ──→ (∞) sale_items

---

## 🛠️ Troubleshooting

### Error: "SQLSTATE[HY000]: General error: 1005"
**Solusi:** Pastikan migration dijalankan dalam urutan yang benar. Jika sudah error, jalankan:
```bash
php artisan migrate:rollback
php artisan migrate --seed
```

### Error: "Class not found"
**Solusi:** Ensure namespace imports benar di controller.
```bash
composer dump-autoload
```

### Database connection error
**Solusi:** Periksa konfigurasi `.env`:
```bash
php artisan tinker
>>> DB::connection()->getPDO();
```

---

## 📝 Catatan Pengembang

- Validasi input dilakukan di controller dan migration constraints
- Password di-hash menggunakan bcrypt
- Stock otomatis berkurang saat create sale, restore saat delete/update
- Semua route yang bukan auth dilindungi middleware `auth`
- Menggunakan Laravel 11+ dengan Bootstrap 5

---

## 📄 Lisensi

MIT License

---

## 👤 Author

M. Taufiq Hidayat - Technical Test Submission
