# 297. DATABASE DESIGN

Database 19HOUSE harus mendukung:

```text
Customer Commerce
Catalog
Variant & Size
Variant Images
Inventory
Cart
Checkout
Order
Payment
Shipping
Voucher
Notification
POS
Reporting
Audit
```

Database menggunakan MySQL dan dirancang agar:

* normalized;
* aman untuk historical transaction;
* mendukung shared inventory;
* mudah dikembangkan;
* konsisten dengan business rules;
* tidak bergantung pada UI.

---

# 298. DATABASE DESIGN PRINCIPLES

## 298.1 Transaction Data Must Be Historical

Data transaksi tidak boleh berubah hanya karena master data berubah.

Contoh:

```text
Product Price Today
Rp199.000

Product Price Next Month
Rp229.000
```

Order lama harus tetap:

```text
Rp199.000
```

Karena order menyimpan snapshot.

---

## 298.2 Inventory Per Sellable SKU

Inventory tidak disimpan hanya pada product.

Struktur:

```text
Product
   ↓
Variant
   ↓
Size / SKU
   ↓
Stock
```

---

## 298.3 Soft Delete / Archive

Data yang memiliki historical reference tidak dihapus secara permanen.

Contoh:

```text
products
product_variants
users
```

---

## 298.4 Shared Inventory

Online Store dan POS menggunakan table inventory yang sama.

---

## 298.5 Payment and Order Separated

Order dan Payment merupakan domain berbeda.

```text
ORDER
= barang + customer + fulfillment

PAYMENT
= proses pembayaran
```

---

# 299. CORE TABLE LIST

Struktur awal database:

```text
users
roles
permissions
role_user
permission_role

customer_addresses

categories

products
product_variants
sizes
product_skus
product_variant_images

inventory_stocks
inventory_movements

carts
cart_items

wishlists

orders
order_items
order_status_histories

payments

shipments

cancellation_requests

promotions
vouchers
voucher_usages

notifications

pos_transactions
pos_transaction_items

activity_logs

settings
```

Beberapa tabel Laravel bawaan juga dapat digunakan:

```text
password_reset_tokens
sessions
jobs
failed_jobs
cache
```

jika dibutuhkan.

---

# 300. USERS TABLE

Table:

```text
users
```

Suggested columns:

```text
id
name
email
phone
password
profile_photo_path
status
email_verified_at
remember_token
created_at
updated_at
deleted_at
```

Status:

```text
ACTIVE
INACTIVE
```

---

# 301. ROLES TABLE

```text
roles
```

Columns:

```text
id
name
slug
created_at
updated_at
```

Example:

```text
Admin
Cashier
Management
Customer
```

Slug:

```text
admin
cashier
management
customer
```

---

# 302. USER ROLE RELATIONSHIP

Untuk sistem sederhana, `role_id` dapat langsung ditempatkan di `users`.

Namun untuk flexibility lebih baik:

```text
role_user
```

Columns:

```text
id
user_id
role_id
created_at
```

Cardinality:

```text
users
M : N
roles
```

Tetapi jika 19HOUSE hanya mengizinkan satu role per user, desain lebih sederhana dapat berupa:

```text
users.role_id
```

Rekomendasi awal 19HOUSE:

```text
1 user = 1 primary role
```

Gunakan:

```text
users.role_id
```

Permission tetap granular.

---

# 303. PERMISSIONS TABLE

```text
permissions
```

Columns:

```text
id
name
slug
created_at
updated_at
```

Example:

```text
View Products
Create Products
Adjust Inventory
View Reports
```

---

# 304. ROLE PERMISSION TABLE

```text
permission_role
```

Columns:

```text
role_id
permission_id
```

Relationship:

```text
roles
M : N
permissions
```

---

# 305. CUSTOMER ADDRESSES TABLE

```text
customer_addresses
```

Columns:

```text
id
user_id
label
recipient_name
phone
province
city
district
postal_code
address_line
is_default
created_at
updated_at
```

Example labels:

```text
Home
Office
Other
```

Relationship:

```text
users
1 : N
customer_addresses
```

---

# 306. CATEGORIES TABLE

```text
categories
```

Columns:

```text
id
name
slug
description
image_path
status
display_order
created_at
updated_at
deleted_at
```

Optional:

```text
parent_id
```

jika category hierarchy nantinya dibutuhkan.

Untuk MVP:

```text
parent_id
```

belum wajib.

---

# 307. PRODUCTS TABLE

```text
products
```

Columns:

```text
id
category_id
name
slug
description
base_price
status
is_featured
published_at
created_at
updated_at
deleted_at
```

Status:

```text
DRAFT
ACTIVE
INACTIVE
ARCHIVED
```

Relationship:

```text
categories
1 : N
products
```

---

# 308. PRODUCT VARIANTS TABLE

```text
product_variants
```

Variant merepresentasikan color/style option.

Columns:

```text
id
product_id
name
color_name
color_hex
status
display_order
created_at
updated_at
deleted_at
```

Example:

```text
Black
White
Navy
```

Relationship:

```text
products
1 : N
product_variants
```

---

# 309. SIZES TABLE

```text
sizes
```

Columns:

```text
id
name
code
display_order
status
created_at
updated_at
```

Example:

```text
XS
S
M
L
XL
XXL
```

`code` dapat sama dengan `name`.

---

# 310. PRODUCT SKUS TABLE

Table paling penting untuk sellable item:

```text
product_skus
```

Columns:

```text
id
product_variant_id
size_id
sku
barcode
price_override
status
created_at
updated_at
deleted_at
```

Example:

```text
19H-TEE-BLK-M
```

Relationship:

```text
product_variants
1 : N
product_skus

sizes
1 : N
product_skus
```

Unique constraint:

```text
sku UNIQUE
```

Suggested additional unique constraint:

```text
product_variant_id + size_id
```

agar size yang sama tidak dibuat dua kali pada variant yang sama.

---

# 311. WHY PRODUCT_SKUS IS IMPORTANT

Jangan menyimpan struktur seperti:

```text
product_variants
├── stock_s
├── stock_m
├── stock_l
└── stock_xl
```

Karena sulit dikembangkan.

Gunakan:

```text
product_skus

Black / S
Black / M
Black / L
White / S
White / M
White / L
```

Setiap SKU menjadi unit inventory yang independen.

---

# 312. PRODUCT VARIANT IMAGES TABLE

```text
product_variant_images
```

Columns:

```text
id
product_variant_id
image_path
alt_text
is_primary
display_order
created_at
updated_at
```

Relationship:

```text
product_variants
1 : N
product_variant_images
```

Rule:

```text
1 variant
→ many images

1 variant
→ maximum 1 primary image
```

---

# 313. INVENTORY STOCKS TABLE

```text
inventory_stocks
```

Columns:

```text
id
product_sku_id
on_hand
reserved
low_stock_threshold
updated_at
```

Relationship:

```text
product_skus
1 : 1
inventory_stocks
```

Available stock:

```text
available =
on_hand - reserved
```

Tidak harus disimpan sebagai field karena dapat dihitung.

---

# 314. INVENTORY MOVEMENTS TABLE

```text
inventory_movements
```

Columns:

```text
id
product_sku_id
type
quantity
stock_before
stock_after
reference_type
reference_id
reason
created_by
created_at
```

Type:

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

# 315. INVENTORY MOVEMENT POLYMORPHIC REFERENCE

`reference_type` dan `reference_id` memungkinkan movement berkaitan dengan:

```text
Order
POS Transaction
Stock Adjustment
Return
```

Contoh:

```text
reference_type = orders
reference_id   = 1001
```

atau gunakan Laravel morph relation:

```text
reference_type
reference_id
```

---

# 316. INVENTORY RELATIONSHIP

```text
product_skus
     │
     ├──── 1 : 1 ─── inventory_stocks
     │
     └──── 1 : N ─── inventory_movements
```

---

# 317. CARTS TABLE

```text
carts
```

Columns:

```text
id
user_id
session_id
status
created_at
updated_at
```

Possible status:

```text
ACTIVE
CONVERTED
ABANDONED
```

For guest:

```text
user_id = NULL
session_id = ...
```

For authenticated Customer:

```text
user_id = customer ID
```

---

# 318. CART ITEMS TABLE

```text
cart_items
```

Columns:

```text
id
cart_id
product_sku_id
quantity
created_at
updated_at
```

Relationship:

```text
carts
1 : N
cart_items

product_skus
1 : N
cart_items
```

Unique recommendation:

```text
cart_id + product_sku_id
```

Jika SKU sama ditambahkan lagi:

```text
quantity += x
```

bukan membuat duplicate row.

---

# 319. WISHLISTS TABLE

Simplest design:

```text
wishlists
```

Columns:

```text
id
user_id
product_id
created_at
```

Unique:

```text
user_id + product_id
```

Relationship:

```text
users
M : N
products
```

melalui wishlist.

---

# 320. ORDERS TABLE

```text
orders
```

Suggested columns:

```text
id
order_number
user_id
channel

status
payment_status

recipient_name
recipient_phone
shipping_province
shipping_city
shipping_district
shipping_postal_code
shipping_address

subtotal
discount_total
shipping_cost
grand_total

voucher_id
voucher_code_snapshot

notes

paid_at
processed_at
shipped_at
delivered_at
completed_at
cancelled_at

created_at
updated_at
```

Channel:

```text
ONLINE
POS
```

Namun POS dapat menggunakan tabel transaction terpisah.

Jika tetap terpisah, `orders.channel` cukup untuk online order atau dapat tetap disiapkan untuk future unification.

---

# 321. ORDER NUMBER

Unique index:

```text
order_number UNIQUE
```

Example:

```text
19H-20260912-00001
```

---

# 322. ORDER STATUS

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

Keduanya tetap dipisahkan.

---

# 323. ORDER ITEMS TABLE

```text
order_items
```

Columns:

```text
id
order_id
product_sku_id

product_name_snapshot
sku_snapshot
variant_snapshot
size_snapshot

unit_price
quantity
discount_amount
subtotal

created_at
```

Relationship:

```text
orders
1 : N
order_items
```

---

# 324. WHY ORDER SNAPSHOT IS REQUIRED

Misalnya product diubah:

```text
Essential Tee
↓
Essential Oversized Tee
```

atau harga berubah:

```text
199000
↓
229000
```

Order lama tetap harus menampilkan data saat transaksi terjadi.

---

# 325. ORDER STATUS HISTORIES TABLE

```text
order_status_histories
```

Columns:

```text
id
order_id
status
note
actor_type
actor_id
created_at
```

Actor dapat berupa:

```text
USER
SYSTEM
PAYMENT_GATEWAY
```

---

# 326. PAYMENTS TABLE

```text
payments
```

Columns:

```text
id
order_id
payment_reference
provider
method
amount
status
provider_transaction_id
payment_url
expires_at
paid_at
failed_at
metadata
created_at
updated_at
```

Relationship:

```text
orders
1 : N
payments
```

Karena satu order dapat memiliki multiple payment attempts.

---

# 327. PAYMENT REFERENCE

Unique:

```text
payment_reference
```

Payment provider transaction ID juga sebaiknya unique jika provider menjamin uniqueness.

---

# 328. PAYMENT METADATA

Field:

```text
metadata JSON
```

dapat menyimpan technical data dari payment provider yang memang relevan.

Jangan menyimpan data kartu/payment credential sensitif.

---

# 329. SHIPMENTS TABLE

```text
shipments
```

Columns:

```text
id
order_id
courier
service
tracking_number
shipping_cost
status
shipped_at
delivered_at
metadata
created_at
updated_at
```

Relationship:

```text
orders
1 : 1
shipments
```

Untuk MVP satu shipment per order.

Future dapat diubah menjadi:

```text
1 : N
```

jika split shipment diperlukan.

---

# 330. CANCELLATION REQUESTS TABLE

```text
cancellation_requests
```

Columns:

```text
id
order_id
requested_by
reason
status
reviewed_by
admin_note
requested_at
reviewed_at
created_at
updated_at
```

Status:

```text
REQUESTED
APPROVED
REJECTED
```

Relationship:

```text
orders
1 : N
cancellation_requests
```

Walaupun biasanya satu request aktif, history sebaiknya tetap bisa dipertahankan.

---

# 331. PROMOTIONS TABLE

```text
promotions
```

Untuk promotion otomatis.

Columns:

```text
id
name
type
value
start_at
end_at
status
conditions
created_at
updated_at
```

`conditions` dapat menggunakan JSON apabila rule promotion berkembang.

MVP dapat menggunakan struktur sederhana terlebih dahulu.

---

# 332. VOUCHERS TABLE

```text
vouchers
```

Columns:

```text
id
code
name
discount_type
discount_value
minimum_purchase
maximum_discount
usage_limit
usage_limit_per_user
used_count
start_at
end_at
status
created_at
updated_at
```

Discount type:

```text
FIXED
PERCENTAGE
```

Unique:

```text
code
```

---

# 333. VOUCHER USAGES TABLE

```text
voucher_usages
```

Columns:

```text
id
voucher_id
user_id
order_id
discount_amount
used_at
```

Relationship:

```text
vouchers
1 : N
voucher_usages

users
1 : N
voucher_usages
```

---

# 334. NOTIFICATIONS TABLE

Laravel notification database table dapat digunakan.

```text
notifications
```

Laravel default structure:

```text
id
type
notifiable_type
notifiable_id
data
read_at
created_at
updated_at
```

Ini sudah cocok untuk notification polymorphic.

---

# 335. POS TRANSACTIONS TABLE

```text
pos_transactions
```

Columns:

```text
id
transaction_number
cashier_id
customer_id
subtotal
discount_total
grand_total
payment_method
amount_received
change_amount
status
completed_at
created_at
updated_at
```

Status:

```text
DRAFT
COMPLETED
CANCELLED
```

---

# 336. POS TRANSACTION ITEMS TABLE

```text
pos_transaction_items
```

Columns:

```text
id
pos_transaction_id
product_sku_id

product_name_snapshot
sku_snapshot
variant_snapshot
size_snapshot

unit_price
quantity
discount_amount
subtotal

created_at
```

Relationship:

```text
pos_transactions
1 : N
pos_transaction_items
```

---

# 337. POS CUSTOMER RELATIONSHIP

`customer_id` boleh NULL.

```text
NULL
=
Walk-in Customer
```

Jika Customer terdaftar:

```text
customer_id
=
users.id
```

---

# 338. POS CASHIER RELATIONSHIP

```text
users
1 : N
pos_transactions
```

melalui:

```text
cashier_id
```

---

# 339. POS STOCK MOVEMENT

Setiap POS item berhasil:

```text
pos_transaction_item
     ↓
inventory_movement
type = POS_SALE
```

Reference:

```text
reference_type = pos_transaction
reference_id   = transaction ID
```

---

# 340. ACTIVITY LOGS TABLE

```text
activity_logs
```

Columns:

```text
id
user_id
action
subject_type
subject_id
description
old_values
new_values
ip_address
user_agent
created_at
```

`old_values` dan `new_values` dapat menggunakan JSON.

---

# 341. SETTINGS TABLE

```text
settings
```

Columns:

```text
id
key
value
type
group
created_at
updated_at
```

Example:

```text
store.name
store.phone
commerce.currency
inventory.low_stock_threshold
```

Sensitive credentials sebaiknya tidak disimpan sembarangan di table settings.

Gunakan `.env`/secret management untuk credential penting.

---

# 342. OPTIONAL CONTENT TABLES

Untuk homepage editorial yang dapat dikelola Admin, dapat ditambahkan:

```text
banners
homepage_sections
collections
collection_products
```

---

# 343. COLLECTIONS TABLE

Jika 19HOUSE membutuhkan curated collection seperti:

```text
Summer Drop
Core Collection
19 Essentials
```

gunakan:

```text
collections
```

Columns:

```text
id
name
slug
description
image_path
status
display_order
published_at
created_at
updated_at
```

---

# 344. COLLECTION PRODUCTS

```text
collection_products
```

Columns:

```text
collection_id
product_id
display_order
```

Relationship:

```text
collections
M : N
products
```

---

# 345. RECOMMENDED FINAL TABLE SET

Setelah memasukkan collection, recommended structure:

```text
AUTH & USERS
├── users
├── roles
├── permissions
└── permission_role

CUSTOMER
├── customer_addresses
└── wishlists

CATALOG
├── categories
├── collections
├── collection_products
├── products
├── product_variants
├── sizes
├── product_skus
└── product_variant_images

INVENTORY
├── inventory_stocks
└── inventory_movements

CART
├── carts
└── cart_items

ONLINE SALES
├── orders
├── order_items
├── order_status_histories
├── payments
├── shipments
└── cancellation_requests

MARKETING
├── promotions
├── vouchers
└── voucher_usages

POS
├── pos_transactions
└── pos_transaction_items

SYSTEM
├── notifications
├── activity_logs
└── settings
```

---

# 346. CORE ERD

High-level ERD:

```text
ROLES
  │
  │ 1
  │
  │ N
USERS
  │
  ├───────────────┐
  │               │
  │ 1             │ 1
  │               │
  │ N             │ N
ADDRESSES       ORDERS
                  │
                  │ 1
                  │
                  │ N
              ORDER_ITEMS
                  │
                  │ N
                  │
                  │ 1
              PRODUCT_SKUS
                  │
          ┌───────┴────────┐
          │                │
          │ N              │ N
          │                │
          │ 1              │ 1
       SIZES         PRODUCT_VARIANTS
                          │
                          │ N
                          │
                          │ 1
                       PRODUCTS
                          │
                          │ N
                          │
                          │ 1
                      CATEGORIES
```

---

# 347. PRODUCT ERD

```text
CATEGORIES
    │
    │ 1
    │
    │ N
PRODUCTS
    │
    │ 1
    │
    │ N
PRODUCT_VARIANTS
    │
    ├───────────────┐
    │               │
    │ 1             │ 1
    │               │
    │ N             │ N
PRODUCT_SKUS     VARIANT_IMAGES
    │
    │ N
    │
    │ 1
  SIZES
```

---

# 348. INVENTORY ERD

```text
PRODUCT_SKUS
    │
    ├──────── 1 : 1 ─────── INVENTORY_STOCKS
    │
    └──────── 1 : N ─────── INVENTORY_MOVEMENTS
```

---

# 349. CART ERD

```text
USERS
   │
   │ 1
   │
   │ N
 CARTS
   │
   │ 1
   │
   │ N
CART_ITEMS
   │
   │ N
   │
   │ 1
PRODUCT_SKUS
```

Guest cart:

```text
carts.user_id = NULL
```

dan menggunakan:

```text
session_id
```

---

# 350. ORDER ERD

```text
USERS
  │
  │ 1
  │
  │ N
ORDERS
  │
  ├──────── 1 : N ─────── ORDER_ITEMS
  │                         │
  │                         N
  │                         │
  │                         1
  │                     PRODUCT_SKUS
  │
  ├──────── 1 : N ─────── PAYMENTS
  │
  ├──────── 1 : 1 ─────── SHIPMENTS
  │
  ├──────── 1 : N ─────── STATUS_HISTORIES
  │
  └──────── 1 : N ─────── CANCELLATION_REQUESTS
```

---

# 351. VOUCHER ERD

```text
VOUCHERS
   │
   │ 1
   │
   │ N
VOUCHER_USAGES
   │
   ├──── N : 1 ─── USERS
   │
   └──── N : 1 ─── ORDERS
```

---

# 352. POS ERD

```text
USERS
(CASHIER)
    │
    │ 1
    │
    │ N
POS_TRANSACTIONS
    │
    │ 1
    │
    │ N
POS_TRANSACTION_ITEMS
    │
    │ N
    │
    │ 1
PRODUCT_SKUS
```

---

# 353. OMNICHANNEL DATABASE RELATION

Online dan POS bertemu pada:

```text
                  PRODUCT_SKUS
                       │
                       ▼
                INVENTORY_STOCKS
                       │
                       ▼
              INVENTORY_MOVEMENTS
                    ▲       ▲
                    │       │
             ONLINE SALE   POS SALE
                    │       │
                 ORDERS   POS_TRANSACTIONS
```

Inilah bagian yang memastikan stock tidak terpisah.

---

# 354. CORE CARDINALITIES

| Entity A        | Relation | Entity B              |
| --------------- | -------- | --------------------- |
| Role            | 1:N      | User                  |
| User            | 1:N      | Customer Address      |
| Category        | 1:N      | Product               |
| Product         | 1:N      | Product Variant       |
| Product Variant | 1:N      | Product Variant Image |
| Product Variant | 1:N      | Product SKU           |
| Size            | 1:N      | Product SKU           |
| Product SKU     | 1:1      | Inventory Stock       |
| Product SKU     | 1:N      | Inventory Movement    |
| User            | 1:N      | Cart                  |
| Cart            | 1:N      | Cart Item             |
| Product SKU     | 1:N      | Cart Item             |
| User            | N:M      | Product via Wishlist  |
| User            | 1:N      | Order                 |
| Order           | 1:N      | Order Item            |
| Product SKU     | 1:N      | Order Item            |
| Order           | 1:N      | Payment               |
| Order           | 1:1      | Shipment              |
| Order           | 1:N      | Status History        |
| Order           | 1:N      | Cancellation Request  |
| Voucher         | 1:N      | Voucher Usage         |
| User            | 1:N      | Voucher Usage         |
| Order           | 1:N      | Voucher Usage         |
| Cashier User    | 1:N      | POS Transaction       |
| POS Transaction | 1:N      | POS Transaction Item  |
| Product SKU     | 1:N      | POS Transaction Item  |
| Collection      | N:M      | Product               |

---

# 355. IMPORTANT UNIQUE CONSTRAINTS

Recommended:

```text
users.email UNIQUE

categories.slug UNIQUE

products.slug UNIQUE

product_skus.sku UNIQUE

product_variant_id + size_id UNIQUE

wishlists(user_id, product_id) UNIQUE

cart_items(cart_id, product_sku_id) UNIQUE

orders.order_number UNIQUE

payments.payment_reference UNIQUE

vouchers.code UNIQUE

pos_transactions.transaction_number UNIQUE

collection_products(collection_id, product_id) UNIQUE
```

---

# 356. IMPORTANT INDEXES

Tambahkan index pada field yang sering digunakan untuk search/filter.

Example:

```text
products.status
products.category_id
products.created_at

product_skus.sku

inventory_movements.product_sku_id
inventory_movements.created_at

orders.user_id
orders.status
orders.payment_status
orders.created_at

payments.status
payments.provider_transaction_id

pos_transactions.cashier_id
pos_transactions.created_at

notifications.notifiable_id
notifications.read_at
```

---

# 357. MONEY DATA TYPES

Untuk IDR gunakan integer/big integer.

Contoh:

```text
base_price BIGINT UNSIGNED
grand_total BIGINT UNSIGNED
```

Example:

```text
Rp199.000
=
199000
```

Hindari:

```text
FLOAT
DOUBLE
```

untuk nilai uang utama.

---

# 358. QUANTITY DATA TYPES

Stock:

```text
INT UNSIGNED
```

untuk kondisi normal.

Jika inventory sangat besar, gunakan BIGINT, tetapi untuk fashion retail biasa INT cukup.

---

# 359. ENUM VS STRING

Status dapat menggunakan:

```text
VARCHAR
```

dan PHP Enum.

Rekomendasi:

```text
Database → VARCHAR
Laravel → backed Enum
```

Example:

```text
OrderStatus::PAID
OrderStatus::PROCESSING
```

lebih fleksibel dibanding MySQL ENUM jika status berkembang.

---

# 360. JSON USAGE

JSON digunakan hanya bila struktur memang fleksibel.

Suitable:

```text
payments.metadata
activity_logs.old_values
activity_logs.new_values
promotion.conditions
```

Jangan menyimpan core relational data seperti:

```text
sizes = ["S","M","L"]
```

di JSON.

Gunakan relational table.

---

# 361. PRODUCT MODEL RELATIONSHIPS

Conceptual Eloquent:

```text
Product
├── belongsTo Category
├── hasMany Variants
├── belongsToMany Collections
└── belongsToMany Users through Wishlist
```

---

# 362. PRODUCT VARIANT RELATIONSHIPS

```text
ProductVariant
├── belongsTo Product
├── hasMany Images
└── hasMany ProductSkus
```

---

# 363. PRODUCT SKU RELATIONSHIPS

```text
ProductSku
├── belongsTo ProductVariant
├── belongsTo Size
├── hasOne InventoryStock
├── hasMany InventoryMovements
├── hasMany CartItems
├── hasMany OrderItems
└── hasMany PosTransactionItems
```

---

# 364. ORDER RELATIONSHIPS

```text
Order
├── belongsTo User
├── hasMany OrderItems
├── hasMany Payments
├── hasOne Shipment
├── hasMany StatusHistories
├── hasMany CancellationRequests
└── belongsTo Voucher (optional)
```

---

# 365. POS RELATIONSHIPS

```text
PosTransaction
├── belongsTo Cashier(User)
├── belongsTo Customer(User) optional
└── hasMany Items
```

---

# 366. CART RELATIONSHIPS

```text
Cart
├── belongsTo User optional
└── hasMany CartItems
```

```text
CartItem
├── belongsTo Cart
└── belongsTo ProductSku
```

---

# 367. ADDRESS SNAPSHOT STRATEGY

Do not use only:

```text
orders.address_id
```

as historical shipping address.

Because Customer can later update address.

Use snapshot columns on order:

```text
recipient_name
recipient_phone
shipping_province
shipping_city
shipping_district
shipping_postal_code
shipping_address
```

Optional:

```text
customer_address_id
```

dapat disimpan sebagai original reference, tetapi snapshot tetap wajib.

---

# 368. PRODUCT SNAPSHOT STRATEGY

Order item stores:

```text
product_sku_id
```

untuk relation plus:

```text
product_name_snapshot
sku_snapshot
variant_snapshot
size_snapshot
unit_price
```

Jika product diarsipkan, order tetap readable.

---

# 369. INVENTORY RESERVATION DESIGN

MVP dapat menyimpan:

```text
inventory_stocks.reserved
```

Tetapi untuk audit yang lebih kuat, disarankan menambahkan:

```text
inventory_reservations
```

---

# 370. INVENTORY RESERVATIONS TABLE

Recommended:

```text
inventory_reservations
```

Columns:

```text
id
order_id
product_sku_id
quantity
status
expires_at
released_at
consumed_at
created_at
updated_at
```

Status:

```text
ACTIVE
CONSUMED
RELEASED
EXPIRED
```

Relationship:

```text
Order
1 : N
Inventory Reservation

Product SKU
1 : N
Inventory Reservation
```

---

# 371. WHY INVENTORY_RESERVATIONS IS RECOMMENDED

Daripada hanya:

```text
reserved = 5
```

sistem dapat mengetahui:

```text
Reserved 2
→ Order A

Reserved 1
→ Order B

Reserved 2
→ Order C
```

Ini lebih mudah untuk:

* expiration;
* debugging;
* reconciliation;
* audit.

Rekomendasi final 19HOUSE:

**Gunakan `inventory_reservations`.**

`inventory_stocks.reserved` dapat menjadi cached/aggregated value atau dihitung dari active reservations.

---

# 372. UPDATED INVENTORY STRUCTURE

```text
product_skus
│
├── inventory_stocks
├── inventory_reservations
└── inventory_movements
```

---

# 373. PAYMENT EVENT TABLE

Untuk payment webhook reliability, disarankan:

```text
payment_events
```

Columns:

```text
id
payment_id
provider_event_id
event_type
payload
processed_at
created_at
```

Unique:

```text
provider_event_id
```

jika provider menyediakannya.

Tujuan:

```text
Webhook idempotency
+
Debugging
+
Audit
```

---

# 374. PAYMENT STRUCTURE

Updated:

```text
orders
   │
   │ 1
   │
   │ N
payments
   │
   │ 1
   │
   │ N
payment_events
```

---

# 375. NOTIFICATION PREFERENCE TABLE

P1 feature:

```text
notification_preferences
```

Columns:

```text
id
user_id
order_updates
promotion_updates
email_enabled
created_at
updated_at
```

Tidak wajib MVP.

---

# 376. DATABASE TRANSACTION BOUNDARIES

Critical operations harus menggunakan database transaction.

Example checkout:

```text
BEGIN

Create Order
Create Items
Create Address Snapshot
Create Reservation
Create Payment

COMMIT
```

Jika gagal:

```text
ROLLBACK
```

---

# 377. POS TRANSACTION BOUNDARY

```text
BEGIN

Validate Stock
Create POS Transaction
Create Items
Record Payment
Update Inventory
Create Movement

COMMIT
```

---

# 378. STOCK ADJUSTMENT BOUNDARY

```text
BEGIN

Lock SKU Stock
Validate Result
Update Stock
Create Movement
Create Activity Log

COMMIT
```

---

# 379. DATABASE LOCKING DIRECTION

Untuk stock-sensitive process gunakan locking strategy yang sesuai.

Conceptually:

```text
SELECT stock
FOR UPDATE
```

melalui Laravel:

```text
lockForUpdate()
```

di dalam transaction.

Tujuan:

```text
Stock = 1

Customer A
Customer B

Only one can acquire final stock
```

---

# 380. SOFT DELETE RECOMMENDATION

Use SoftDeletes for:

```text
products
product_variants
product_skus
categories
users
```

Tidak perlu untuk immutable/history tables seperti:

```text
order_items
inventory_movements
activity_logs
payment_events
```

Data history tidak seharusnya dihapus melalui workflow normal.

---

# 381. TABLE NAMING CONVENTION

Gunakan Laravel plural snake_case:

```text
product_variants
product_variant_images
product_skus
inventory_movements
order_items
```

Foreign keys:

```text
product_id
product_variant_id
product_sku_id
```

---

# 382. PRIMARY KEY STRATEGY

MVP dapat menggunakan:

```text
BIGINT UNSIGNED AUTO_INCREMENT
```

melalui:

```text
$table->id();
```

Public URL tidak harus menggunakan raw database ID.

Product menggunakan slug.

Order menggunakan order number.

---

# 383. PUBLIC IDENTIFIER

Recommended public references:

```text
Product
→ slug

Collection
→ slug

Order
→ order_number

POS
→ transaction_number
```

Jika Customer menggunakan order number di URL, tetap lakukan ownership authorization.

---

# 384. DATABASE ERD — DETAILED TEXT VERSION

```text
ROLES
│
└──< USERS
      │
      ├──< CUSTOMER_ADDRESSES
      │
      ├──< CARTS
      │     └──< CART_ITEMS >── PRODUCT_SKUS
      │
      ├──< WISHLISTS >── PRODUCTS
      │
      ├──< ORDERS
      │     ├──< ORDER_ITEMS >── PRODUCT_SKUS
      │     ├──< ORDER_STATUS_HISTORIES
      │     ├──< PAYMENTS
      │     │     └──< PAYMENT_EVENTS
      │     ├── SHIPMENT
      │     ├──< INVENTORY_RESERVATIONS
      │     └──< CANCELLATION_REQUESTS
      │
      └──< POS_TRANSACTIONS
            └──< POS_TRANSACTION_ITEMS >── PRODUCT_SKUS


CATEGORIES
│
└──< PRODUCTS
      │
      ├──< PRODUCT_VARIANTS
      │     │
      │     ├──< PRODUCT_VARIANT_IMAGES
      │     │
      │     └──< PRODUCT_SKUS
      │             │
      │             ├── INVENTORY_STOCK
      │             ├──< INVENTORY_RESERVATIONS
      │             └──< INVENTORY_MOVEMENTS
      │
      └──<> COLLECTIONS


SIZES
│
└──< PRODUCT_SKUS


VOUCHERS
│
└──< VOUCHER_USAGES
      ├── USERS
      └── ORDERS
```

Legend:

```text
──    one relationship
──<   one-to-many
──<>  many-to-many
```

---

# 385. DATABASE DOMAIN MAP

```text
┌────────────────────┐
│ AUTHENTICATION     │
│ users / roles      │
└─────────┬──────────┘
          │
          ▼
┌────────────────────┐
│ CATALOG            │
│ products / variant │
│ size / SKU / image │
└─────────┬──────────┘
          │
          ▼
┌────────────────────┐
│ INVENTORY          │
│ stock/reservation  │
│ movement           │
└─────────┬──────────┘
          │
     ┌────┴─────┐
     ▼          ▼
┌─────────┐ ┌───────────┐
│ ONLINE  │ │ POS       │
│ ORDER   │ │ SALE      │
└────┬────┘ └─────┬─────┘
     │            │
     └──────┬─────┘
            ▼
     ┌─────────────┐
     │ REPORTING   │
     └─────────────┘
```

---

# 386. DATABASE ACCEPTANCE CRITERIA

Database design dianggap memenuhi kebutuhan 19HOUSE apabila:

```text
✓ Product mendukung banyak variant
✓ Variant mempunyai banyak foto
✓ Variant mendukung banyak size
✓ Setiap sellable combination mempunyai SKU unik

✓ Stock dicatat per SKU
✓ Reservation dapat dilacak
✓ Online dan POS memakai inventory yang sama
✓ Stock movement tercatat

✓ Guest dan authenticated cart didukung
✓ Wishlist didukung

✓ Order menyimpan snapshot
✓ Address menyimpan snapshot
✓ Order dan payment dipisahkan
✓ Multiple payment attempt didukung
✓ Payment webhook event dapat dilacak

✓ Shipment dapat dicatat
✓ Cancellation dapat dicatat
✓ Voucher usage dapat dilacak

✓ POS transaction menyimpan cashier
✓ POS item menggunakan product SKU

✓ Activity penting dapat diaudit
✓ Historical record tetap readable
✓ Unique constraint tersedia
✓ Index tersedia pada query penting
```

---

# 387. RECOMMENDED MVP DATABASE TABLES

Untuk fase pertama development, prioritas tabel:

```text
1. roles
2. permissions
3. permission_role
4. users

5. customer_addresses

6. categories
7. collections
8. collection_products
9. products
10. product_variants
11. product_variant_images
12. sizes
13. product_skus

14. inventory_stocks
15. inventory_reservations
16. inventory_movements

17. carts
18. cart_items
19. wishlists

20. orders
21. order_items
22. order_status_histories

23. payments
24. payment_events
25. shipments
26. cancellation_requests

27. vouchers
28. voucher_usages

29. pos_transactions
30. pos_transaction_items

31. notifications
32. activity_logs
33. settings
```

`promotions` dapat masuk setelah voucher/basic checkout stabil.

---

# 388. MIGRATION DEVELOPMENT ORDER

Migration sebaiknya dibuat mengikuti dependency:

```text
roles
permissions
users

customer_addresses

categories
collections
products

product_variants
sizes
product_skus
product_variant_images
collection_products

inventory_stocks
inventory_reservations
inventory_movements

carts
cart_items
wishlists

vouchers

orders
order_items
order_status_histories

payments
payment_events

shipments
cancellation_requests
voucher_usages

pos_transactions
pos_transaction_items

activity_logs
settings
```

Urutan dapat berubah sedikit tergantung foreign key design.

---

# 389. FINAL DATABASE ARCHITECTURE DECISION

Untuk 19HOUSE, struktur inti yang dipertahankan adalah:

```text
PRODUCT
   ↓
VARIANT
   ↓
SKU + SIZE
   ↓
INVENTORY
```

bukan:

```text
PRODUCT
↓
SIZE COLUMNS
```

Dan commerce architecture:

```text
ONLINE
      \
       → SHARED INVENTORY → REPORTING
      /
POS
```

Dengan model ini, database tetap bersih ketika jumlah produk, warna, ukuran, transaksi, dan channel penjualan bertambah.
