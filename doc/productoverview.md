# 19HOUSE

## Product Requirements Document

**Product:** 19HOUSE
**Product Type:** Fashion E-Commerce & Retail Management System
**Platform:** Web Application
**Primary Audience:** Gen-Z Fashion Consumers
**Internal Users:** Admin, Kasir, Pimpinan
**Technology:** Laravel, Blade, Livewire, Tailwind CSS, MySQL
**Status:** Planning
**Version:** 1.0

---

# 1. PRODUCT OVERVIEW

## 1.1 Product Summary

**19HOUSE** adalah platform e-commerce fashion berbasis web yang menggabungkan pengalaman belanja online dengan sistem operasional toko dalam satu platform.

Produk dirancang terutama untuk konsumen **Gen-Z** dengan pendekatan UI **minimalist fashion/editorial**. Interface mengutamakan fotografi produk, whitespace, typography yang bersih, navigasi sederhana, dan interaksi yang halus.

19HOUSE tidak dirancang seperti marketplace yang menampilkan banyak informasi, banner, badge, promosi, dan elemen sekaligus.

Fokus utama storefront adalah:

> **Product first. Simple interaction. Strong visual identity.**

Di balik storefront yang sederhana, 19HOUSE memiliki sistem internal untuk menangani:

* Product Management
* Product Variants
* Variant Images
* Size
* Inventory
* Online Orders
* POS / Offline Sales
* Payments
* Customers
* Promotions
* Notifications
* Reports
* Business Monitoring

Semua transaksi menggunakan sumber inventory yang sama sehingga penjualan melalui website dan Kasir tetap tersinkronisasi.

---

# 2. PRODUCT VISION

Membangun pengalaman belanja fashion digital yang **minimal, modern, visual, dan effortless**, sekaligus menyediakan sistem operasional retail yang terintegrasi untuk 19HOUSE.

19HOUSE harus terasa sederhana bagi Customer tetapi tetap powerful bagi pengguna internal.

### Customer Experience

```text
Discover
   ↓
Explore
   ↓
Choose
   ↓
Checkout
   ↓
Pay
   ↓
Track
   ↓
Receive
```

Customer tidak perlu memahami kompleksitas sistem di belakangnya.

### Internal Experience

```text
Products
   ↓
Variants
   ↓
Inventory
   ↓
Sales
   ↓
Orders
   ↓
Payments
   ↓
Reports
```

Admin, Kasir, dan Pimpinan mendapatkan informasi sesuai kebutuhan role masing-masing.

---

# 3. PROBLEM STATEMENT

## Customer Problems

Customer fashion online sering menghadapi beberapa friction seperti:

* katalog terlalu ramai;
* terlalu banyak banner dan promosi;
* informasi produk sulit ditemukan;
* pemilihan warna dan ukuran membingungkan;
* foto produk tidak mengikuti warna yang dipilih;
* proses checkout terlalu panjang;
* status pembayaran tidak jelas;
* sulit mengetahui status pesanan;
* website kurang nyaman digunakan melalui smartphone.

19HOUSE harus mengurangi friction tersebut.

---

## Operational Problems

Operasional toko memiliki kebutuhan yang berbeda dengan Customer.

Sistem harus mampu menangani:

* banyak produk;
* beberapa variant dalam satu produk;
* beberapa size dalam satu variant;
* beberapa foto untuk setiap variant;
* stok masing-masing variant dan size;
* transaksi online;
* transaksi Kasir;
* perubahan inventory;
* pembayaran;
* pesanan;
* pembatalan;
* promosi;
* Customer;
* notification;
* reporting.

Data tersebut tidak boleh dikelola sebagai sistem yang terpisah.

---

# 4. PRODUCT GOALS

## G1 — Simple Shopping Experience

Customer dapat menemukan hingga membeli produk dengan jumlah langkah dan friction seminimal mungkin.

---

## G2 — Strong Product Presentation

Produk menjadi elemen utama dalam storefront.

Customer harus dapat melihat:

* product name;
* price;
* description;
* available colors;
* available sizes;
* variant-specific images;
* availability;
* product details.

---

## G3 — Variant-Based Product Experience

Setiap produk dapat mempunyai beberapa variant.

Contoh:

```text
Oversized T-Shirt
│
├── BLACK
│   ├── Black Image 01
│   ├── Black Image 02
│   ├── Black Image 03
│   │
│   ├── S
│   ├── M
│   └── L
│
├── WHITE
│   ├── White Image 01
│   ├── White Image 02
│   │
│   ├── S
│   ├── M
│   └── L
│
└── NAVY
    ├── Navy Image 01
    ├── Navy Image 02
    │
    ├── S
    ├── M
    └── L
```

Ketika Customer memilih **WHITE**, galeri harus berubah menjadi foto variant White.

Ketika Customer memilih:

```text
WHITE
+
SIZE M
```

sistem harus memeriksa stok kombinasi tersebut.

---

# 5. PRODUCT PRINCIPLES

Semua keputusan desain dan development 19HOUSE mengikuti prinsip berikut.

## Product First

Produk merupakan visual hierarchy utama.

Foto produk harus lebih dominan dibandingkan dekorasi interface.

---

## Minimal but Functional

Minimal bukan berarti menghilangkan fitur.

Fitur e-commerce tetap lengkap, tetapi complexity tidak boleh langsung ditampilkan kepada Customer.

---

## Visual over Decoration

Gunakan:

* photography;
* typography;
* spacing;
* composition;

untuk membentuk karakter brand.

Hindari ketergantungan terhadap:

* excessive shadows;
* gradient;
* colorful badges;
* unnecessary cards;
* excessive icons;
* decorative components.

---

## Mobile First

Storefront harus dirancang dari pengalaman smartphone terlebih dahulu.

Prioritas:

```text
Mobile
   ↓
Tablet
   ↓
Desktop
```

---

## Fast Interaction

Interaksi penting harus memberikan feedback secara cepat.

Contoh:

```text
Add to Cart
      ↓
Cart Drawer appears

Select Color
      ↓
Product Gallery changes

Select Size
      ↓
Availability updates

Apply Voucher
      ↓
Total recalculated
```

Livewire digunakan untuk menangani sebagian interaksi tersebut tanpa membutuhkan full page reload.

---

## Consistency

Komponen yang memiliki fungsi sama harus mempunyai behavior yang sama di seluruh aplikasi.

Termasuk:

* button;
* input;
* modal;
* drawer;
* product card;
* notification;
* navigation;
* loading;
* empty state;
* error state.

---

# 6. TARGET USERS

19HOUSE memiliki empat role utama.

| Role     | Type     | Primary Purpose          |
| -------- | -------- | ------------------------ |
| Customer | External | Membeli produk           |
| Kasir    | Internal | Menangani transaksi toko |
| Admin    | Internal | Mengelola operasional    |
| Pimpinan | Internal | Monitoring dan analisis  |

---

# 7. CUSTOMER

Customer merupakan pengguna utama storefront.

### Primary Goals

Customer ingin:

* menemukan produk;
* melihat collection;
* mencari produk;
* melihat detail produk;
* melihat pilihan warna;
* melihat foto berdasarkan variant;
* menentukan size;
* mengetahui availability;
* menyimpan wishlist;
* menambahkan produk ke cart;
* melakukan checkout;
* menggunakan voucher;
* memilih pengiriman;
* melakukan pembayaran;
* melihat status pembayaran;
* memantau pesanan;
* menerima notification;
* melihat purchase history;
* mengelola account.

### Customer Experience Target

Alur pembelian utama harus terasa sesederhana:

```text
HOME

 ↓

COLLECTION

 ↓

PRODUCT

 ↓

COLOR + SIZE

 ↓

ADD TO CART

 ↓

CHECKOUT

 ↓

PAYMENT

 ↓

ORDER CONFIRMED
```

---

# 8. KASIR

Kasir menangani transaksi penjualan langsung menggunakan POS 19HOUSE.

### Primary Goals

Kasir dapat:

* login;
* membuka POS;
* mencari produk;
* scan/search SKU;
* memilih variant;
* memilih size;
* melihat stock;
* menambahkan item;
* mengubah quantity;
* menghapus item;
* memberikan discount sesuai permission;
* memilih metode pembayaran;
* menerima pembayaran;
* menyelesaikan transaksi;
* menghasilkan receipt;
* melihat transaction history.

### POS Principle

POS harus mengutamakan **speed**.

Kasir tidak membutuhkan interface seperti storefront.

Flow utamanya:

```text
Find Product
     ↓
Select Variant
     ↓
Add Item
     ↓
Payment
     ↓
Complete
```

---

# 9. ADMIN

Admin merupakan operational controller dari 19HOUSE.

### Primary Responsibilities

Admin mengelola:

```text
Dashboard

Users
├── Customer
├── Cashier
└── Management

Catalog
├── Categories
├── Products
├── Variants
├── Sizes
└── Images

Inventory
├── Current Stock
├── Stock In
├── Stock Adjustment
└── Stock Movement

Orders
├── New
├── Processing
├── Shipped
├── Completed
└── Cancelled

Payments

Promotions
├── Discounts
└── Vouchers

Notifications

Reports

Settings

Activity Logs
```

Admin memiliki akses operasional paling luas tetapi tetap dibatasi berdasarkan permission sistem.

---

# 10. PIMPINAN

Pimpinan menggunakan sistem terutama untuk monitoring.

Pimpinan tidak perlu menangani pekerjaan operasional harian seperti mengedit produk.

### Primary Goals

Pimpinan dapat melihat:

* revenue;
* total sales;
* online sales;
* POS sales;
* number of orders;
* average order value;
* best-selling products;
* best-selling variants;
* low stock products;
* out-of-stock products;
* sales trends;
* payment statistics;
* customer statistics;
* sales reports;
* inventory reports.

Dashboard harus mengubah data operasional menjadi informasi yang mudah dibaca.

---

# 11. CORE PRODUCT MODULES

Versi lengkap 19HOUSE direncanakan memiliki module:

```text
19HOUSE
│
├── AUTHENTICATION
│
├── STOREFRONT
│   ├── Home
│   ├── Collection
│   ├── Search
│   ├── Product
│   └── Recommendation
│
├── CUSTOMER
│   ├── Account
│   ├── Address
│   ├── Wishlist
│   ├── Cart
│   ├── Checkout
│   ├── Orders
│   └── Notifications
│
├── CATALOG
│   ├── Category
│   ├── Product
│   ├── Variant
│   ├── Size
│   └── Product Media
│
├── INVENTORY
│   ├── Stock
│   └── Stock Movement
│
├── SALES
│   ├── Online Order
│   └── POS
│
├── PAYMENT
│
├── SHIPPING
│
├── PROMOTION
│   ├── Discount
│   └── Voucher
│
├── NOTIFICATION
│
├── REPORTING
│
└── SYSTEM
    ├── Users
    ├── Roles
    ├── Permissions
    ├── Settings
    └── Activity Logs
```

---

# 12. INVENTORY PRINCIPLE

19HOUSE menggunakan **shared inventory**.

Tidak terdapat stok terpisah antara:

```text
Website Stock
```

dan:

```text
POS Stock
```

Keduanya membaca sumber inventory yang sama.

Contoh:

```text
BLACK / M

Available Stock
10

Online Sale
-1

Available
9

POS Sale
-2

Available
7
```

Setiap perubahan inventory harus menghasilkan **Stock Movement**.

Contoh:

```text
STOCK MOVEMENT

SKU       : 19H-TS-BLK-M
Type      : SALE
Channel   : POS
Before    : 9
Quantity  : -2
After     : 7
Reference : POS-000129
```

Dengan demikian perubahan inventory dapat ditelusuri.

---

# 13. HIGH-LEVEL CUSTOMER JOURNEY

```text
DISCOVERY
│
├── Home
├── Collection
├── Search
└── Recommendation
        │
        ▼
CONSIDERATION
│
├── Product Detail
├── Product Images
├── Variant
├── Size
└── Availability
        │
        ▼
INTENT
│
├── Wishlist
└── Add to Cart
        │
        ▼
PURCHASE
│
├── Cart
├── Checkout
├── Address
├── Shipping
├── Voucher
└── Payment
        │
        ▼
FULFILLMENT
│
├── Order Confirmed
├── Processing
├── Shipped
└── Delivered
        │
        ▼
POST PURCHASE
│
├── Order History
├── Notification
└── Buy Again
```

---

# 14. PRODUCT SUCCESS CRITERIA

Keberhasilan produk tidak hanya diukur berdasarkan apakah fitur berhasil dibuat.

Beberapa indikator yang nantinya dapat digunakan adalah:

### Shopping

* Add-to-cart rate
* Checkout completion rate
* Payment success rate
* Cart abandonment rate
* Conversion rate

### Sales

* Revenue
* Number of orders
* Average Order Value
* Online vs POS sales

### Product

* Product views
* Best-selling products
* Best-selling variants
* Out-of-stock frequency

### Customer

* New customers
* Returning customers
* Repeat purchase rate

### Technical

* page load performance;
* system uptime;
* error rate;
* payment processing reliability;
* inventory accuracy.

Nilai target numeriknya belum ditetapkan pada tahap awal dan dapat ditentukan setelah sistem memiliki baseline penggunaan.

---

# 15. PRODUCT CONSTRAINTS

Pengembangan awal 19HOUSE memiliki beberapa constraint:

**Platform**

Fokus awal adalah **responsive web application**, bukan native Android/iOS application.

**Technology**

Core application menggunakan:

* Laravel
* Blade
* Livewire
* Tailwind CSS
* MySQL

**Responsive**

Customer storefront harus optimal untuk mobile dan desktop.

**Inventory**

Online Store dan POS harus menggunakan inventory yang sama.

**Authorization**

Semua fungsi internal harus dibatasi menggunakan role dan permission.

**Security**

Operasi seperti:

* payment;
* price modification;
* stock adjustment;
* order modification;
* user management;

tidak boleh hanya mengandalkan validasi frontend.

Validasi dan authorization harus dilakukan pada server.

---

# 16. OUT OF SCOPE — INITIAL RELEASE

Untuk menjaga scope development awal, beberapa kemampuan dapat ditempatkan di luar initial release, seperti:

* native mobile application;
* multi-vendor marketplace;
* international multi-currency;
* multi-language;
* AI stylist;
* AR virtual try-on;
* loyalty points;
* advanced warehouse management;
* multi-warehouse;
* complex accounting/ERP integration.

Fitur tersebut dapat dipertimbangkan pada versi berikutnya apabila kebutuhan bisnis 19HOUSE berkembang.

---

# 17. MVP DEFINITION

MVP 19HOUSE dianggap berhasil apabila Customer sudah dapat menyelesaikan proses:

```text
Discover Product
       ↓
Select Variant
       ↓
Select Size
       ↓
Add to Cart
       ↓
Checkout
       ↓
Payment
       ↓
Order Created
       ↓
Order Processed
       ↓
Order Completed
```

sementara internal user sudah dapat:

```text
Manage Product
      +
Manage Variant
      +
Manage Inventory
      +
Process Order
      +
POS Transaction
      +
Monitor Sales
```

tanpa membutuhkan pengelolaan data utama di luar sistem.

---

# 18. PRODUCT DIRECTION

19HOUSE harus mempunyai dua pengalaman yang berbeda tetapi berada dalam satu ekosistem.

### Customer Side

**Simple, visual, minimal, editorial.**

Customer hanya melihat informasi yang diperlukan untuk mengambil keputusan pembelian.

### Internal Side

**Functional, informative, efficient.**

Admin, Kasir, dan Pimpinan dapat melihat informasi lebih padat karena tujuan mereka bukan browsing produk, melainkan menjalankan operasional bisnis.

Prinsip akhirnya adalah:

> **Minimal on the surface, complete underneath.**

Storefront 19HOUSE harus terlihat sederhana karena sistem dirancang dengan baik, bukan karena sistem memiliki sedikit fitur.
