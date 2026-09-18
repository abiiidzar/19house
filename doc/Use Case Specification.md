# 131. USE CASE SPECIFICATION

Use Case Specification menjelaskan bagaimana setiap actor berinteraksi dengan sistem 19HOUSE.

Actor utama:

```text
CUSTOMER
CASHIER
ADMIN
MANAGEMENT
```

System actor tambahan:

```text
PAYMENT GATEWAY
SHIPPING SERVICE
SYSTEM
```

Setiap use case menggunakan struktur:

```text
Use Case ID
Use Case Name
Actor
Goal
Precondition
Trigger
Main Flow
Alternative / Exception Flow
Postcondition
Related Requirements
```

---

# 132. HIGH-LEVEL USE CASE MAP

```text
19HOUSE
│
├── CUSTOMER
│   ├── Register
│   ├── Login
│   ├── Browse Home
│   ├── Browse Collection
│   ├── Search Product
│   ├── Filter & Sort Product
│   ├── View Product Detail
│   ├── Select Variant
│   ├── Select Size
│   ├── View Variant Images
│   ├── Manage Wishlist
│   ├── Manage Cart
│   ├── Manage Address
│   ├── Checkout
│   ├── Apply Voucher
│   ├── Select Shipping
│   ├── Make Payment
│   ├── View Order
│   ├── Track Order
│   ├── Cancel Order
│   ├── View Notification
│   └── Manage Profile
│
├── CASHIER
│   ├── Login
│   ├── View Dashboard
│   ├── Open POS
│   ├── Search Product
│   ├── Select SKU
│   ├── Manage POS Cart
│   ├── Apply Allowed Discount
│   ├── Process Payment
│   ├── Complete Transaction
│   ├── Generate Receipt
│   └── View Transaction History
│
├── ADMIN
│   ├── Dashboard
│   ├── Manage Users
│   ├── Manage Categories
│   ├── Manage Products
│   ├── Manage Variants
│   ├── Manage Variant Images
│   ├── Manage Sizes
│   ├── Manage Inventory
│   ├── Manage Orders
│   ├── Manage Payments
│   ├── Manage Cancellation
│   ├── Manage Promotions
│   ├── Manage Vouchers
│   ├── Manage Homepage Content
│   ├── View Reports
│   ├── Manage Settings
│   └── View Activity Logs
│
└── MANAGEMENT
    ├── Login
    ├── View Dashboard
    ├── View Sales Performance
    ├── View Product Performance
    ├── View Inventory
    ├── View Customer Statistics
    ├── View Reports
    └── Export Reports
```

---

# 133. CUSTOMER USE CASES

---

# UC-CUS-001 — Register Account

**Actor:** Customer
**Priority:** Must

## Goal

Customer membuat akun 19HOUSE.

## Precondition

Customer belum login.

## Trigger

Customer memilih:

```text
Create Account
```

## Main Flow

1. Customer membuka Register.
2. Sistem menampilkan registration form.
3. Customer memasukkan:

   * name;
   * email;
   * phone;
   * password;
   * password confirmation.
4. Customer mengirim form.
5. Sistem memvalidasi data.
6. Sistem memastikan email belum digunakan.
7. Password di-hash.
8. Account Customer dibuat.
9. Sistem membuat authenticated session.
10. Customer diarahkan ke storefront/account.

## Alternative Flow

### Email sudah digunakan

Sistem menolak registration dan menampilkan informasi bahwa email sudah terdaftar.

### Password tidak memenuhi requirement

Sistem menampilkan validation error.

### Form tidak lengkap

Sistem menampilkan error pada field terkait.

## Postcondition

Customer memiliki account aktif.

## Related Requirements

```text
AUTH-002
SEC-001
SEC-004
```

---

# UC-CUS-002 — Login

**Actor:** Customer
**Priority:** Must

## Precondition

Customer memiliki account aktif.

## Trigger

Customer memilih Login.

## Main Flow

1. Customer membuka Login.
2. Customer memasukkan credential.
3. Sistem memvalidasi credential.
4. Sistem memeriksa account status.
5. Session dibuat.
6. Customer diarahkan kembali ke halaman yang sesuai.

Jika Customer sebelumnya sedang checkout, sistem dapat mengembalikan Customer ke checkout.

## Alternative Flow

### Credential salah

Login ditolak.

### Account inactive

Login ditolak.

### Terlalu banyak percobaan

Rate limiting diterapkan.

## Postcondition

Customer authenticated.

---

# UC-CUS-003 — Browse Homepage

**Actor:** Customer / Guest

## Goal

Melihat content utama dan menemukan produk.

## Main Flow

1. User membuka homepage.
2. Sistem menampilkan editorial hero.
3. Sistem menampilkan selected product section.
4. User dapat melihat:

   * new arrivals;
   * featured products;
   * selected collection;
   * campaign/editorial.
5. User memilih produk atau collection.

## Postcondition

User dapat melanjutkan ke Collection atau Product Detail.

---

# UC-CUS-004 — Browse Collection

**Actor:** Customer / Guest

## Main Flow

1. User memilih collection/category.
2. Sistem mengambil product aktif yang relevan.
3. Sistem menampilkan product grid.
4. User dapat:

   * scroll;
   * filter;
   * sort;
   * membuka product.

## Alternative Flow

Jika collection tidak memiliki produk:

```text
Empty Collection State
```

ditampilkan.

---

# UC-CUS-005 — Search Product

**Actor:** Customer / Guest

## Main Flow

1. User membuka Search.
2. User memasukkan keyword.
3. Sistem mencari product relevan.
4. Hasil ditampilkan.
5. User dapat membuka product.

## Alternative Flow

Tidak ada hasil.

Sistem menampilkan:

```text
No products found
```

beserta opsi kembali menjelajahi collection.

---

# UC-CUS-006 — Filter Products

**Actor:** Customer / Guest

## Main Flow

1. User membuka filter.
2. User menentukan filter:

   * category;
   * color;
   * size;
   * availability;
   * price.
3. Sistem memperbarui product results.
4. Filter aktif ditampilkan.
5. User dapat menghapus individual filter atau reset semua.

---

# UC-CUS-007 — Sort Products

**Actor:** Customer / Guest

Customer dapat memilih:

```text
Newest
Price Low to High
Price High to Low
Best Selling
```

Sistem memperbarui urutan produk.

---

# UC-CUS-008 — View Product Detail

**Actor:** Customer / Guest
**Priority:** Must

## Main Flow

1. User membuka product.
2. Sistem menampilkan:

   * product name;
   * gallery;
   * price;
   * variant;
   * sizes;
   * description;
   * availability;
   * size guide jika tersedia.
3. Default variant dipilih apabila sesuai rule.
4. User dapat memilih variant.
5. Gallery berubah sesuai variant.
6. User memilih size.
7. Sistem menampilkan availability.
8. User dapat Add to Cart atau Wishlist.

## Alternative Flow

### Product inactive

Product tidak dapat dibeli.

### Semua SKU habis

Product tetap dapat ditampilkan dengan state:

```text
SOLD OUT
```

---

# UC-CUS-009 — Select Product Variant

**Actor:** Customer / Guest

## Main Flow

1. Customer berada pada Product Detail.
2. Customer memilih color/variant.
3. Sistem memperbarui:

   * gallery;
   * available sizes;
   * price jika berbeda;
   * availability.
4. Selected variant diberi visual state.

---

# UC-CUS-010 — Select Size

**Actor:** Customer / Guest

## Main Flow

1. Variant telah dipilih.
2. Customer memilih size.
3. Sistem menentukan SKU.
4. Sistem memeriksa availability.
5. Jika available, Add to Cart dapat digunakan.

## Alternative Flow

Size stock = 0.

Size ditampilkan disabled.

---

# UC-CUS-011 — View Variant Images

**Actor:** Customer / Guest

Customer dapat melihat beberapa foto variant melalui:

```text
Gallery
Thumbnail
Swipe
Previous / Next
```

Pada mobile, gesture swipe dapat digunakan apabila implementasi mendukung.

---

# UC-CUS-012 — Add Product to Wishlist

**Actor:** Customer

## Precondition

Customer authenticated.

## Main Flow

1. Customer memilih Wishlist.
2. Sistem memeriksa apakah product sudah tersimpan.
3. Jika belum, product ditambahkan.
4. UI memberikan feedback.

## Alternative Flow

Guest memilih Wishlist.

Sistem meminta Login/Register.

---

# UC-CUS-013 — Remove Wishlist

Customer dapat menghapus product dari Wishlist.

---

# UC-CUS-014 — Add to Cart

**Actor:** Customer / Guest
**Priority:** Must

## Precondition

Product aktif.

Variant dan size valid telah dipilih.

## Main Flow

1. Customer memilih Add to Cart.
2. Sistem menentukan SKU.
3. Sistem memvalidasi:

   * product;
   * variant;
   * size;
   * availability.
4. Item ditambahkan ke cart.
5. Sistem memberikan feedback.
6. Cart Drawer dapat dibuka.

## Alternative Flow

### Variant belum dipilih

Customer diminta memilih variant.

### Size belum dipilih

Customer diminta memilih size.

### Stok habis

Item tidak ditambahkan.

### Item sudah ada

Quantity dapat ditambah sesuai stock.

---

# UC-CUS-015 — View Cart

**Actor:** Customer / Guest

Sistem menampilkan:

```text
Product
Variant
Size
Quantity
Price
Subtotal
```

Customer dapat melanjutkan ke Checkout.

---

# UC-CUS-016 — Update Cart Quantity

## Main Flow

1. Customer memilih quantity baru.
2. Sistem memeriksa available stock.
3. Cart diperbarui.
4. Total dihitung ulang.

## Alternative Flow

Requested quantity > available stock.

Sistem menolak quantity dan menampilkan jumlah maksimal tersedia.

---

# UC-CUS-017 — Remove Cart Item

Customer menghapus item.

Cart total diperbarui.

---

# UC-CUS-018 — Manage Address

**Actor:** Customer

Customer dapat:

```text
Create Address
Update Address
Delete Address
Set Default
```

Alamat yang sedang digunakan historical order tidak ikut berubah.

---

# UC-CUS-019 — Start Checkout

**Actor:** Customer
**Priority:** Must

## Precondition

* authenticated;
* cart tidak kosong;
* semua item valid.

## Main Flow

1. Customer memilih Checkout.
2. Sistem melakukan cart validation.
3. Sistem menampilkan Checkout.
4. Customer menentukan address.
5. Customer menentukan shipping.
6. Customer dapat memasukkan voucher.
7. Customer menentukan payment method.
8. Sistem menghitung total.
9. Customer melakukan Place Order.

## Alternative Flow

Jika guest:

```text
Checkout
↓
Login / Register
↓
Return to Checkout
```

---

# UC-CUS-020 — Apply Voucher

## Main Flow

1. Customer memasukkan voucher code.
2. Sistem memvalidasi voucher.
3. Jika valid, discount dihitung.
4. Total checkout diperbarui.

## Exception

Voucher:

* expired;
* inactive;
* limit tercapai;
* minimum transaction tidak terpenuhi.

Sistem menolak voucher dengan pesan yang sesuai.

---

# UC-CUS-021 — Select Shipping

Customer memilih shipping option yang tersedia.

Sistem memperbarui:

```text
Shipping Cost
Grand Total
```

---

# UC-CUS-022 — Place Order

**Priority:** Must

## Main Flow

1. Customer memilih Place Order.
2. Server melakukan final validation.
3. Sistem memvalidasi stock.
4. Sistem menghitung ulang price.
5. Sistem memvalidasi voucher.
6. Sistem memvalidasi shipping.
7. Sistem membuat order.
8. Sistem membuat order items snapshot.
9. Sistem membuat address snapshot.
10. Stock reservation dibuat jika diperlukan.
11. Payment dibuat.
12. Customer diarahkan ke proses payment.

## Exception

Jika stock berubah sebelum order dibuat, transaction dibatalkan dan Customer diberi informasi.

---

# UC-CUS-023 — Make Payment

**Actor:** Customer + Payment Gateway
**Priority:** Must

## Main Flow

1. Customer memilih payment method.
2. Payment transaction dibuat.
3. Payment provider memberikan payment instruction.
4. Customer menyelesaikan payment.
5. Provider mengirim callback.
6. Sistem memverifikasi callback.
7. Payment menjadi PAID.
8. Order menjadi PAID.
9. Inventory diproses sesuai rule.
10. Notification dibuat.

## Alternative Flow

### Payment failed

Status:

```text
FAILED
```

Customer dapat retry jika tersedia.

### Payment expired

Status:

```text
EXPIRED
```

Order dibatalkan sesuai business rule.

Reservation dilepas.

---

# UC-CUS-024 — View Payment Status

Customer dapat melihat:

```text
Pending
Paid
Failed
Expired
Refunded
```

tanpa memerlukan asumsi berdasarkan browser redirect.

---

# UC-CUS-025 — View Orders

**Actor:** Customer

Customer melihat order miliknya.

Order dapat difilter berdasarkan status jika diperlukan.

---

# UC-CUS-026 — View Order Detail

Customer melihat:

* order number;
* products;
* variants;
* sizes;
* quantity;
* price;
* discount;
* shipping;
* payment;
* tracking;
* address snapshot;
* order status;
* status timeline.

---

# UC-CUS-027 — Track Order

## Main Flow

1. Customer membuka Order Detail.
2. Sistem menampilkan fulfillment status.
3. Jika shipped, tracking number ditampilkan.
4. Jika shipping integration tersedia, tracking data dapat ditampilkan.

---

# UC-CUS-028 — Cancel Unpaid Order

## Precondition

Order:

```text
PENDING_PAYMENT
```

## Main Flow

1. Customer memilih Cancel.
2. Sistem meminta confirmation/reason.
3. Customer mengonfirmasi.
4. Order menjadi CANCELLED.
5. Payment dibatalkan apabila relevan.
6. Reservation dilepas.
7. History dibuat.

---

# UC-CUS-029 — Request Paid Order Cancellation

## Precondition

Order berada pada status yang mengizinkan cancellation request.

## Main Flow

1. Customer memilih Request Cancellation.
2. Customer memasukkan reason.
3. Request dibuat.
4. Admin menerima notification.
5. Status request:

```text
REQUESTED
```

Customer menunggu keputusan Admin.

---

# UC-CUS-030 — View Notifications

Customer dapat membuka Notification Center.

Sistem menampilkan:

* unread notification;
* read notification;
* related resource.

---

# UC-CUS-031 — Mark Notification as Read

Customer membuka atau menandai notification sebagai read.

---

# UC-CUS-032 — Manage Profile

Customer dapat mengubah:

* name;
* phone;
* photo;
* email sesuai verification policy;
* password.

---

# 134. CASHIER USE CASES

---

# UC-CAS-001 — Cashier Login

Kasir login menggunakan internal account.

Setelah berhasil:

```text
Cashier Dashboard / POS
```

---

# UC-CAS-002 — View Cashier Dashboard

Kasir melihat informasi seperti:

* sales today;
* transactions today;
* recent transactions;
* shortcut POS.

---

# UC-CAS-003 — Open POS

## Main Flow

1. Kasir membuka POS.
2. Sistem membuat/menampilkan empty POS cart.
3. Product search tersedia.
4. Kasir dapat mulai transaksi.

---

# UC-CAS-004 — Search POS Product

Kasir dapat mencari menggunakan:

```text
Product Name
SKU
Barcode (future/optional)
```

---

# UC-CAS-005 — Select POS SKU

Kasir memilih:

```text
Product
Variant
Size
```

Sistem menentukan SKU dan stock.

---

# UC-CAS-006 — Add POS Item

## Main Flow

1. Kasir memilih SKU.
2. Sistem memvalidasi stock.
3. Item masuk POS cart.
4. Total diperbarui.

---

# UC-CAS-007 — Update POS Cart

Kasir dapat:

* increase quantity;
* decrease quantity;
* remove item.

Semua perubahan harus divalidasi terhadap stock.

---

# UC-CAS-008 — Apply POS Discount

**Actor:** Cashier dengan permission

Discount hanya dapat diberikan sesuai rule:

```text
Predefined promotion
OR
Authorized manual discount
```

---

# UC-CAS-009 — Process Cash Payment

## Main Flow

1. Kasir memilih CASH.
2. Sistem menampilkan total.
3. Kasir memasukkan amount received.
4. Sistem memastikan received >= total.
5. Sistem menghitung change.
6. Kasir mengonfirmasi payment.

---

# UC-CAS-010 — Process QRIS / Non-Cash Payment

1. Kasir memilih payment method.
2. Payment dilakukan sesuai mekanisme toko.
3. Payment dikonfirmasi.
4. Transaction dilanjutkan.

---

# UC-CAS-011 — Complete POS Transaction

**Priority:** Must

## Main Flow

Dalam database transaction:

1. POS transaction dibuat.
2. POS items dibuat.
3. Payment tercatat.
4. Stock divalidasi.
5. Inventory dikurangi.
6. Stock movements dibuat.
7. Transaction menjadi complete.
8. Receipt dibuat.

Jika critical process gagal:

```text
ROLLBACK
```

---

# UC-CAS-012 — Generate Receipt

Setelah transaksi berhasil, Kasir dapat:

* melihat receipt;
* print receipt;
* mengirim format digital jika fitur tersedia.

---

# UC-CAS-013 — View Transaction History

Kasir melihat transaksi yang diizinkan berdasarkan permission.

Filter dapat mencakup:

```text
Date
Transaction Number
Payment Method
```

---

# 135. ADMIN USE CASES

---

# UC-ADM-001 — Admin Login

Admin login dan diarahkan ke Admin Dashboard.

---

# UC-ADM-002 — View Admin Dashboard

Admin melihat operational summary:

* new paid orders;
* pending processing;
* low stock;
* sales today;
* recent activity;
* relevant alerts.

---

# UC-ADM-003 — Manage Users

Admin dapat:

```text
View
Create Internal User
Update
Activate
Deactivate
Assign Role
```

Role change dicatat pada Activity Log.

---

# UC-ADM-004 — Manage Categories

Admin dapat:

* create;
* update;
* reorder;
* activate;
* deactivate.

---

# UC-ADM-005 — Create Product

## Main Flow

1. Admin memilih Add Product.
2. Mengisi:

   * name;
   * category;
   * description;
   * base price;
   * status.
3. Sistem memvalidasi.
4. Product dibuat.
5. Admin dapat melanjutkan ke Variant.

---

# UC-ADM-006 — Update Product

Admin memperbarui product information.

Perubahan sensitive seperti price dicatat.

---

# UC-ADM-007 — Archive Product

Admin mengarsipkan product.

Historical order tidak berubah.

---

# UC-ADM-008 — Create Product Variant

Admin menambahkan variant seperti:

```text
Black
White
Navy
```

Admin dapat menentukan color metadata.

---

# UC-ADM-009 — Manage Variant Images

Admin dapat:

* upload image;
* delete unused image;
* set primary image;
* reorder image.

Image terkait variant tertentu.

---

# UC-ADM-010 — Manage Product Sizes

Admin memilih size yang tersedia bagi variant.

---

# UC-ADM-011 — Create SKU

Sistem/Admin menghasilkan SKU unik untuk kombinasi:

```text
Product + Variant + Size
```

Duplicate SKU ditolak.

---

# UC-ADM-012 — View Inventory

Admin dapat melihat:

* SKU;
* product;
* variant;
* size;
* on-hand;
* reserved;
* available;
* status.

---

# UC-ADM-013 — Stock In

## Main Flow

1. Admin memilih SKU.
2. Memasukkan quantity.
3. Memasukkan reference/reason jika diperlukan.
4. Sistem menambah stock.
5. Stock movement dibuat.

---

# UC-ADM-014 — Stock Adjustment

## Main Flow

1. Admin memilih SKU.
2. Sistem menampilkan stock saat ini.
3. Admin memilih adjustment.
4. Admin memasukkan quantity.
5. Admin wajib memasukkan reason.
6. Sistem memvalidasi hasil tidak negatif.
7. Stock diperbarui.
8. Movement dibuat.
9. Activity Log dibuat.

---

# UC-ADM-015 — View Stock Movement

Admin dapat melihat chronological inventory movement.

Filter:

* SKU;
* product;
* movement type;
* date;
* actor.

---

# UC-ADM-016 — View Orders

Admin melihat seluruh online order.

Filter dapat meliputi:

```text
Order Status
Payment Status
Date
Customer
Order Number
```

---

# UC-ADM-017 — Process Order

## Precondition

Order:

```text
PAID
```

## Main Flow

1. Admin membuka Order Detail.
2. Memverifikasi item.
3. Memulai processing.
4. Status:

```text
PAID
↓
PROCESSING
```

5. History dibuat.
6. Customer mendapat notification.

---

# UC-ADM-018 — Mark Ready to Ship

Setelah packing selesai:

```text
PROCESSING
↓
READY_TO_SHIP
```

---

# UC-ADM-019 — Ship Order

## Main Flow

1. Admin memasukkan shipment information.
2. Admin memasukkan tracking number.
3. Status menjadi SHIPPED.
4. History dibuat.
5. Customer mendapat notification.

---

# UC-ADM-020 — Complete Order Manually

Admin hanya dapat melakukan manual completion apabila benar-benar diperlukan dan mempunyai permission.

Action dicatat.

---

# UC-ADM-021 — Review Cancellation Request

## Main Flow

1. Admin melihat request.
2. Admin memeriksa:

   * order;
   * payment;
   * fulfillment status;
   * reason.
3. Admin memilih:

   * Approve;
   * Reject.
4. Keputusan disimpan.
5. Customer menerima notification.

---

# UC-ADM-022 — Approve Cancellation

Jika cancellation diizinkan:

1. Order diubah sesuai rule.
2. Refund dimulai apabila diperlukan.
3. Inventory ditangani sesuai kondisi fulfillment.
4. History dibuat.
5. Customer mendapat notification.

---

# UC-ADM-023 — Reject Cancellation

Admin memasukkan internal/customer-visible reason jika diperlukan.

Request menjadi:

```text
REJECTED
```

---

# UC-ADM-024 — View Payments

Admin dapat melihat payment transaction:

* order;
* provider;
* method;
* amount;
* status;
* reference;
* timestamp.

---

# UC-ADM-025 — Manage Voucher

Admin dapat:

```text
Create
Update
Activate
Deactivate
View Usage
```

---

# UC-ADM-026 — Manage Promotion

Admin mengatur promotion sesuai business rule.

---

# UC-ADM-027 — Manage Homepage Content

Admin dapat mengelola content seperti:

* hero;
* editorial campaign;
* featured collection;
* featured products.

Admin tidak mengubah design system.

---

# UC-ADM-028 — View Customers

Admin dapat melihat Customer information yang memang dibutuhkan secara operasional.

Admin tidak boleh melihat password atau payment credential.

---

# UC-ADM-029 — View Operational Reports

Admin melihat:

* sales;
* orders;
* inventory;
* product performance.

---

# UC-ADM-030 — Export Report

Admin dengan permission dapat melakukan export.

---

# UC-ADM-031 — Manage Settings

Admin mengelola allowed system settings.

Sensitive setting changes dicatat.

---

# UC-ADM-032 — View Activity Logs

Admin dapat melihat audit information seperti:

```text
Actor
Action
Resource
Timestamp
Before
After
```

sesuai log availability.

---

# 136. MANAGEMENT USE CASES

---

# UC-MGT-001 — Management Login

Pimpinan login menggunakan account yang memiliki Management role.

---

# UC-MGT-002 — View Management Dashboard

Dashboard menampilkan business overview.

Minimum:

```text
Revenue
Sales
Orders
Average Order Value
Online vs POS
Best Sellers
Sales Trend
Inventory Alerts
Customer Trend
```

---

# UC-MGT-003 — Filter Dashboard Period

Pimpinan dapat memilih periode seperti:

```text
Today
Last 7 Days
Last 30 Days
This Month
Custom Range
```

---

# UC-MGT-004 — View Sales Performance

Pimpinan dapat melihat:

* gross sales/revenue sesuai definisi;
* total transaction;
* item sold;
* average order value;
* trend.

---

# UC-MGT-005 — Compare Sales Channels

Pimpinan dapat membandingkan:

```text
ONLINE
vs
POS
```

berdasarkan:

* revenue;
* transactions;
* units sold.

---

# UC-MGT-006 — View Product Performance

Pimpinan dapat melihat:

* best-selling product;
* units sold;
* product revenue;
* variant performance.

---

# UC-MGT-007 — View Inventory Status

Pimpinan melihat:

```text
Current Stock
Low Stock
Out of Stock
```

tanpa mempunyai stock adjustment permission.

---

# UC-MGT-008 — View Customer Statistics

Pimpinan dapat melihat aggregate information seperti:

* new customers;
* returning customers;
* repeat purchases.

Hanya data yang relevan terhadap bisnis yang ditampilkan.

---

# UC-MGT-009 — View Sales Report

Pimpinan dapat membuka detailed sales report.

---

# UC-MGT-010 — View Inventory Report

Pimpinan dapat melihat inventory report dan movement summary.

---

# UC-MGT-011 — Export Report

Pimpinan dapat export report jika fitur aktif.

---

# 137. SYSTEM USE CASES

Selain human actor, beberapa use case dilakukan otomatis oleh sistem.

---

# UC-SYS-001 — Process Payment Callback

**Actor:** Payment Gateway → System

## Main Flow

1. Provider mengirim callback.
2. Sistem memverifikasi authenticity.
3. Sistem menemukan payment.
4. Sistem memeriksa apakah event sudah diproses.
5. Status payment diperbarui.
6. Order diperbarui bila diperlukan.
7. Inventory diproses.
8. Status history dibuat.
9. Notification dibuat.

## Exception

Invalid callback:

```text
Rejected
Logged
No order mutation
```

---

# UC-SYS-002 — Expire Payment

Jika payment melewati expiry:

1. Payment menjadi EXPIRED.
2. Order berubah sesuai business rule.
3. Stock reservation dilepas.
4. Notification dibuat.

---

# UC-SYS-003 — Release Stock Reservation

Sistem melepaskan reserved stock saat:

* payment expired;
* unpaid order cancelled;
* reservation timeout.

---

# UC-SYS-004 — Create Low Stock Alert

Setelah stock movement:

```text
Available Stock <= Threshold
```

sistem membuat low-stock alert apabila kondisi alert belum aktif.

---

# UC-SYS-005 — Create Order Notification

System menghasilkan notification berdasarkan order event.

---

# UC-SYS-006 — Record Activity

Untuk operation tertentu, sistem mencatat:

```text
Actor
Action
Resource
Changes
Timestamp
```

---

# 138. PAYMENT GATEWAY USE CASES

Payment Gateway merupakan external system actor.

Gateway dapat:

```text
Create Payment Session
Provide Payment Instructions
Return Payment Status
Send Webhook / Callback
Process Refund
```

19HOUSE tidak menganggap browser redirect sebagai final payment confirmation.

---

# 139. SHIPPING SERVICE USE CASES

Apabila shipping API digunakan, external Shipping Service dapat menangani:

```text
Calculate Shipping Rate
Provide Courier Service
Provide Shipment Tracking
```

Integrasi final bergantung pada provider yang dipilih.

Jika integration belum tersedia, Admin masih dapat memasukkan:

```text
Courier
Service
Tracking Number
```

secara manual.

---

# 140. CRITICAL USE CASE DEPENDENCIES

Beberapa use case mempunyai dependency.

### Add to Cart

```text
View Product
   ↓
Select Variant
   ↓
Select Size
   ↓
Validate Stock
   ↓
Add to Cart
```

### Checkout

```text
Authenticated Customer
+
Valid Cart
+
Address
+
Shipping
+
Payment Method
```

### Complete Online Order

```text
Create Order
+
Payment Confirmation
+
Inventory Processing
+
Order Processing
+
Shipping
```

### Complete POS Transaction

```text
Cashier Authentication
+
Valid SKU
+
Available Stock
+
Payment
```

---

# 141. CUSTOMER USE CASE PRIORITY

## P0

```text
Register
Login
Browse Product
Search
Product Detail
Variant Selection
Size Selection
Cart
Checkout
Address
Shipping
Payment
Order History
Order Detail
Order Tracking
Notification
```

## P1

```text
Wishlist
Voucher
Cancellation Request
Advanced Filters
```

## P2

```text
Reviews
Back-in-Stock Notification
Loyalty
Personalized Recommendation
```

---

# 142. ADMIN USE CASE PRIORITY

## P0

```text
Product
Category
Variant
Size
Variant Image
Inventory
Order Processing
Payment Visibility
Users
Basic Reporting
```

## P1

```text
Voucher
Promotion
Homepage Content
Advanced Reports
Cancellation Workflow
```

---

# 143. CASHIER USE CASE PRIORITY

## P0

```text
Login
POS
Product Search
SKU Selection
POS Cart
Payment
Complete Transaction
Receipt
Transaction History
```

---

# 144. MANAGEMENT USE CASE PRIORITY

## P0

```text
Dashboard
Sales Report
Product Performance
Inventory Visibility
Online vs POS Comparison
```

## P1

```text
Customer Analytics
Advanced Comparison
Report Export
```

---

# 145. USE CASE SECURITY CONDITIONS

Semua use case protected harus mematuhi:

```text
Authentication
      ↓
Authorization
      ↓
Ownership (if relevant)
      ↓
Input Validation
      ↓
Business Rule Validation
      ↓
Execution
```

Contoh Customer Order Detail:

```text
Authenticated?
      ↓
Customer owns order?
      ↓
YES → Show
NO  → 403
```

---

# 146. USE CASE DATA INTEGRITY CONDITIONS

Use case kritis seperti:

```text
Checkout
Payment
POS Transaction
Stock Adjustment
Cancellation
Refund
```

harus mempertimbangkan:

* database transaction;
* concurrent requests;
* duplicate request;
* idempotency;
* server-side validation;
* audit trail.

---

# 147. USE CASE ERROR STATES

Setiap critical use case harus mempunyai error state yang dirancang.

Contoh Add to Cart:

```text
Product unavailable
Variant unavailable
Size unavailable
Out of stock
Quantity exceeds stock
Server error
```

Contoh Payment:

```text
Pending
Failed
Expired
Provider unavailable
Callback delayed
```

Contoh POS:

```text
Insufficient stock
Invalid payment amount
Payment failure
Transaction conflict
```

---

# 148. USE CASE SUCCESS FEEDBACK

User harus mendapatkan confirmation setelah successful action.

Contoh:

```text
Product added to cart

Address saved

Order placed successfully

Payment confirmed

Order shipped

Transaction completed

Stock successfully adjusted
```

Feedback harus sesuai visual language masing-masing interface.

Storefront menggunakan feedback minimal.

Dashboard internal dapat menggunakan alert/toast yang lebih informatif.

---

# 149. USE CASE SUMMARY BY ROLE

| Capability            |     Customer |  Cashier |    Admin | Management |
| --------------------- | -----------: | -------: | -------: | ---------: |
| Browse Products       |            ✓ |        ✓ |        ✓ |          ✓ |
| Buy Online            |            ✓ |        — |        — |          — |
| Wishlist              |            ✓ |        — |        — |          — |
| Checkout              |            ✓ |        — |        — |          — |
| Payment Online        |            ✓ |        — |     View |       View |
| Order Tracking        |            ✓ |        — |        ✓ |       View |
| POS                   |            — |        ✓ | Optional |          — |
| Catalog Management    |            — |        — |        ✓ |       View |
| Inventory View        | Availability |        ✓ |        ✓ |          ✓ |
| Inventory Adjustment  |            — |        — |        ✓ |          — |
| Order Processing      |            — |        — |        ✓ |          — |
| User Management       |            — |        — |        ✓ |          — |
| Operational Dashboard |            — |        ✓ |        ✓ |          — |
| Business Dashboard    |            — |        — |  Limited |          ✓ |
| Reports               |     Personal |  Limited |        ✓ |          ✓ |
| Settings              |     Personal | Personal |        ✓ |   Personal |
| Activity Logs         |            — |        — |        ✓ |   Optional |

---

# 150. USE CASE ACCEPTANCE

Use Case phase dianggap lengkap apabila:

```text
✓ Semua role memiliki use case utama
✓ Actor jelas
✓ Preconditions jelas
✓ Main flow jelas
✓ Alternative flow utama tersedia
✓ Postcondition jelas
✓ Authorization dapat dipetakan
✓ Business rules dapat dipetakan
✓ Critical error state teridentifikasi
✓ External system interaction teridentifikasi
```

Use Case Specification ini selanjutnya akan digunakan untuk membangun **User Flow**, sehingga yang digambarkan bukan hanya “fitur apa yang ada”, tetapi urutan halaman, keputusan user, kondisi gagal, redirect, dan hasil setiap flow.
