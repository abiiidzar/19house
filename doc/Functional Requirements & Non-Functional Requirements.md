# 19. FUNCTIONAL REQUIREMENTS

Functional Requirements mendefinisikan kemampuan yang harus disediakan oleh sistem 19HOUSE.

Requirement dikelompokkan berdasarkan modul dengan kode unik:

```text
AUTH  Authentication & Account
USR   User & Role Management
CAT   Category
PRD   Product
VAR   Product Variant
INV   Inventory
COL   Collection & Discovery
SRC   Search & Filter
WSH   Wishlist
CRT   Cart
CHK   Checkout
ADR   Address
SHP   Shipping
PMT   Payment
ORD   Order
CNL   Cancellation
PRM   Promotion & Voucher
NTF   Notification
POS   Point of Sale
RPT   Reporting
DSH   Dashboard
SET   Settings
LOG   Activity Log
```

Priority menggunakan:

* **Must** — wajib tersedia.
* **Should** — penting tetapi tidak memblokir fungsi utama.
* **Could** — enhancement yang dapat ditambahkan setelah fungsi utama stabil.

---

# 20. AUTHENTICATION & ACCOUNT

## AUTH-001 — Login

**Priority:** Must

Sistem harus menyediakan authentication menggunakan email/credential dan password.

Role yang dapat login:

* Customer
* Kasir
* Admin
* Pimpinan

Setelah login, sistem mengarahkan pengguna berdasarkan role.

```text
Customer
   ↓
Storefront / Account

Kasir
   ↓
POS / Cashier Dashboard

Admin
   ↓
Admin Dashboard

Pimpinan
   ↓
Management Dashboard
```

---

## AUTH-002 — Customer Registration

**Priority:** Must

Customer dapat membuat account menggunakan minimal:

* name;
* email;
* phone number;
* password;
* password confirmation.

Email harus unik.

---

## AUTH-003 — Logout

**Priority:** Must

Semua authenticated user dapat logout dan session harus diakhiri secara aman.

---

## AUTH-004 — Forgot Password

**Priority:** Must

Pengguna dapat meminta password reset melalui email yang terdaftar.

---

## AUTH-005 — Reset Password

**Priority:** Must

Pengguna dapat membuat password baru melalui password reset token yang valid.

---

## AUTH-006 — Profile Management

**Priority:** Must

Pengguna dapat memperbarui informasi profile yang diizinkan.

Customer minimal dapat mengelola:

* name;
* email;
* phone;
* profile photo;
* password.

---

## AUTH-007 — Role-Based Redirect

**Priority:** Must

Sistem harus menentukan halaman awal berdasarkan role setelah authentication.

---

## AUTH-008 — Session Protection

**Priority:** Must

Halaman internal tidak dapat diakses oleh unauthenticated user.

---

# 21. USER & ROLE MANAGEMENT

## USR-001 — Role

**Priority:** Must

Sistem menyediakan empat role:

```text
ADMIN
CASHIER
MANAGEMENT
CUSTOMER
```

Role tidak boleh hanya digunakan untuk menyembunyikan menu frontend.

Authorization harus diverifikasi di server.

---

## USR-002 — User Management

**Priority:** Must

Admin dapat:

* melihat user;
* membuat internal user;
* mengedit user;
* mengaktifkan user;
* menonaktifkan user;
* menentukan role.

---

## USR-003 — Permission

**Priority:** Should

Sistem sebaiknya mendukung permission yang lebih granular.

Contoh:

```text
products.view
products.create
products.update
products.delete

inventory.view
inventory.adjust

orders.view
orders.process
orders.cancel

reports.view
```

Dengan demikian hak akses tidak sepenuhnya hard-coded berdasarkan role.

---

# 22. CATEGORY MANAGEMENT

## CAT-001 — Category

**Priority:** Must

Admin dapat:

* membuat;
* melihat;
* mengubah;
* mengaktifkan;
* menonaktifkan;

category.

Contoh:

```text
TOPS
BOTTOMS
OUTERWEAR
ACCESSORIES
```

---

## CAT-002 — Category Product Association

**Priority:** Must

Produk harus dapat dihubungkan dengan category.

---

## CAT-003 — Category Visibility

**Priority:** Must

Category yang inactive tidak ditampilkan sebagai navigasi aktif pada storefront.

---

# 23. PRODUCT MANAGEMENT

## PRD-001 — Create Product

**Priority:** Must

Admin dapat membuat produk.

Minimum data:

* product name;
* slug;
* category;
* description;
* base price;
* status.

---

## PRD-002 — Product Status

**Priority:** Must

Produk memiliki status minimal:

```text
DRAFT
ACTIVE
INACTIVE
```

Hanya produk yang memenuhi status publik yang dapat ditampilkan kepada Customer.

---

## PRD-003 — Product Detail

**Priority:** Must

Customer dapat melihat:

* product name;
* price;
* description;
* gallery;
* color/variant;
* size;
* availability;
* relevant product information.

---

## PRD-004 — Product Slug

**Priority:** Must

Setiap produk mempunyai URL-friendly slug unik.

Contoh:

```text
/products/oversized-essential-tee
```

---

## PRD-005 — Featured Product

**Priority:** Should

Admin dapat menentukan produk yang akan ditampilkan pada area tertentu seperti:

* New Arrival
* Featured
* Recommended

tanpa mengubah data produk utama.

---

## PRD-006 — Product Archive

**Priority:** Should

Produk yang sudah mempunyai transaction history sebaiknya tidak dihapus secara permanen.

Produk dapat diarsipkan agar historical order tetap valid.

---

# 24. PRODUCT VARIANT

Variant merupakan salah satu bagian paling penting pada 19HOUSE.

## VAR-001 — Product Variant

**Priority:** Must

Satu produk dapat mempunyai banyak variant.

Contoh:

```text
Essential Tee

BLACK
WHITE
NAVY
BROWN
```

---

## VAR-002 — Variant Images

**Priority:** Must

Setiap variant dapat mempunyai beberapa gambar sendiri.

```text
BLACK
├── front.jpg
├── back.jpg
├── detail.jpg
└── model.jpg

WHITE
├── front.jpg
├── back.jpg
└── model.jpg
```

---

## VAR-003 — Dynamic Gallery

**Priority:** Must

Ketika Customer mengganti variant, gallery harus mengikuti variant tersebut tanpa membutuhkan full page reload.

```text
BLACK selected
      ↓
BLACK images displayed

WHITE selected
      ↓
WHITE images displayed
```

---

## VAR-004 — Size

**Priority:** Must

Variant dapat mempunyai beberapa size.

Contoh:

```text
BLACK
├── S
├── M
├── L
└── XL
```

---

## VAR-005 — SKU

**Priority:** Must

Setiap sellable variant/size combination harus mempunyai SKU unik.

Contoh:

```text
19H-TEE-BLK-S
19H-TEE-BLK-M
19H-TEE-BLK-L

19H-TEE-WHT-S
19H-TEE-WHT-M
```

---

## VAR-006 — Variant Price

**Priority:** Should

Sistem harus memungkinkan variant tertentu memiliki harga berbeda apabila dibutuhkan.

Apabila tidak mempunyai harga khusus, gunakan harga produk.

---

## VAR-007 — Variant Availability

**Priority:** Must

Customer hanya dapat memilih kombinasi variant dan size yang tersedia.

Out-of-stock combination tetap dapat ditampilkan tetapi harus mempunyai state yang jelas dan tidak dapat dibeli.

---

# 25. INVENTORY MANAGEMENT

## INV-001 — Shared Inventory

**Priority:** Must

Storefront dan POS menggunakan sumber inventory yang sama.

---

## INV-002 — Stock Per SKU

**Priority:** Must

Stock disimpan berdasarkan sellable SKU.

Contoh:

```text
BLACK / S = 10
BLACK / M = 15
BLACK / L = 8

WHITE / S = 5
WHITE / M = 7
WHITE / L = 0
```

---

## INV-003 — Stock Movement

**Priority:** Must

Setiap perubahan stock harus menghasilkan inventory movement.

Movement minimal menyimpan:

* SKU;
* movement type;
* quantity;
* stock before;
* stock after;
* reference;
* user/system actor;
* timestamp.

---

## INV-004 — Stock Movement Type

**Priority:** Must

Movement minimal mendukung:

```text
STOCK_IN
SALE
RETURN
ADJUSTMENT
CANCELLATION
```

---

## INV-005 — Prevent Negative Stock

**Priority:** Must

Sistem tidak boleh mengizinkan transaksi menyebabkan stock negatif.

---

## INV-006 — Low Stock

**Priority:** Should

Sistem dapat menentukan threshold low stock.

Contoh:

```text
Threshold = 5

Stock 4
→ LOW STOCK
```

Admin mendapatkan indikator ketika stock mencapai threshold.

---

## INV-007 — Stock Adjustment

**Priority:** Must

Admin dengan permission dapat melakukan stock adjustment.

Adjustment harus membutuhkan:

* quantity;
* reason.

Perubahan harus masuk Activity Log dan Stock Movement.

---

# 26. COLLECTION & PRODUCT DISCOVERY

## COL-001 — Home

**Priority:** Must

Home dapat menampilkan:

* hero/editorial campaign;
* new arrivals;
* featured products;
* selected collection;
* campaign imagery;
* brand/editorial content.

Konten harus tetap minimal dan tidak membuat halaman terasa seperti marketplace.

---

## COL-002 — Collection

**Priority:** Must

Customer dapat membuka halaman collection/category dan melihat produk yang terkait.

---

## COL-003 — Product Grid

**Priority:** Must

Produk ditampilkan dalam responsive editorial grid.

Product card minimal mempunyai:

* image;
* product name;
* price.

Informasi tambahan hanya ditampilkan apabila memang diperlukan.

---

# 27. SEARCH, FILTER & SORTING

## SRC-001 — Product Search

**Priority:** Must

Customer dapat mencari produk berdasarkan keyword.

---

## SRC-002 — Filter

**Priority:** Must

Customer dapat melakukan filter berdasarkan informasi yang relevan seperti:

* category;
* size;
* color;
* availability;
* price range.

---

## SRC-003 — Sorting

**Priority:** Must

Customer dapat mengurutkan produk berdasarkan:

* newest;
* price low-to-high;
* price high-to-low;
* popularity/best selling apabila data tersedia.

---

## SRC-004 — Empty Search State

**Priority:** Must

Jika produk tidak ditemukan, Customer mendapatkan empty state yang jelas dan opsi kembali menjelajahi collection.

---

# 28. WISHLIST

## WSH-001 — Add Wishlist

**Priority:** Should

Authenticated Customer dapat menyimpan produk ke wishlist.

---

## WSH-002 — Remove Wishlist

**Priority:** Should

Customer dapat menghapus produk dari wishlist.

---

## WSH-003 — Wishlist Page

**Priority:** Should

Customer mempunyai halaman khusus untuk melihat produk yang telah disimpan.

---

# 29. SHOPPING CART

## CRT-001 — Add to Cart

**Priority:** Must

Customer dapat memasukkan produk setelah memilih variant dan size yang valid.

---

## CRT-002 — Cart Item Identity

Cart item harus menyimpan kombinasi:

```text
Product
+
Variant
+
Size
+
Quantity
```

---

## CRT-003 — Update Quantity

**Priority:** Must

Customer dapat menambah atau mengurangi quantity.

Sistem harus memvalidasi stock.

---

## CRT-004 — Remove Item

**Priority:** Must

Customer dapat menghapus item dari cart.

---

## CRT-005 — Cart Summary

**Priority:** Must

Cart menampilkan:

* product image;
* product name;
* variant;
* size;
* quantity;
* price;
* subtotal;
* order subtotal.

---

## CRT-006 — Cart Drawer

**Priority:** Should

Setelah `Add to Cart`, sistem dapat menampilkan cart drawer tanpa memindahkan Customer dari Product Detail.

---

## CRT-007 — Persistent Cart

**Priority:** Should

Cart authenticated Customer tetap tersedia ketika Customer login kembali.

---

## CRT-008 — Cart Validation

**Priority:** Must

Sebelum checkout sistem harus melakukan validasi ulang terhadap:

* product availability;
* variant availability;
* price;
* stock;
* promotion.

Data dari cart tidak boleh dianggap sebagai sumber final transaksi.

---

# 30. CUSTOMER ADDRESS

## ADR-001 — Address Management

**Priority:** Must

Customer dapat:

* menambah alamat;
* mengubah alamat;
* menghapus alamat;
* menentukan default address.

---

## ADR-002 — Address Information

Alamat minimal mendukung:

* recipient name;
* phone;
* province;
* city/regency;
* district;
* postal code;
* detailed address.

Struktur final menyesuaikan shipping provider yang dipilih.

---

# 31. CHECKOUT

## CHK-001 — Checkout

**Priority:** Must

Customer dapat checkout item yang valid dari cart.

---

## CHK-002 — Checkout Information

Checkout minimal menampilkan:

```text
Shipping Address

Order Items

Shipping Method

Voucher / Discount

Subtotal

Shipping Cost

Discount

Grand Total

Payment Method
```

---

## CHK-003 — Final Validation

**Priority:** Must

Sebelum order dibuat, server melakukan validasi ulang:

* stock;
* product status;
* variant;
* size;
* current price;
* discount;
* voucher;
* shipping;
* total.

---

## CHK-004 — Price Snapshot

**Priority:** Must

Harga pada order disimpan sebagai snapshot.

Perubahan harga produk setelah transaksi tidak boleh mengubah historical order.

---

# 32. SHIPPING

## SHP-001 — Shipping Method

**Priority:** Must

Customer dapat memilih metode pengiriman yang tersedia.

---

## SHP-002 — Shipping Cost

**Priority:** Must

Biaya pengiriman harus masuk perhitungan grand total sebelum pembayaran.

---

## SHP-003 — Tracking Number

**Priority:** Must

Admin dapat memasukkan nomor resi ketika order dikirim.

---

## SHP-004 — Shipment Tracking

**Priority:** Should

Customer dapat melihat nomor resi dan informasi pengiriman dari Order Detail.

Integrasi tracking otomatis dapat ditambahkan apabila shipping provider mendukung.

---

# 33. PAYMENT

## PMT-001 — Payment Method

**Priority:** Must

Customer dapat memilih payment method yang disediakan oleh sistem/payment gateway.

---

## PMT-002 — Payment Record

**Priority:** Must

Setiap payment attempt harus tercatat dan berhubungan dengan order.

---

## PMT-003 — Payment Status

**Priority:** Must

Payment minimal mempunyai status:

```text
PENDING
PAID
FAILED
EXPIRED
REFUNDED
```

---

## PMT-004 — Payment Verification

**Priority:** Must

Status pembayaran dari payment gateway tidak boleh dipercaya berdasarkan redirect browser Customer saja.

Status final harus diverifikasi menggunakan server-side callback/webhook atau mekanisme resmi provider.

---

## PMT-005 — Duplicate Callback Protection

**Priority:** Must

Callback pembayaran yang sama tidak boleh menyebabkan order diproses dua kali.

---

## PMT-006 — Payment Expiration

**Priority:** Must

Pembayaran yang memiliki batas waktu harus dapat berubah menjadi expired sesuai informasi payment provider.

---

# 34. ORDER MANAGEMENT

## ORD-001 — Create Order

**Priority:** Must

Order dibuat berdasarkan hasil checkout yang valid.

---

## ORD-002 — Order Number

**Priority:** Must

Setiap order mempunyai nomor unik.

Contoh:

```text
19H-20260912-0001
```

Format final dapat ditentukan pada tahap implementasi.

---

## ORD-003 — Order Snapshot

**Priority:** Must

Order item menyimpan snapshot informasi penting:

* product name;
* SKU;
* variant;
* size;
* unit price;
* quantity;
* subtotal.

Historical order tidak boleh berubah hanya karena catalog diperbarui.

---

## ORD-004 — Order Status

**Priority:** Must

High-level order lifecycle:

```text
PENDING_PAYMENT
      ↓
PAID
      ↓
PROCESSING
      ↓
SHIPPED
      ↓
DELIVERED
      ↓
COMPLETED
```

Alternative state:

```text
CANCELLED
```

Payment status dan fulfillment/order status sebaiknya tidak digabung menjadi satu field karena keduanya merepresentasikan kondisi yang berbeda.

---

## ORD-005 — Order History

**Priority:** Must

Customer dapat melihat semua order miliknya.

---

## ORD-006 — Order Detail

**Priority:** Must

Customer dapat melihat:

* order number;
* date;
* items;
* payment;
* shipping;
* address snapshot;
* order status;
* tracking;
* total;
* status history.

---

## ORD-007 — Order Status History

**Priority:** Must

Perubahan status order harus mempunyai history.

Contoh:

```text
12 Sep 10:05
Payment Confirmed

12 Sep 10:30
Processing

13 Sep 14:20
Shipped
```

---

# 35. ORDER CANCELLATION

## CNL-001 — Customer Cancellation

**Priority:** Must

Customer hanya dapat meminta/melakukan pembatalan pada status yang diizinkan oleh business rules.

---

## CNL-002 — Cancellation Reason

**Priority:** Must

Pembatalan harus mempunyai alasan.

---

## CNL-003 — Inventory Restoration

**Priority:** Must

Apabila order yang telah mempengaruhi inventory dibatalkan dan aturan bisnis mengharuskan stock dikembalikan, sistem harus membuat reverse inventory movement.

Stock tidak boleh diperbaiki hanya dengan mengubah angka secara manual.

---

## CNL-004 — Paid Order Cancellation

**Priority:** Must

Pembatalan order yang sudah dibayar harus mengikuti aturan refund/payment provider dan tidak boleh diperlakukan sama dengan unpaid order.

Detail lifecycle akan ditentukan pada Business Rules.

---

# 36. PROMOTION & VOUCHER

## PRM-001 — Voucher

**Priority:** Should

Admin dapat membuat voucher dengan:

* code;
* discount type;
* discount value;
* minimum purchase;
* maximum discount;
* start date;
* expiration date;
* usage limit;
* user usage limit;
* status.

---

## PRM-002 — Voucher Validation

**Priority:** Must apabila voucher diaktifkan

Voucher harus divalidasi server-side ketika digunakan dan ketika checkout diselesaikan.

---

## PRM-003 — Discount Types

Sistem minimal dapat mendukung:

```text
FIXED AMOUNT

PERCENTAGE
```

---

# 37. NOTIFICATION

## NTF-001 — In-App Notification

**Priority:** Must

Sistem menyediakan notification center.

---

## NTF-002 — Customer Notification

Customer dapat menerima notification untuk event seperti:

* payment confirmed;
* payment failed/expired;
* order processed;
* order shipped;
* order completed;
* order cancelled.

---

## NTF-003 — Admin Notification

Admin dapat menerima notification seperti:

* new paid order;
* low stock;
* cancellation request;
* operational event penting.

---

## NTF-004 — Cashier Notification

Kasir hanya menerima notification yang relevan dengan pekerjaan Kasir.

---

## NTF-005 — Management Notification

Pimpinan dapat menerima notification yang relevan dengan monitoring bisnis apabila fitur tersebut diaktifkan.

---

## NTF-006 — Read State

**Priority:** Must

Notification mempunyai state:

```text
UNREAD
READ
```

User dapat menandai notification sebagai read.

---

# 38. POINT OF SALE

## POS-001 — POS Access

**Priority:** Must

POS hanya dapat diakses oleh role/permission yang diizinkan.

---

## POS-002 — Product Search

**Priority:** Must

Kasir dapat mencari produk berdasarkan:

* product name;
* SKU.

Barcode support dapat ditambahkan apabila dibutuhkan.

---

## POS-003 — Variant Selection

**Priority:** Must

Kasir memilih SKU/variant/size yang benar sebelum menambahkan item.

---

## POS-004 — POS Cart

**Priority:** Must

Kasir dapat:

* add item;
* update quantity;
* remove item;
* melihat subtotal;
* melihat total.

---

## POS-005 — Stock Validation

**Priority:** Must

POS menggunakan inventory yang sama dengan online store.

---

## POS-006 — POS Payment

**Priority:** Must

Kasir dapat memilih metode pembayaran toko yang tersedia.

Contoh awal:

```text
CASH
QRIS
TRANSFER / OTHER
```

Metode final ditentukan berdasarkan kebutuhan bisnis.

---

## POS-007 — Cash Payment

**Priority:** Must

Untuk pembayaran tunai, sistem dapat menghitung:

```text
Total     Rp250.000
Received  Rp300.000
Change     Rp50.000
```

---

## POS-008 — Complete Transaction

**Priority:** Must

Setelah transaksi berhasil:

* transaction tersimpan;
* payment tercatat;
* stock berkurang;
* stock movement dibuat;
* receipt dapat dihasilkan.

Proses tersebut harus dijaga agar tidak menghasilkan transaksi parsial apabila salah satu operasi penting gagal.

---

## POS-009 — Transaction History

**Priority:** Must

Kasir dapat melihat transaksi yang relevan sesuai permission.

---

# 39. DASHBOARD

## DSH-001 — Admin Dashboard

Admin dashboard dapat menampilkan informasi operasional seperti:

* orders today;
* pending orders;
* sales;
* low stock;
* recent transactions;
* inventory alerts.

---

## DSH-002 — Cashier Dashboard

Kasir dashboard memprioritaskan:

* open POS;
* sales today;
* transactions today;
* recent transactions.

---

## DSH-003 — Management Dashboard

Pimpinan dashboard memprioritaskan informasi bisnis:

```text
Revenue
Sales
Orders
Average Order Value
Online vs POS
Best Sellers
Sales Trend
Low Stock
Customer Trend
```

---

## DSH-004 — Customer Dashboard

Customer Account Overview dapat menampilkan:

* active orders;
* recent orders;
* wishlist;
* notification;
* saved addresses;
* account information.

Customer dashboard harus tetap mengikuti visual language storefront dan tidak terlihat seperti dashboard admin.

---

# 40. REPORTING

## RPT-001 — Sales Report

**Priority:** Must

Pimpinan/Admin yang memiliki permission dapat melihat laporan penjualan.

---

## RPT-002 — Date Filter

Laporan dapat difilter berdasarkan periode.

---

## RPT-003 — Sales Channel

Laporan dapat membedakan:

```text
ONLINE
POS
```

---

## RPT-004 — Product Performance

Sistem dapat menampilkan:

* units sold;
* revenue per product;
* best-selling product;
* best-selling variant.

---

## RPT-005 — Inventory Report

Sistem menyediakan informasi:

* current stock;
* low stock;
* out of stock;
* inventory movement.

---

## RPT-006 — Report Export

**Priority:** Should

Laporan tertentu dapat diekspor ke format yang sesuai seperti PDF/Excel pada fase lanjutan.

---

# 41. SETTINGS

## SET-001 — Store Information

Admin dapat mengatur:

* store name;
* logo;
* contact;
* address;
* social links.

---

## SET-002 — Commerce Settings

Pengaturan dapat mencakup:

* currency;
* low-stock threshold;
* checkout settings;
* order settings.

---

## SET-003 — Visual Content

Admin dapat mengelola konten tertentu seperti:

* homepage hero;
* editorial images;
* featured collection.

Kontrol ini tidak berarti Admin bebas mengubah design system storefront.

---

# 42. ACTIVITY LOG

## LOG-001 — Sensitive Action Logging

**Priority:** Must

Aktivitas sensitif harus dicatat.

Contoh:

```text
Stock Adjustment
Price Change
Product Update
Order Status Change
Cancellation
User Role Change
Settings Change
```

---

## LOG-002 — Log Information

Log minimal mencatat:

* actor;
* action;
* target;
* timestamp;
* relevant changes.

---

# 43. NON-FUNCTIONAL REQUIREMENTS

Non-Functional Requirements mendefinisikan kualitas dan batas teknis sistem.

Kode:

```text
PERF  Performance
SEC   Security
UX    Usability
RES   Responsive
REL   Reliability
DATA  Data Integrity
ACC   Accessibility
SEO   Search Engine Optimization
MNT   Maintainability
OBS   Observability
BKP   Backup & Recovery
```

---

# 44. PERFORMANCE

## PERF-001 — Page Performance

Storefront harus dioptimalkan agar tetap cepat meskipun menggunakan photography berkualitas tinggi.

---

## PERF-002 — Image Optimization

Product images harus mendukung strategi seperti:

* appropriate dimensions;
* compression;
* responsive images;
* lazy loading;
* modern image format apabila memungkinkan.

---

## PERF-003 — Query Efficiency

Database query harus menghindari N+1 query dan penggunaan query berulang yang tidak diperlukan.

---

## PERF-004 — Pagination

Data besar seperti:

* products;
* orders;
* customers;
* transactions;
* logs;

harus menggunakan pagination atau mekanisme pemuatan bertahap.

---

## PERF-005 — Interaction Feedback

Interaksi Livewire yang membutuhkan proses server harus mempunyai loading feedback yang sesuai.

---

# 45. SECURITY

## SEC-001 — Password Security

Password tidak boleh disimpan dalam plain text.

Laravel password hashing digunakan untuk penyimpanan credential.

---

## SEC-002 — Authorization

Setiap protected operation harus diverifikasi berdasarkan authentication dan authorization server-side.

---

## SEC-003 — CSRF Protection

State-changing web request harus menggunakan perlindungan CSRF sesuai mekanisme Laravel.

---

## SEC-004 — Input Validation

Semua data yang diterima server harus divalidasi.

---

## SEC-005 — Payment Security

Informasi sensitif pembayaran tidak boleh disimpan sendiri apabila tidak diperlukan.

Gunakan mekanisme resmi payment provider.

---

## SEC-006 — File Upload Security

Upload product/profile image harus divalidasi berdasarkan:

* file type;
* file size;
* allowed format.

---

## SEC-007 — Rate Limiting

Endpoint sensitif seperti authentication dan endpoint tertentu harus mempunyai rate limiting.

---

## SEC-008 — Activity Trace

Operasi administratif penting harus dapat ditelusuri melalui activity log.

---

# 46. USABILITY

## UX-001 — Clear Navigation

Customer harus dapat memahami navigasi tanpa memerlukan tutorial.

---

## UX-002 — Minimal Checkout Friction

Checkout hanya meminta informasi yang benar-benar diperlukan.

---

## UX-003 — System Feedback

Setiap action penting harus memberikan feedback.

Contoh:

```text
Added to cart

Voucher applied

Payment successful

Something went wrong
```

---

## UX-004 — State Design

Interface harus menyediakan state untuk:

* loading;
* success;
* error;
* empty;
* disabled;
* out-of-stock.

---

## UX-005 — Confirmation

Operasi destructive atau berisiko harus menggunakan confirmation apabila sesuai.

---

# 47. RESPONSIVE REQUIREMENTS

## RES-001

Storefront menggunakan pendekatan mobile-first.

---

## RES-002

Tidak boleh terdapat horizontal scrolling yang tidak disengaja.

---

## RES-003

Product grid harus menyesuaikan viewport.

Baseline awal:

```text
Mobile       2 columns
Tablet       2–3 columns
Desktop      3–4 columns
```

Jumlah final dapat disesuaikan ketika UI design dibuat.

---

## RES-004

POS dan internal dashboard harus tetap usable pada target viewport yang ditentukan.

POS terutama dioptimalkan untuk tablet/desktop apabila perangkat operasional toko menggunakan ukuran tersebut.

---

# 48. RELIABILITY & DATA INTEGRITY

## REL-001 — Transaction Safety

Operasi yang melibatkan beberapa perubahan penting harus menggunakan database transaction apabila diperlukan.

Contoh:

```text
Create POS Transaction
+
Create Items
+
Record Payment
+
Update Stock
+
Create Stock Movement
```

Jika proses kritis gagal, sistem tidak boleh meninggalkan data dalam kondisi parsial.

---

## DATA-001 — Money

Nilai uang tidak boleh menggunakan floating-point yang dapat menghasilkan rounding error.

---

## DATA-002 — Historical Snapshot

Historical transaction tidak boleh bergantung sepenuhnya pada data catalog yang dapat berubah.

Order menyimpan snapshot informasi penting.

---

## DATA-003 — Inventory Integrity

Stock tidak boleh berubah tanpa sumber perubahan yang dapat ditelusuri.

---

## DATA-004 — Unique Identifiers

Data penting seperti:

* SKU;
* order number;
* transaction number;
* voucher code;

harus mempunyai uniqueness constraint yang sesuai.

---

# 49. ACCESSIBILITY

## ACC-001

Text dan background harus memiliki contrast yang memadai.

---

## ACC-002

Interactive element harus mempunyai focus state yang terlihat.

---

## ACC-003

Form harus mempunyai label/error information yang dapat dipahami.

---

## ACC-004

Product image harus mempunyai alternative text yang relevan.

---

## ACC-005

Animasi tidak boleh menjadi satu-satunya cara menyampaikan informasi penting.

---

# 50. SEO

## SEO-001

Public product page mempunyai:

* unique title;
* meta description;
* canonical-friendly URL.

---

## SEO-002

Product dan collection menggunakan readable slug.

---

## SEO-003

Public page structure harus menggunakan semantic HTML sebisa mungkin.

---

## SEO-004

Product images mempunyai meaningful alternative text.

---

# 51. MAINTAINABILITY

## MNT-001

Business logic penting tidak boleh tersebar secara tidak terstruktur di Blade template.

---

## MNT-002

Livewire component digunakan untuk interaksi dinamis yang memang membutuhkannya, bukan sebagai pengganti seluruh arsitektur aplikasi.

---

## MNT-003

Kode harus dipisahkan berdasarkan responsibility yang jelas.

Contoh area:

```text
Catalog
Inventory
Cart
Checkout
Order
Payment
POS
Reporting
```

---

## MNT-004

Database migration, seeder, factory, dan test harus digunakan untuk membantu konsistensi development.

---

# 52. BACKUP & RECOVERY

## BKP-001

Production database harus mempunyai mekanisme backup berkala.

---

## BKP-002

Product media/storage yang diperlukan untuk operasional harus termasuk dalam strategi backup.

---

## BKP-003

Backup harus dapat dipulihkan dan bukan sekadar dibuat.

Strategi detail bergantung pada platform deployment yang nantinya digunakan.

---

# 53. REQUIREMENT PRIORITY SUMMARY

Untuk initial development, requirement dapat dikelompokkan menjadi:

### P0 — Commerce Core

```text
Authentication
Role Authorization

Product
Category
Variant
Variant Images
Size
SKU

Inventory
Stock Movement

Collection
Search

Cart
Checkout
Address
Shipping

Payment
Order
Order History
Cancellation

POS

Basic Notification

Admin Dashboard
Cashier Dashboard
Management Dashboard
```

P0 merupakan fungsi yang membentuk sistem utama 19HOUSE.

### P1 — Commerce Enhancement

```text
Wishlist
Voucher
Advanced Filter
Low Stock Alert
Product Recommendation
Advanced Reporting
Report Export
Notification Enhancement
```

### P2 — Future Growth

```text
Loyalty Program
Product Review
Back-in-Stock Notification
Advanced Analytics
Multi Warehouse
Native Mobile App
AI Recommendation
AR / Virtual Try-On
```

Prioritas dapat berubah berdasarkan kebutuhan bisnis sebelum development sprint dimulai.

---

# 54. DEFINITION OF DONE — FEATURE LEVEL

Sebuah feature tidak dianggap selesai hanya karena interface sudah dapat digunakan.

Feature dinyatakan **Done** apabila:

1. Requirement utama terpenuhi.
2. Authorization diterapkan.
3. Server-side validation diterapkan.
4. Happy path berhasil.
5. Error dan edge case utama ditangani.
6. Loading/empty/error state tersedia jika diperlukan.
7. Responsive behavior sesuai target.
8. Data integrity terjaga.
9. Activity logging diterapkan untuk operasi sensitif.
10. Automated test dibuat untuk business logic yang kritis.
11. Tidak terdapat known blocker pada flow utama.
12. Feature dapat digunakan bersama module terkait tanpa merusak existing flow.

Contohnya, fitur **Add to Cart** belum dianggap selesai hanya karena tombol dapat ditekan.

Feature tersebut baru selesai ketika:

```text
Variant required       ✓
Size required          ✓
Stock validated        ✓
Quantity validated     ✓
Cart updated           ✓
Correct price shown    ✓
Loading state          ✓
Out-of-stock handled   ✓
Mobile behavior        ✓
Server validation      ✓
```

Dengan pendekatan ini, PRD dapat digunakan sebagai acuan development dan QA, bukan hanya sebagai daftar fitur.
