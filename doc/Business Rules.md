# 78. BUSINESS RULES

Business Rules mendefinisikan aturan operasional yang harus dipatuhi sistem 19HOUSE.

Kode rule menggunakan format:

```text
BR-[MODULE]-XXX
```

Contoh:

```text
BR-PRD-001
BR-INV-001
BR-CART-001
BR-ORD-001
```

Business Rules menjadi acuan untuk:

* database design;
* validation;
* backend logic;
* authorization;
* UI state;
* testing;
* audit trail.

---

# 79. PRODUCT BUSINESS RULES

## BR-PRD-001 — Product Identity

Setiap produk harus mempunyai identitas unik di dalam sistem.

Minimal:

* product ID;
* product name;
* slug;
* category;
* description;
* base price;
* status.

---

## BR-PRD-002 — Product Status

Product menggunakan status:

```text
DRAFT
ACTIVE
INACTIVE
ARCHIVED
```

### DRAFT

Produk masih dipersiapkan dan tidak tampil pada storefront.

### ACTIVE

Produk tersedia untuk ditampilkan kepada Customer.

### INACTIVE

Produk sementara tidak ditampilkan tetapi masih dapat dikelola.

### ARCHIVED

Produk tidak lagi digunakan untuk transaksi baru tetapi historical transaction tetap dipertahankan.

---

## BR-PRD-003 — Product Publication

Product hanya dapat tampil di storefront apabila:

```text
Product Status = ACTIVE
AND
memiliki minimal 1 active sellable variant
AND
memiliki product image
```

Produk dengan stok habis tetap boleh ditampilkan apabila strategi bisnis menginginkan produk tetap terlihat.

UI harus menampilkan status:

```text
SOLD OUT
```

---

## BR-PRD-004 — Product Deletion

Product yang sudah pernah digunakan dalam transaksi tidak boleh hard-delete.

Gunakan:

```text
ARCHIVED
```

atau mekanisme soft delete.

Tujuannya menjaga historical transaction.

---

## BR-PRD-005 — Product Price

Setiap produk memiliki base price.

Variant/SKU dapat mempunyai override price apabila diperlukan.

Priority harga:

```text
SKU / Variant Price
        ↓
jika tidak ada
        ↓
Product Base Price
```

---

# 80. CATEGORY BUSINESS RULES

## BR-CAT-001

Setiap product minimal mempunyai satu primary category.

---

## BR-CAT-002

Inactive category tidak tampil pada navigasi storefront.

---

## BR-CAT-003

Menonaktifkan category tidak boleh otomatis menghapus product di dalamnya.

---

## BR-CAT-004

Category dapat memiliki display order untuk mengatur urutan tampilan.

Contoh:

```text
01 NEW ARRIVAL
02 TOPS
03 BOTTOMS
04 OUTERWEAR
05 ACCESSORIES
```

---

# 81. VARIANT BUSINESS RULES

Struktur produk 19HOUSE dibedakan menjadi:

```text
PRODUCT
   ↓
VARIANT
   ↓
SIZE / SELLABLE SKU
```

Contoh:

```text
Essential Oversized Tee
│
├── Black
│   ├── S
│   ├── M
│   └── L
│
└── White
    ├── S
    ├── M
    └── L
```

---

## BR-VAR-001 — Variant

Satu product dapat mempunyai satu atau lebih variant.

Variant umumnya merepresentasikan:

```text
Color
```

namun model data sebaiknya tidak terlalu bergantung pada nama warna agar dapat dikembangkan.

---

## BR-VAR-002 — Variant Name

Variant name harus unik dalam product yang sama.

Tidak boleh:

```text
Essential Tee
├── Black
└── Black
```

---

## BR-VAR-003 — Variant Color

Jika variant menggunakan warna, sistem dapat menyimpan:

* color name;
* optional HEX code.

Contoh:

```text
Color Name : Black
HEX        : #111111
```

HEX digunakan untuk UI swatch dan bukan sebagai pengganti product image.

---

# 82. SIZE BUSINESS RULES

## BR-SIZE-001

Size dikelola sebagai data terstruktur.

Contoh:

```text
XS
S
M
L
XL
XXL
```

---

## BR-SIZE-002

Tidak semua variant wajib memiliki size yang sama.

Contoh:

```text
Black
S M L XL

White
S M L
```

---

## BR-SIZE-003

Size yang tidak tersedia pada variant tetap dapat ditampilkan sebagai disabled state jika berguna untuk UX.

Contoh:

```text
[S] [M] [L] [XL]

XL → Disabled
```

---

## BR-SIZE-004 — Size Guide

Product dapat mempunyai size guide.

Size guide dapat berupa:

* measurement table;
* image;
* textual information.

Contoh:

```text
Size | Chest | Length
S    | 52cm  | 68cm
M    | 55cm  | 71cm
L    | 58cm  | 74cm
```

---

# 83. SKU BUSINESS RULES

## BR-SKU-001

Setiap kombinasi product variant + size yang dapat dijual harus mempunyai SKU unik.

Contoh:

```text
19H-TEE-BLK-S
19H-TEE-BLK-M
19H-TEE-BLK-L
```

---

## BR-SKU-002

SKU tidak boleh digunakan ulang untuk product lain.

---

## BR-SKU-003

SKU yang sudah mempunyai transaction history tidak boleh sembarangan diubah.

Jika perubahan diperlukan, harus mempertimbangkan historical reference.

---

## BR-SKU-004

SKU dapat digunakan untuk:

* inventory;
* POS;
* search;
* barcode;
* reporting;
* order item reference.

---

# 84. PRODUCT IMAGE BUSINESS RULES

Foto variant merupakan requirement penting 19HOUSE.

---

## BR-IMG-001 — Variant-Specific Images

Setiap variant dapat memiliki banyak foto.

Contoh:

```text
BLACK
├── 01 Front
├── 02 Back
├── 03 Detail
└── 04 Model

WHITE
├── 01 Front
├── 02 Back
└── 03 Model
```

---

## BR-IMG-002 — Variant Switching

Ketika Customer memilih variant:

```text
BLACK → WHITE
```

gallery harus berubah:

```text
Black Images
     ↓
White Images
```

tanpa berpindah halaman.

---

## BR-IMG-003 — Primary Variant Image

Setiap variant harus dapat mempunyai satu primary image.

Primary image digunakan untuk:

* product card;
* cart;
* wishlist;
* checkout;
* order presentation.

---

## BR-IMG-004 — Image Order

Admin dapat menentukan urutan foto.

Contoh:

```text
1 Front
2 Model
3 Back
4 Detail
```

---

## BR-IMG-005 — Image Requirement

Active variant yang tampil pada storefront minimal mempunyai satu image.

---

## BR-IMG-006 — Image Delete

Image yang digunakan dalam order tidak perlu disimpan sebagai snapshot file untuk setiap transaksi.

Order cukup menyimpan informasi product snapshot.

Historical image preservation dapat dilakukan melalui media retention apabila diperlukan.

---

# 85. INVENTORY BUSINESS RULES

Inventory merupakan shared resource antara:

```text
ONLINE STORE
+
POS
```

---

## BR-INV-001 — Stock Level

Stock dicatat berdasarkan sellable SKU.

Bukan berdasarkan product secara umum.

Benar:

```text
Black / S = 5
Black / M = 8
Black / L = 2
```

Bukan:

```text
Black = 15
```

sebagai satu-satunya sumber stock.

---

## BR-INV-002 — No Negative Stock

Stock tidak boleh:

```text
< 0
```

---

## BR-INV-003 — Available Stock

Konsep inventory dibedakan menjadi:

```text
On Hand Stock
Reserved Stock
Available Stock
```

Formula:

```text
Available Stock
=
On Hand Stock - Reserved Stock
```

Contoh:

```text
On Hand  : 10
Reserved : 2

Available: 8
```

---

# 86. STOCK RESERVATION

Stock reservation penting untuk mencegah dua Customer membeli unit terakhir secara bersamaan.

## BR-INV-004

Memasukkan product ke cart **tidak langsung mengurangi stock**.

Contoh:

```text
Stock = 5

Customer adds to cart
→ Stock tetap 5
```

Cart bukan jaminan stock.

---

## BR-INV-005

Stock divalidasi ulang ketika Customer memulai proses checkout/order creation.

---

## BR-INV-006

Untuk payment method yang tidak langsung selesai, sistem dapat membuat temporary stock reservation.

Contoh:

```text
Stock On Hand = 5

Order Pending Payment = 2

Reserved = 2

Available = 3
```

---

## BR-INV-007

Stock reservation mempunyai expiry time yang mengikuti payment expiration.

Contoh:

```text
Order created
12:00

Payment Expiry
13:00
```

Jika payment tidak selesai sampai 13:00:

```text
Reservation released
```

---

## BR-INV-008

Jika payment berhasil:

```text
Reserved Stock
      ↓
Converted to Sale
```

---

# 87. STOCK MOVEMENT RULES

## BR-INV-009

Setiap stock change harus mempunyai movement.

Movement types awal:

```text
STOCK_IN
ONLINE_SALE
POS_SALE
RETURN
CANCELLATION
ADJUSTMENT_IN
ADJUSTMENT_OUT
```

---

## BR-INV-010

Stock movement tidak boleh diedit sembarangan setelah dibuat.

Jika terjadi kesalahan, buat corrective movement baru.

---

## BR-INV-011

Stock adjustment wajib mempunyai reason.

Contoh:

```text
Damaged Item
Physical Count Correction
Lost Item
Initial Stock
```

---

# 88. CART BUSINESS RULES

## BR-CART-001

Cart item harus mereferensikan SKU yang spesifik.

Contoh:

```text
Essential Tee
Black
Size M
Qty 2
```

---

## BR-CART-002

Customer tidak dapat Add to Cart sebelum memilih variant dan size jika product membutuhkan keduanya.

---

## BR-CART-003

Quantity minimal:

```text
1
```

---

## BR-CART-004

Quantity cart tidak boleh melebihi available stock pada saat validasi.

---

## BR-CART-005

Cart tidak menjamin harga atau stock.

Sebelum checkout, sistem melakukan revalidation.

---

## BR-CART-006

Jika harga berubah sejak item dimasukkan:

```text
Cart Price
Rp199.000

Current Price
Rp219.000
```

maka cart diperbarui menjadi harga terbaru sebelum checkout.

Customer harus melihat total terbaru.

---

## BR-CART-007

Jika SKU menjadi unavailable, item tetap dapat ditampilkan pada cart tetapi harus diberi status:

```text
Unavailable
```

dan tidak dapat dilanjutkan ke checkout.

---

## BR-CART-008

Jika stock berkurang:

```text
Cart Qty = 3
Available = 2
```

sistem harus meminta quantity disesuaikan.

---

# 89. GUEST CART

Untuk versi awal 19HOUSE, Customer dapat browsing tanpa login.

Rekomendasi:

```text
Browsing    → Guest allowed
Wishlist    → Login recommended/required
Cart        → Guest allowed
Checkout    → Login required atau account creation
```

---

## BR-CART-009

Guest cart dapat disimpan menggunakan session.

---

## BR-CART-010

Ketika Customer login, guest cart dapat digabung dengan authenticated cart.

Jika SKU sama:

```text
Guest Qty 1
Account Qty 2

Result Qty 3
```

selama stock mencukupi.

Jika tidak, quantity dibatasi sesuai available stock.

---

# 90. WISHLIST BUSINESS RULES

## BR-WISH-001

Wishlist hanya tersedia untuk authenticated Customer.

---

## BR-WISH-002

Satu Customer tidak dapat menyimpan product yang sama dua kali dalam wishlist.

---

## BR-WISH-003

Wishlist menyimpan product, bukan SKU, secara default.

Pemilihan variant dilakukan ketika Customer membuka Product Detail.

---

# 91. CHECKOUT BUSINESS RULES

## BR-CHK-001

Customer hanya dapat checkout jika cart memiliki minimal satu valid item.

---

## BR-CHK-002

Sebelum order dibuat, server wajib memvalidasi:

```text
Product active
Variant active
SKU valid
Stock available
Current price
Voucher validity
Shipping
Grand total
Customer address
```

---

## BR-CHK-003

Checkout tidak boleh mempercayai price yang dikirim dari browser.

Server selalu menghitung ulang.

---

## BR-CHK-004

Customer harus memilih:

```text
Shipping Address
Shipping Method
Payment Method
```

sebelum order dibuat.

---

# 92. ADDRESS BUSINESS RULES

## BR-ADR-001

Customer dapat menyimpan beberapa address.

---

## BR-ADR-002

Customer dapat mempunyai satu default shipping address.

---

## BR-ADR-003

Mengubah saved address setelah order dibuat tidak boleh mengubah alamat pada historical order.

Order menyimpan address snapshot.

---

## BR-ADR-004

Address snapshot minimal:

```text
Recipient Name
Phone
Province
City/Regency
District
Postal Code
Full Address
```

---

# 93. SHIPPING BUSINESS RULES

## BR-SHP-001

Shipping method yang tersedia dapat bergantung pada alamat Customer.

---

## BR-SHP-002

Shipping cost merupakan bagian dari order total.

Formula:

```text
Items Subtotal
- Discount
+ Shipping Cost
----------------
Grand Total
```

---

## BR-SHP-003

Tracking number hanya dapat ditambahkan jika order telah memasuki proses shipment.

---

## BR-SHP-004

Saat tracking number tersedia, Customer menerima notification.

---

# 94. VOUCHER BUSINESS RULES

## BR-VCR-001

Voucher mempunyai unique code.

Contoh:

```text
WELCOME19
```

---

## BR-VCR-002

Voucher hanya valid jika:

```text
Active = true
Current Time >= Start Date
Current Time <= End Date
Usage Limit not exceeded
Customer Limit not exceeded
Minimum Order fulfilled
```

---

## BR-VCR-003

Discount type:

```text
FIXED
PERCENTAGE
```

---

## BR-VCR-004

Percentage voucher dapat memiliki maximum discount.

Contoh:

```text
20%
Maximum Discount Rp50.000
```

---

## BR-VCR-005

Satu order hanya menggunakan satu voucher pada initial release.

Voucher stacking berada di luar scope awal.

---

## BR-VCR-006

Voucher harus divalidasi kembali ketika order dibuat.

Bukan hanya saat Customer menekan:

```text
Apply Voucher
```

---

# 95. ORDER BUSINESS RULES

Order dan Payment harus dipisahkan.

Order menggambarkan transaksi barang.

Payment menggambarkan proses pembayaran.

---

## BR-ORD-001 — Order Number

Setiap order memiliki nomor unik.

Contoh:

```text
19H-20260912-00001
```

---

## BR-ORD-002 — Order Item Snapshot

Setiap order item harus menyimpan snapshot:

```text
Product Name
SKU
Variant
Size
Unit Price
Quantity
Discount
Subtotal
```

---

## BR-ORD-003 — Address Snapshot

Order menyimpan shipping address snapshot.

---

# 96. ORDER STATUS

Status order awal:

```text
PENDING_PAYMENT
PAID
PROCESSING
READY_TO_SHIP
SHIPPED
DELIVERED
COMPLETED
CANCELLED
```

---

## BR-ORD-004 — Normal Flow

```text
PENDING_PAYMENT
      ↓
PAID
      ↓
PROCESSING
      ↓
READY_TO_SHIP
      ↓
SHIPPED
      ↓
DELIVERED
      ↓
COMPLETED
```

---

## BR-ORD-005

Status tidak boleh berpindah secara arbitrary.

Tidak boleh:

```text
PENDING_PAYMENT
      ↓
SHIPPED
```

---

## BR-ORD-006

Setiap perubahan status harus menghasilkan:

```text
Order Status History
+
Timestamp
+
Actor
```

---

# 97. PAYMENT BUSINESS RULES

Payment status:

```text
PENDING
PAID
FAILED
EXPIRED
CANCELLED
REFUNDED
PARTIALLY_REFUNDED
```

Walaupun refund mungkin belum diaktifkan pada MVP, model status sebaiknya siap.

---

## BR-PAY-001

Order:

```text
PENDING_PAYMENT
```

dapat memiliki payment:

```text
PENDING
```

---

## BR-PAY-002

Payment dianggap berhasil hanya setelah mendapatkan konfirmasi valid dari payment provider.

Browser redirect Customer bukan sumber kebenaran final.

---

## BR-PAY-003

Webhook/callback harus diverifikasi sesuai mekanisme provider.

---

## BR-PAY-004

Webhook harus idempotent.

Jika payment provider mengirim event yang sama lebih dari sekali:

```text
Callback #1 → Processed
Callback #2 → Ignored safely
Callback #3 → Ignored safely
```

tidak boleh membuat:

* duplicate order;
* duplicate stock movement;
* duplicate notification.

---

## BR-PAY-005

Ketika payment:

```text
PAID
```

maka order dapat berpindah:

```text
PENDING_PAYMENT
→
PAID
```

---

## BR-PAY-006

Jika payment expired:

```text
Payment → EXPIRED
Order   → CANCELLED
Reservation → RELEASED
```

untuk order yang belum dibayar.

---

# 98. PAYMENT ATTEMPT RULE

Satu order dapat memiliki lebih dari satu payment attempt jika fitur retry diaktifkan.

Contoh:

```text
Order #19H001

Payment Attempt 1
FAILED

Payment Attempt 2
PAID
```

Order tetap satu.

Payment record dapat lebih dari satu.

---

# 99. ORDER CANCELLATION BUSINESS RULES

Cancellation dibedakan berdasarkan kondisi order.

---

## BR-CNL-001 — Unpaid Order

Customer dapat membatalkan order selama:

```text
Order = PENDING_PAYMENT
```

Result:

```text
Order → CANCELLED
Payment → CANCELLED
Reservation → RELEASED
```

---

## BR-CNL-002 — Paid but Not Processed

Jika:

```text
Order = PAID
```

Customer dapat mengajukan cancellation request.

Tidak langsung dibatalkan otomatis.

Admin melakukan review.

---

## BR-CNL-003 — Processing Order

Untuk:

```text
PROCESSING
READY_TO_SHIP
```

pembatalan tergantung kebijakan toko.

Default MVP:

```text
Customer cannot auto-cancel
Customer may request support
```

---

## BR-CNL-004 — Shipped Order

Order:

```text
SHIPPED
```

tidak dapat dibatalkan melalui normal cancellation flow.

Jika ada masalah, proses masuk ke return/refund flow pada pengembangan berikutnya.

---

## BR-CNL-005

Cancellation harus menyimpan:

* reason;
* requested_by;
* requested_at;
* reviewed_by;
* reviewed_at;
* status;
* admin note jika ada.

---

# 100. CANCELLATION REQUEST STATUS

```text
REQUESTED
APPROVED
REJECTED
```

---

# 101. REFUND BUSINESS RULES

Refund akan sangat bergantung pada payment gateway.

Baseline rule:

## BR-RFD-001

Refund hanya dapat dilakukan untuk payment yang:

```text
PAID
```

---

## BR-RFD-002

Refund harus mempunyai:

* reason;
* amount;
* actor;
* reference;
* status.

---

## BR-RFD-003

Refund tidak boleh otomatis mengubah stock tanpa mengetahui apakah barang memang dikembalikan.

Payment reversal dan inventory return merupakan dua aktivitas berbeda.

---

# 102. POS BUSINESS RULES

POS menggunakan catalog dan inventory yang sama dengan storefront.

---

## BR-POS-001

Kasir harus login sebelum membuat transaksi.

---

## BR-POS-002

Setiap POS sale merekam:

```text
Cashier
Transaction Number
Items
Payment
Timestamp
```

---

## BR-POS-003

Kasir hanya dapat menjual SKU dengan available stock.

---

## BR-POS-004

POS stock reduction terjadi ketika transaksi berhasil diselesaikan.

---

## BR-POS-005

Jika transaksi gagal sebelum completion:

```text
Stock tidak dikurangi
```

---

# 103. POS PAYMENT

Payment method initial POS:

```text
CASH
QRIS
TRANSFER
```

Dapat dikembangkan sesuai kebutuhan.

---

## BR-POS-006 — Cash

Untuk CASH:

```text
Received Amount >= Grand Total
```

Change:

```text
Received - Total
```

---

## BR-POS-007

Untuk non-cash payment yang membutuhkan confirmation, transaksi hanya dianggap selesai setelah payment memenuhi rule yang ditentukan.

---

# 104. POS CUSTOMER

POS tidak wajib membutuhkan registered Customer.

Kasir dapat membuat:

```text
Walk-in Customer
```

Optional information:

* customer name;
* phone;
* registered customer account.

---

# 105. POS DISCOUNT

Kasir tidak boleh menentukan arbitrary discount tanpa batas.

Discount dapat berupa:

```text
Predefined Promotion
```

atau memerlukan permission:

```text
pos.discount.apply
```

---

# 106. RECEIPT BUSINESS RULES

Setiap transaksi POS berhasil dapat menghasilkan receipt.

Receipt minimal:

```text
19HOUSE

Transaction Number
Date & Time
Cashier

Items
Variant
Size
Qty
Price

Subtotal
Discount
Total

Payment Method
Received
Change

Thank You
```

---

# 107. NOTIFICATION BUSINESS RULES

Notification dibuat berdasarkan event.

Contoh:

```text
EVENT
Payment Paid
      ↓
Notification
"Pembayaran berhasil"
```

---

## BR-NTF-001

Customer notification minimum:

```text
Payment Successful
Payment Failed / Expired
Order Processing
Order Shipped
Order Delivered
Order Completed
Cancellation Result
```

---

## BR-NTF-002

Admin notification minimum:

```text
New Paid Order
Cancellation Request
Low Stock
```

---

## BR-NTF-003

Notification menyimpan:

* recipient;
* type;
* title;
* message;
* related resource;
* read_at;
* created_at.

---

## BR-NTF-004

Notification harus dapat mengarahkan user ke resource yang relevan jika resource masih tersedia.

Contoh:

```text
Your order has shipped
      ↓
/account/orders/19H001
```

---

# 108. LOW STOCK NOTIFICATION

## BR-NTF-005

Jika stock turun hingga threshold:

```text
Stock <= Low Stock Threshold
```

sistem dapat membuat alert.

---

## BR-NTF-006

Sistem harus mencegah spam notification yang sama berulang kali setiap kali halaman dibuka.

Low-stock notification dihasilkan berdasarkan event stock change.

---

# 109. ORDER NOTIFICATION EVENT MAP

```text
PAYMENT PAID
→ Customer: Payment successful
→ Admin: New paid order

ORDER PROCESSING
→ Customer: Order is being prepared

ORDER SHIPPED
→ Customer: Order shipped + tracking number

ORDER DELIVERED
→ Customer: Order delivered

CANCELLATION REQUEST
→ Admin: New cancellation request

CANCELLATION APPROVED
→ Customer: Cancellation approved
```

---

# 110. DASHBOARD BUSINESS RULES

Dashboard hanya menampilkan data yang sesuai role.

---

## Admin

Fokus:

```text
Operational Status
```

Contoh:

* paid orders awaiting processing;
* low stock;
* new orders;
* recent transactions.

---

## Kasir

Fokus:

```text
Daily POS Operation
```

---

## Pimpinan

Fokus:

```text
Business Performance
```

---

## Customer

Fokus:

```text
Personal Shopping Activity
```

---

# 111. REPORTING BUSINESS RULES

## BR-RPT-001

Revenue hanya menghitung transaksi yang memenuhi definisi revenue bisnis.

Default:

```text
Paid / Completed Transaction
```

Cancelled unpaid order tidak dihitung.

---

## BR-RPT-002

Online dan POS harus dapat dipisahkan menggunakan sales channel.

```text
ONLINE
POS
```

---

## BR-RPT-003

Report date range menggunakan timezone bisnis yang konsisten.

---

## BR-RPT-004

Historical report menggunakan transaction snapshot, bukan current catalog price.

---

# 112. SALES CHANNEL

Setiap transaksi harus mempunyai channel:

```text
ONLINE
POS
```

Hal ini dibutuhkan untuk:

* reporting;
* audit;
* dashboard;
* sales comparison.

---

# 113. ORDER PRICE CALCULATION

Formula dasar:

```text
Item Subtotal
      ↓
Product / Order Discount
      ↓
Voucher
      ↓
Shipping
      ↓
Grand Total
```

Secara konseptual:

```text
subtotal =
Σ(unit_price × quantity)

discount_total =
promotion_discount + voucher_discount

grand_total =
subtotal - discount_total + shipping_cost
```

Grand total tidak boleh negatif.

---

# 114. MONEY BUSINESS RULES

Currency awal:

```text
IDR
```

---

## BR-MNY-001

Semua nilai uang disimpan dalam integer smallest practical unit.

Untuk IDR:

```text
Rp199.000
→
199000
```

Tidak menggunakan floating-point untuk nilai transaksi utama.

---

# 115. TIMESTAMP BUSINESS RULES

Semua transaction record harus mempunyai timestamp.

Contoh:

```text
created_at
updated_at
paid_at
processed_at
shipped_at
delivered_at
completed_at
cancelled_at
```

Tidak semua field harus berada langsung pada order jika status history sudah menjadi sumber yang lebih tepat.

---

# 116. AUDIT RULES

Aktivitas berikut wajib dapat ditelusuri:

```text
Product price update
Product archive
Stock adjustment
Order manual status update
Cancellation approval/rejection
Refund
Role update
User deactivation
Settings update
```

Audit minimal:

```text
Who
What
When
Resource
Before
After
```

untuk perubahan yang relevan.

---

# 117. CONCURRENCY RULES

Sistem harus mempertimbangkan dua transaksi terjadi pada waktu yang hampir bersamaan.

Contoh:

```text
Available Stock = 1

Customer A checkout
Customer B checkout
```

Sistem tidak boleh menghasilkan:

```text
Stock = -1
```

Stock validation dan mutation harus dilakukan secara aman menggunakan database transaction/locking strategy yang sesuai.

---

# 118. TRANSACTION ATOMICITY

Operasi penting harus bersifat atomic.

Contoh POS:

```text
Create Transaction
+
Create Items
+
Record Payment
+
Reduce Stock
+
Create Stock Movements
```

Jika salah satu critical operation gagal:

```text
ROLLBACK
```

Sistem tidak boleh menghasilkan transaksi setengah selesai.

---

# 119. PRODUCT VISIBILITY RULE

Produk dapat tampil dalam kondisi:

### In Stock

Normal purchase.

### Low Stock

Tetap dapat dibeli.

Optional UI:

```text
Only 2 left
```

### Out of Stock

Produk tetap dapat dilihat tetapi Add to Cart disabled untuk SKU tersebut.

### Inactive

Tidak tersedia pada storefront.

---

# 120. SEARCH BUSINESS RULES

Search minimal mempertimbangkan:

```text
Product Name
Category
SKU (internal/POS)
```

SKU tidak harus ditampilkan sebagai search keyword utama untuk Customer.

---

# 121. SORTING BUSINESS RULES

Supported initial sorting:

```text
Newest
Price Low → High
Price High → Low
Best Selling
```

Best Selling dihitung dari valid sales, bukan page views.

---

# 122. ACCOUNT BUSINESS RULES

Customer account menyimpan data profile terpisah dari historical order snapshot.

Jika Customer mengganti:

```text
Name
Phone
Address
```

historical order tidak berubah.

---

# 123. CUSTOMER DATA RULE

Sistem hanya meminta data Customer yang diperlukan untuk:

* account;
* transaction;
* shipping;
* communication;
* operational needs.

Data sensitif yang tidak diperlukan tidak boleh dikumpulkan.

---

# 124. ERROR HANDLING RULES

Error harus menggunakan pesan yang dapat dipahami user.

Tidak:

```text
SQLSTATE[23000]...
```

kepada Customer.

Gunakan:

```text
Stok untuk ukuran M baru saja habis.
Silakan pilih ukuran lain.
```

Technical detail disimpan pada server log.

---

# 125. EMPTY STATE RULES

Setiap halaman yang dapat kosong harus memiliki empty state.

Contoh:

### Wishlist

```text
Your wishlist is empty.
Explore the collection.
```

### Orders

```text
You haven't placed any orders yet.
```

### Notifications

```text
No notifications yet.
```

---

# 126. SOFT DELETE RULES

Entity yang memiliki historical transaction reference sebaiknya menggunakan archive/soft delete.

Contoh:

```text
Products
Variants
Users
```

Entity yang dihapus tidak boleh menyebabkan historical transaction kehilangan konteks.

---

# 127. DEFAULT MVP BUSINESS DECISIONS

Untuk mencegah ambiguity saat development, initial version 19HOUSE menggunakan keputusan berikut:

```text
Currency
IDR

Sales Channels
ONLINE + POS

Customer Cart
Guest allowed

Checkout
Authentication required

Wishlist
Authentication required

Voucher per order
Maximum 1

Voucher stacking
Disabled

Shared Inventory
Enabled

Stock reservation
Enabled during pending payment

Negative stock
Forbidden

Variant images
Required

Payment/order status
Separated

Online + POS reports
Separated and combined

Management
Read-only

Hard delete transactional products
Forbidden

Native App
Not included
```

---

# 128. BUSINESS RULE ACCEPTANCE CHECKLIST

Sebelum commerce flow dianggap siap, sistem minimal harus berhasil menangani skenario berikut.

### Product

```text
✓ Product memiliki variant
✓ Variant memiliki foto sendiri
✓ Variant memiliki size
✓ SKU unik
✓ Product inactive tidak dapat dibeli
```

### Inventory

```text
✓ Stock tersimpan per SKU
✓ Online dan POS berbagi stock
✓ Stock tidak negatif
✓ Semua perubahan tercatat
✓ Concurrent purchase aman
```

### Cart

```text
✓ Variant wajib dipilih
✓ Size wajib dipilih
✓ Quantity tervalidasi
✓ Price tervalidasi ulang
✓ Out-of-stock ditangani
```

### Checkout

```text
✓ Address valid
✓ Shipping valid
✓ Voucher valid
✓ Price dihitung server
✓ Stock divalidasi
```

### Payment

```text
✓ Pending payment tercatat
✓ Webhook diverifikasi
✓ Duplicate webhook aman
✓ Payment expiration ditangani
```

### Order

```text
✓ Order number unik
✓ Product snapshot tersimpan
✓ Address snapshot tersimpan
✓ Status transition terkontrol
✓ Status history tercatat
```

### Cancellation

```text
✓ Unpaid order dapat dibatalkan
✓ Paid cancellation melalui review
✓ Stock reservation dikembalikan
✓ Reason tersimpan
```

### POS

```text
✓ Cashier tercatat
✓ Stock terintegrasi
✓ Payment tercatat
✓ Receipt tersedia
✓ Transaction atomic
```

### Notification

```text
✓ Payment notification
✓ Order notification
✓ Shipping notification
✓ Low-stock notification
✓ Read/unread state
```

---

# 129. CORE COMMERCE STATE MODEL

Secara keseluruhan, flow utama online commerce menjadi:

```text
CUSTOMER
   │
   ▼
PRODUCT
   │
   ▼
VARIANT + SIZE
   │
   ▼
CART
   │
   ▼
CHECKOUT
   │
   ├── Address
   ├── Shipping
   └── Voucher
   │
   ▼
ORDER CREATED
   │
   ▼
PENDING PAYMENT
   │
   ├──────── Payment Failed
   │               │
   │               ▼
   │            RETRY
   │
   ├──────── Payment Expired
   │               │
   │               ▼
   │           CANCELLED
   │
   ▼
PAYMENT PAID
   │
   ▼
PROCESSING
   │
   ▼
READY TO SHIP
   │
   ▼
SHIPPED
   │
   ▼
DELIVERED
   │
   ▼
COMPLETED
```

Flow tersebut akan menjadi dasar untuk:

* database relationship;
* status enum;
* backend service;
* notification event;
* UI state;
* integration test.

---

# 130. POS STATE MODEL

```text
CASHIER
   │
   ▼
OPEN POS
   │
   ▼
SEARCH / SELECT SKU
   │
   ▼
POS CART
   │
   ▼
PAYMENT
   │
   ├── Failed
   │     ↓
   │   Retry
   │
   ▼
SUCCESS
   │
   ├── Transaction saved
   ├── Payment recorded
   ├── Inventory reduced
   ├── Stock movement created
   └── Receipt generated
```

Dengan aturan ini, storefront, checkout, payment, inventory, POS, dan reporting memiliki sumber aturan yang konsisten dan dapat diterjemahkan langsung ke implementasi Laravel.
