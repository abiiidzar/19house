# 55. ROLES, PERMISSIONS & ACCESS CONTROL

Bagian ini mendefinisikan batas akses setiap role di dalam sistem 19HOUSE.

Role utama:

```text
ADMIN
CASHIER
MANAGEMENT
CUSTOMER
```

Tujuan access control adalah memastikan setiap pengguna hanya dapat mengakses data dan fungsi yang relevan dengan tanggung jawabnya.

---

# 56. ACCESS CONTROL PRINCIPLES

## 56.1 Server-Side Authorization

Hak akses tidak boleh hanya diterapkan dengan menyembunyikan menu atau tombol pada interface.

Semua tindakan penting harus diverifikasi di server.

Contoh:

```text
User hides "Delete Product" button
≠
User is prevented from deleting product
```

Yang benar:

```text
UI Permission Check
+
Route Protection
+
Policy / Authorization Check
+
Server Validation
```

---

## 56.2 Least Privilege

Setiap role hanya mendapatkan akses minimum yang dibutuhkan untuk menjalankan tugasnya.

Contoh:

Kasir tidak membutuhkan akses untuk:

* mengubah role user;
* mengubah harga produk global;
* menghapus produk;
* melihat konfigurasi sistem;
* melakukan stock adjustment tanpa permission.

---

## 56.3 Separation of Duties

Fungsi operasional dan monitoring dibedakan.

Contoh:

```text
Admin
→ mengelola operasional

Cashier
→ melakukan transaksi

Management
→ monitoring dan laporan

Customer
→ melakukan pembelian
```

---

## 56.4 Permission-Based Expansion

Walaupun sistem memiliki empat role utama, authorization sebaiknya dirancang agar dapat dikembangkan menjadi permission granular.

Contoh:

```text
products.view
products.create
products.update
products.archive

inventory.view
inventory.adjust

orders.view
orders.process
orders.cancel

reports.view
reports.export
```

Dengan demikian hak akses dapat dikembangkan tanpa mengubah struktur role utama.

---

# 57. CUSTOMER ACCESS

Customer hanya memiliki akses terhadap data dan fungsi yang berkaitan dengan account serta aktivitas belanjanya sendiri.

## Customer dapat:

* melihat storefront;
* melihat collection;
* mencari produk;
* menggunakan filter;
* melihat product detail;
* melihat foto variant;
* memilih size;
* melihat availability;
* menyimpan wishlist;
* mengelola cart;
* checkout;
* menggunakan voucher;
* memilih shipping;
* melakukan payment;
* melihat order miliknya;
* melihat tracking order;
* mengajukan cancellation sesuai aturan;
* melihat notification miliknya;
* mengelola profile;
* mengelola address.

## Customer tidak dapat:

* mengakses admin dashboard;
* melihat order Customer lain;
* mengubah product;
* mengubah price;
* mengubah stock;
* melihat internal report;
* mengakses POS;
* mengubah payment status;
* mengubah order status secara langsung;
* mengelola user lain.

---

# 58. CASHIER ACCESS

Kasir berfokus pada transaksi penjualan langsung.

## Kasir dapat:

* login ke internal system;
* melihat Cashier Dashboard;
* membuka POS;
* mencari produk;
* melihat SKU;
* melihat variant;
* melihat stock;
* membuat POS cart;
* mengubah quantity;
* menerima pembayaran;
* menyelesaikan transaksi;
* mencetak/melihat receipt;
* melihat transaksi miliknya atau transaksi toko sesuai permission.

## Kasir tidak dapat secara default:

* membuat produk;
* menghapus produk;
* mengubah product price;
* mengubah category;
* melakukan stock adjustment;
* mengelola role;
* mengelola user internal;
* mengubah settings;
* melihat laporan strategis penuh;
* mengubah order online selain akses yang diberikan.

---

# 59. ADMIN ACCESS

Admin memiliki akses operasional paling luas.

## Admin dapat:

### User

* melihat user;
* membuat internal user;
* mengubah internal user;
* mengaktifkan user;
* menonaktifkan user;
* menetapkan role sesuai batas sistem.

### Catalog

* membuat category;
* mengubah category;
* membuat product;
* mengubah product;
* mengarsipkan product;
* membuat variant;
* mengubah variant;
* mengatur size;
* mengatur SKU;
* mengatur product image.

### Inventory

* melihat stock;
* melakukan stock in;
* melakukan stock adjustment;
* melihat stock movement;
* mengatur low-stock threshold.

### Order

* melihat seluruh order;
* memproses order;
* mengubah fulfillment status;
* memasukkan tracking number;
* menangani cancellation;
* melihat payment status.

### Promotion

* membuat voucher;
* mengubah voucher;
* menonaktifkan voucher;
* mengatur promotion.

### Reporting

* melihat operational reports;
* melihat sales report;
* melihat inventory report;
* export report jika memiliki permission.

### System

* melihat activity log;
* mengelola selected settings;
* mengelola homepage content.

---

# 60. MANAGEMENT ACCESS

Pimpinan berfokus pada visibility dan decision support.

## Pimpinan dapat:

* melihat Management Dashboard;
* melihat revenue;
* melihat sales;
* melihat order statistics;
* melihat POS vs online sales;
* melihat product performance;
* melihat best-selling products;
* melihat inventory status;
* melihat low-stock data;
* melihat customer statistics;
* melihat sales reports;
* melihat inventory reports;
* melihat trend;
* export report jika diaktifkan.

## Pimpinan secara default tidak dapat:

* membuat product;
* mengubah product;
* menghapus product;
* membuat transaksi POS;
* melakukan stock adjustment;
* mengubah order operasional;
* mengubah payment status;
* mengelola user;
* mengubah system settings.

Pimpinan menggunakan pendekatan **read-mostly access**.

---

# 61. ACCESS MATRIX

Legend:

```text
✓   Allowed
△   Limited / Permission Based
—   Not Allowed
SELF Own Data Only
```

| Module / Action              |             Customer |         Cashier |                   Admin | Management |
| ---------------------------- | -------------------: | --------------: | ----------------------: | ---------: |
| View Storefront              |                    ✓ |               ✓ |                       ✓ |          ✓ |
| Register Customer Account    |                    ✓ |               — |                       △ |          — |
| Update Own Profile           |                    ✓ |               ✓ |                       ✓ |          ✓ |
| View Products                |                    ✓ |               ✓ |                       ✓ |          ✓ |
| Create Product               |                    — |               — |                       ✓ |          — |
| Update Product               |                    — |               — |                       ✓ |          — |
| Archive Product              |                    — |               — |                       ✓ |          — |
| Manage Category              |                    — |               — |                       ✓ |          — |
| Manage Variant               |                    — |               — |                       ✓ |          — |
| Manage Product Images        |                    — |               — |                       ✓ |          — |
| View Stock                   | Product availability |               ✓ |                       ✓ |          ✓ |
| Stock In                     |                    — |               — |                       ✓ |          — |
| Stock Adjustment             |                    — |               — |                       ✓ |          — |
| View Stock Movement          |                    — |               △ |                       ✓ |          ✓ |
| Wishlist                     |                 SELF |               — |                       — |          — |
| Cart                         |                 SELF |        POS Cart |                       — |          — |
| Checkout Online              |                 SELF |               — |                       — |          — |
| POS Transaction              |                    — |               ✓ |                       △ |          — |
| View Own Orders              |                 SELF |               — |                       — |          — |
| View All Orders              |                    — |               △ |                       ✓ |          ✓ |
| Process Online Order         |                    — |               △ |                       ✓ |          — |
| Cancel Own Order             |                    △ |               — |                       — |          — |
| Handle Cancellation          |                    — |               — |                       ✓ |          — |
| View Payments                |                 SELF | POS transaction |                       ✓ |          ✓ |
| Change Payment Status        |                    — |               — | System/Admin controlled |          — |
| Manage Voucher               |                    — |               — |                       ✓ |          — |
| Use Voucher                  |                    ✓ |               △ |                       — |          — |
| View Notification            |                 SELF |            SELF |                    SELF |       SELF |
| Manage Notification Template |                    — |               — |                       △ |          — |
| View Sales Report            |                    — |               △ |                       ✓ |          ✓ |
| View Inventory Report        |                    — |               — |                       ✓ |          ✓ |
| Export Report                |                    — |               — |                       △ |          ✓ |
| Manage Users                 |                    — |               — |                       ✓ |          — |
| Manage Roles                 |                    — |               — |                       ✓ |          — |
| View Activity Log            |                    — |               — |                       ✓ |          △ |
| Manage Settings              |                    — |               — |                       ✓ |          — |

---

# 62. PERMISSION GROUPS

Permission dapat dikelompokkan berdasarkan module.

## Authentication

```text
auth.login
auth.logout
profile.view
profile.update
```

---

## User

```text
users.view
users.create
users.update
users.activate
users.deactivate
roles.assign
```

---

## Product

```text
products.view
products.create
products.update
products.archive

categories.view
categories.create
categories.update

variants.view
variants.create
variants.update

product_images.create
product_images.delete
```

---

## Inventory

```text
inventory.view
inventory.stock_in
inventory.adjust
inventory.movements.view
```

---

## Orders

```text
orders.view
orders.process
orders.ship
orders.complete
orders.cancel
orders.cancellation.handle
```

---

## Payments

```text
payments.view
payments.verify
payments.refund
```

`payments.verify` dan `payments.refund` hanya diberikan bila memang diperlukan dan tetap mengikuti payment provider.

---

## POS

```text
pos.access
pos.transaction.create
pos.transaction.view
pos.discount.apply
pos.receipt.print
```

---

## Promotion

```text
promotions.view
promotions.create
promotions.update

vouchers.view
vouchers.create
vouchers.update
vouchers.disable
```

---

## Reporting

```text
reports.sales.view
reports.inventory.view
reports.products.view
reports.customers.view
reports.export
```

---

## Settings

```text
settings.view
settings.update
```

---

## Activity Logs

```text
activity_logs.view
```

---

# 63. ROLE DEFAULT PERMISSIONS

## Customer

```text
profile.view
profile.update

products.view

wishlist.manage
cart.manage

checkout.create

orders.own.view
orders.own.cancel

notifications.own.view
```

---

## Cashier

```text
profile.view
profile.update

products.view
inventory.view

pos.access
pos.transaction.create
pos.transaction.view
pos.receipt.print
```

Optional:

```text
pos.discount.apply
```

---

## Admin

Admin mendapatkan sebagian besar operational permissions.

Contoh:

```text
users.*
products.*
categories.*
variants.*

inventory.*

orders.*

promotions.*
vouchers.*

reports.*

settings.*
activity_logs.view
```

Tetapi permission sensitif seperti refund dapat dipisahkan jika diperlukan.

---

## Management

```text
profile.view
profile.update

dashboard.management.view

reports.sales.view
reports.inventory.view
reports.products.view
reports.customers.view
reports.export

inventory.view
orders.view
payments.view
```

Tidak mempunyai write permission secara default.

---

# 64. DATA OWNERSHIP RULES

Tidak semua authorization cukup ditentukan berdasarkan role.

Beberapa resource harus menggunakan ownership.

Contoh:

```text
Customer A
can view
Order A

Customer A
cannot view
Order B
```

Hal ini berlaku untuk:

* Customer profile;
* Customer address;
* wishlist;
* cart;
* orders;
* notifications.

---

# 65. CUSTOMER ORDER ACCESS

Customer hanya dapat membuka order jika:

```text
order.customer_id
==
authenticated_customer.id
```

Mengganti ID pada URL tidak boleh memungkinkan Customer membaca order milik pengguna lain.

Contoh:

```text
/orders/123
```

harus tetap melewati authorization.

---

# 66. ADMIN SENSITIVE ACTIONS

Beberapa operasi Admin memerlukan perlakuan lebih ketat.

Contoh:

* stock adjustment;
* role change;
* refund;
* order cancellation;
* user deactivation;
* settings change;
* product price modification.

Operasi tersebut harus:

1. membutuhkan authorization;
2. divalidasi server-side;
3. dicatat pada Activity Log;
4. mencatat actor;
5. mencatat timestamp;
6. mencatat relevant changes.

---

# 67. PRICE CHANGE RULE

Saat Admin mengubah product price:

```text
Current Product Price
Rp199.000

Admin updates
Rp219.000
```

harga baru hanya berlaku pada transaksi berikutnya.

Order yang sebelumnya mempunyai snapshot:

```text
Rp199.000
```

tetap menampilkan Rp199.000.

---

# 68. INVENTORY ACCESS RULE

Kasir boleh melihat stock tetapi tidak boleh melakukan manual adjustment secara default.

Contoh:

```text
Cashier sees:

BLACK / M
Stock: 5
```

Kasir tidak dapat langsung mengganti:

```text
5 → 100
```

Adjustment harus dilakukan oleh authorized Admin.

---

# 69. CASHIER TRANSACTION OWNERSHIP

Setiap POS transaction harus mencatat cashier yang membuat transaksi.

Contoh:

```text
Transaction
POS-000120

Cashier
User #17

Created At
2026-09-12 15:35
```

Hal ini dibutuhkan untuk:

* accountability;
* shift review;
* sales tracking;
* audit.

---

# 70. MANAGEMENT READ-ONLY RULE

Management dashboard menggunakan prinsip read-only.

Pimpinan dapat melakukan:

```text
View
Filter
Compare
Export
```

tetapi secara default tidak:

```text
Create
Update
Delete
Adjust
Process
```

Tujuannya adalah menjaga separation antara monitoring dan operational execution.

---

# 71. SYSTEM-ACTOR ACTIONS

Tidak semua perubahan dilakukan oleh manusia.

Sistem juga dianggap sebagai actor.

Contoh:

```text
Payment Webhook
→ Payment marked PAID

System
→ Order updated

System
→ Notification created
```

Actor dapat dibedakan:

```text
USER
SYSTEM
PAYMENT_GATEWAY
```

agar audit trail lebih jelas.

---

# 72. ACCESS DENIED BEHAVIOR

Jika user mencoba mengakses resource yang tidak diizinkan, sistem harus memberikan response yang tepat.

Untuk halaman web:

```text
403 Forbidden
```

atau redirect yang aman jika sesuai UX.

Sistem tidak boleh menampilkan data resource sebelum authorization selesai.

---

# 73. DISABLED USER BEHAVIOR

Jika internal user dinonaktifkan:

```text
ACTIVE
↓
INACTIVE
```

user tidak boleh membuat session baru.

Session aktif dapat diinvalidasi sesuai kebijakan keamanan sistem.

Historical record yang dibuat user tetap harus dipertahankan.

Contoh:

```text
POS Transaction
created_by = Cashier A
```

tetap mempunyai referensi ke Cashier A meskipun account tersebut sudah inactive.

---

# 74. ROLE CHANGE

Jika role user diubah:

```text
CASHIER
↓
ADMIN
```

hak akses berikutnya harus mengikuti role/permission baru.

Perubahan role harus tercatat dalam Activity Log.

---

# 75. ADMIN PROTECTION

Sistem harus mencegah kondisi yang dapat menghilangkan seluruh administrative access.

Contoh:

Jika hanya terdapat satu Admin aktif, sistem sebaiknya tidak mengizinkan Admin tersebut:

```text
Deactivate Himself
```

atau menghapus akses administratif terakhir tanpa mekanisme pengganti.

---

# 76. AUTHORIZATION IMPLEMENTATION DIRECTION

Implementasi Laravel dapat memanfaatkan kombinasi:

```text
Middleware
+
Policies
+
Gates / Permissions
```

Contoh high-level:

```text
Route
    ↓
Authentication Middleware
    ↓
Role / Permission Middleware
    ↓
Policy
    ↓
Business Logic
```

Blade/Livewire hanya menampilkan action yang diizinkan sebagai bagian UX.

Authorization final tetap berada pada server.

---

# 77. ACCESS CONTROL ACCEPTANCE CRITERIA

Access control dianggap selesai apabila:

### Customer

* Customer tidak dapat membuka dashboard internal.
* Customer tidak dapat melihat order orang lain.
* Customer hanya dapat mengubah profile miliknya.
* Customer hanya dapat melihat notification miliknya.

### Cashier

* Kasir dapat membuka POS.
* Kasir dapat melihat stock.
* Kasir tidak dapat melakukan stock adjustment.
* Kasir tidak dapat membuka User Management.
* Kasir tidak dapat mengedit global product price.

### Admin

* Admin dapat menjalankan seluruh operational module sesuai permission.
* sensitive actions tercatat.
* authorization berlaku pada UI dan server.

### Management

* Pimpinan dapat melihat dashboard dan report.
* Pimpinan tidak dapat mengubah operational data secara default.

### System

* direct URL access tetap dilindungi;
* manipulated request tetap ditolak;
* disabled user kehilangan akses;
* unauthorized API/Livewire action ditolak.
