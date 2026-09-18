# 236. SITEMAP

Sitemap 19HOUSE dibagi menjadi lima area utama:

```text id="mpd0th"
PUBLIC STOREFRONT
CUSTOMER ACCOUNT
CASHIER / POS
ADMIN PANEL
MANAGEMENT DASHBOARD
```

Tujuan pembagian ini adalah menjaga perbedaan konteks penggunaan.

Storefront berfokus pada:

```text id="s8nsme"
Discovery
Shopping
Checkout
Order Tracking
```

Internal application berfokus pada:

```text id="tnq51x"
Operation
Transaction
Monitoring
Reporting
```

---

# 237. HIGH-LEVEL APPLICATION MAP

```text id="c1pi8e"
19HOUSE
│
├── PUBLIC STOREFRONT
│
├── AUTHENTICATION
│
├── CUSTOMER ACCOUNT
│
├── CASHIER / POS
│
├── ADMIN
│
└── MANAGEMENT
```

---

# 238. PUBLIC STOREFRONT SITEMAP

```text id="wk28my"
PUBLIC STOREFRONT
│
├── Home
│
├── Shop
│   ├── All Products
│   ├── New Arrivals
│   ├── Categories
│   ├── Collections
│   └── Search Results
│
├── Product Detail
│
├── Wishlist
│
├── Cart
│
├── Checkout
│
├── Order Success
│
├── Payment
│   ├── Payment Pending
│   ├── Payment Success
│   ├── Payment Failed
│   └── Payment Expired
│
├── About
│
├── Contact
│
├── Size Guide
│
├── Shipping Information
│
├── Return / Cancellation Policy
│
├── Privacy Policy
└── Terms & Conditions
```

---

# 239. STOREFRONT PRIMARY NAVIGATION

Rekomendasi navigasi utama:

```text id="8ir5uh"
19HOUSE

SHOP
NEW ARRIVALS
COLLECTIONS
ABOUT
```

Bagian kanan:

```text id="kw910l"
SEARCH
ACCOUNT
WISHLIST
CART
```

Pada mobile:

```text id="zn6p0q"
MENU
LOGO
SEARCH
CART
```

Menu lainnya berada dalam mobile navigation drawer.

---

# 240. HOME PAGE STRUCTURE

```text id="hj2w5d"
HOME
│
├── Header
│
├── Hero / Editorial Campaign
│
├── New Arrivals
│
├── Featured Collection
│
├── Editorial Image Section
│
├── Selected Products
│
├── Brand / Campaign Story
│
└── Footer
```

Tidak semua section harus aktif sekaligus.

Admin dapat mengatur content tertentu tanpa mengubah design system.

---

# 241. SHOP SITEMAP

```text id="8fj0i3"
SHOP
│
├── All Products
│
├── New Arrivals
│
├── Category
│   ├── Tops
│   ├── Bottoms
│   ├── Outerwear
│   └── Accessories
│
├── Collection
│
├── Search
│
├── Filter
└── Sort
```

---

# 242. COLLECTION PAGE

```text id="y10nzl"
COLLECTION
│
├── Collection Header
├── Description / Campaign Image
├── Product Count
├── Filter
├── Sort
└── Product Grid
```

---

# 243. PRODUCT DETAIL SITEMAP

```text id="lhqmgi"
PRODUCT DETAIL
│
├── Breadcrumb / Back Navigation
├── Product Gallery
│   ├── Main Image
│   └── Thumbnails
│
├── Product Information
│   ├── Name
│   ├── Price
│   ├── Variant
│   ├── Size
│   ├── Stock State
│   ├── Size Guide
│   ├── Quantity
│   ├── Add to Cart
│   └── Wishlist
│
├── Description
├── Product Details
├── Care Information
├── Shipping Information
└── Related Products
```

Tidak semua informasi harus langsung terbuka.

Untuk menjaga minimalist interface, section seperti:

```text id="8sgdjd"
Description
Product Details
Care
Shipping
```

dapat menggunakan accordion.

---

# 244. CART SITEMAP

```text id="jtmc2g"
CART
│
├── Cart Items
│   ├── Product Image
│   ├── Name
│   ├── Variant
│   ├── Size
│   ├── Quantity
│   ├── Price
│   └── Remove
│
├── Subtotal
├── Discount
├── Estimated Total
├── Continue Shopping
└── Checkout
```

---

# 245. CART DRAWER

Selain full Cart page:

```text id="ajy9q3"
CART DRAWER
│
├── Recently Added Item
├── Cart Items
├── Cart Count
├── Subtotal
├── View Cart
└── Checkout
```

Cart Drawer digunakan untuk quick interaction.

---

# 246. CHECKOUT SITEMAP

```text id="r8uo09"
CHECKOUT
│
├── Contact
├── Shipping Address
│   ├── Saved Address
│   └── Add Address
│
├── Shipping Method
├── Voucher
├── Payment Method
├── Order Summary
│   ├── Items
│   ├── Subtotal
│   ├── Discount
│   ├── Shipping
│   └── Grand Total
│
└── Place Order
```

---

# 247. PAYMENT PAGES

```text id="bk98nq"
PAYMENT
│
├── Pending
│   ├── Payment Instructions
│   ├── Amount
│   ├── Expiry
│   └── Check Status
│
├── Success
│   ├── Order Number
│   └── View Order
│
├── Failed
│   ├── Error State
│   └── Retry
│
└── Expired
    ├── Expired Message
    └── Return to Order / Shop
```

---

# 248. AUTHENTICATION SITEMAP

```text id="bmtxti"
AUTH
│
├── Login
├── Register
├── Forgot Password
└── Reset Password
```

Optional future:

```text id="beygsk"
Verify Email
```

---

# 249. CUSTOMER ACCOUNT SITEMAP

```text id="msf9j4"
MY ACCOUNT
│
├── Overview
│
├── Orders
│   ├── All Orders
│   └── Order Detail
│
├── Wishlist
│
├── Addresses
│   ├── Address List
│   ├── Add Address
│   └── Edit Address
│
├── Notifications
│
├── Profile
│
├── Security / Password
│
└── Logout
```

---

# 250. CUSTOMER ACCOUNT OVERVIEW

```text id="74x33h"
ACCOUNT OVERVIEW
│
├── Welcome
├── Active Orders
├── Recent Orders
├── Wishlist Summary
├── Unread Notification
└── Default Address
```

---

# 251. CUSTOMER ORDER SITEMAP

```text id="gq0x9p"
ORDERS
│
├── All
├── Pending Payment
├── Processing
├── Shipped
├── Completed
└── Cancelled
```

Order Detail:

```text id="cyjs5v"
ORDER DETAIL
│
├── Order Number
├── Status
├── Timeline
├── Items
├── Payment
├── Address
├── Shipping
├── Tracking
├── Total
└── Available Actions
```

---

# 252. NOTIFICATION SITEMAP

```text id="wbc7op"
NOTIFICATIONS
│
├── All
├── Unread
└── Notification Detail / Related Link
```

Notification juga dapat tersedia dalam header dropdown.

---

# 253. CASHIER APPLICATION SITEMAP

```text id="lpf73x"
CASHIER
│
├── Dashboard
│
├── POS
│
├── Transactions
│   ├── Transaction List
│   └── Transaction Detail
│
├── Notifications
│
├── Profile
└── Logout
```

Kasir tidak membutuhkan menu internal yang terlalu banyak.

Fokus utama:

```text id="bnqlrq"
POS
+
Transaction History
```

---

# 254. CASHIER DASHBOARD

```text id="d458kr"
CASHIER DASHBOARD
│
├── Open POS
├── Sales Today
├── Transaction Count
├── Recent Transactions
└── Notification / Alert
```

---

# 255. POS SITEMAP

POS idealnya satu workspace utama.

```text id="62vk3h"
POS
│
├── Product Search
│
├── Product Results
│
├── Variant / Size Selection
│
├── Current Order
│
│   ├── Items
│   ├── Quantity
│   ├── Remove
│   └── Discount
│
├── Order Summary
│
├── Payment
│   ├── Cash
│   ├── QRIS
│   └── Transfer
│
└── Receipt
```

---

# 256. POS SCREEN STRUCTURE

Desktop/tablet:

```text id="03llc6"
┌────────────────────────────────────────────────────┐
│ 19HOUSE POS                     CASHIER / PROFILE │
├──────────────────────────────┬─────────────────────┤
│ PRODUCT                      │ CURRENT ORDER       │
│                              │                     │
│ Search...                    │ Product A           │
│                              │ Black / M      x1   │
│ Product Grid / Results       │                     │
│                              │ Product B           │
│                              │ White / L      x2   │
│                              │                     │
│                              │ ------------------  │
│                              │ Subtotal            │
│                              │ Discount            │
│                              │ TOTAL               │
│                              │                     │
│                              │ [ PAYMENT ]         │
└──────────────────────────────┴─────────────────────┘
```

---

# 257. ADMIN SITEMAP

```text id="rd6pof"
ADMIN
│
├── Dashboard
│
├── Catalog
│   ├── Products
│   │   ├── Product List
│   │   ├── Add Product
│   │   ├── Product Detail
│   │   └── Edit Product
│   │
│   ├── Categories
│   ├── Variants
│   ├── Sizes
│   └── Product Media
│
├── Inventory
│   ├── Stock Overview
│   ├── Stock In
│   ├── Stock Adjustment
│   └── Stock Movements
│
├── Orders
│   ├── All Orders
│   ├── Paid / New
│   ├── Processing
│   ├── Ready to Ship
│   ├── Shipped
│   ├── Completed
│   ├── Cancelled
│   └── Order Detail
│
├── Payments
│   ├── Payment List
│   └── Payment Detail
│
├── Cancellations
│   ├── Requests
│   └── Request Detail
│
├── Customers
│   ├── Customer List
│   └── Customer Detail
│
├── Promotions
│   ├── Promotions
│   └── Vouchers
│
├── Content
│   ├── Homepage
│   ├── Hero
│   ├── Featured Collection
│   └── Editorial Content
│
├── Reports
│   ├── Sales
│   ├── Products
│   ├── Inventory
│   └── Customers
│
├── Users & Roles
│   ├── Users
│   └── Roles / Permissions
│
├── Notifications
│
├── Activity Logs
│
├── Settings
│
├── Profile
└── Logout
```

---

# 258. ADMIN DASHBOARD SITEMAP

```text id="zib18k"
ADMIN DASHBOARD
│
├── Sales Today
├── Orders Today
├── New Paid Orders
├── Processing Orders
├── Low Stock
├── Recent Transactions
├── Recent Orders
└── Quick Actions
```

Quick Actions:

```text id="tlxoqe"
Add Product
Stock In
Open Orders
Create Voucher
```

---

# 259. ADMIN CATALOG STRUCTURE

Recommended navigation:

```text id="297hxm"
CATALOG
│
├── Products
├── Categories
├── Sizes
└── Media
```

Variant tidak harus menjadi top-level menu jika seluruh variant dikelola dari Product Detail/Edit.

Contoh:

```text id="9yzhn2"
Product
   ↓
Variants
   ↓
Sizes / SKU / Images / Stock
```

Ini lebih natural dibanding variant global list sebagai workflow utama.

---

# 260. PRODUCT ADMIN DETAIL

```text id="lx66od"
PRODUCT DETAIL
│
├── Overview
│
├── Variants
│   ├── Black
│   │   ├── Images
│   │   ├── S
│   │   ├── M
│   │   └── L
│   │
│   └── White
│       ├── Images
│       ├── S
│       ├── M
│       └── L
│
├── Inventory
├── Sales Performance
└── Activity
```

---

# 261. ADMIN INVENTORY SITEMAP

```text id="qxq2ar"
INVENTORY
│
├── Overview
│   ├── All SKU
│   ├── Low Stock
│   └── Out of Stock
│
├── Stock In
│
├── Adjustment
│
└── Movement History
```

---

# 262. INVENTORY TABLE

Recommended table:

```text id="8ub451"
SKU
Product
Variant
Size
On Hand
Reserved
Available
Status
Action
```

Status:

```text id="kr4quk"
IN STOCK
LOW STOCK
OUT OF STOCK
```

---

# 263. ADMIN ORDER SITEMAP

```text id="inrdxu"
ORDERS
│
├── All
├── Pending Payment
├── Paid
├── Processing
├── Ready to Ship
├── Shipped
├── Delivered
├── Completed
└── Cancelled
```

---

# 264. ADMIN ORDER DETAIL

```text id="07bz54"
ORDER DETAIL
│
├── Header
│   ├── Order Number
│   ├── Status
│   └── Actions
│
├── Customer
├── Order Items
├── Payment
├── Shipping
├── Address
├── Pricing Summary
├── Status Timeline
├── Cancellation
└── Internal Notes
```

---

# 265. PAYMENT ADMIN

```text id="nwl4kt"
PAYMENTS
│
├── All
├── Pending
├── Paid
├── Failed
├── Expired
├── Refunded
└── Detail
```

Payment detail:

```text id="umgjof"
Payment Reference
Order
Customer
Method
Amount
Status
Provider Reference
Created At
Paid At
Callback History / Metadata
```

Technical metadata hanya ditampilkan sesuai kebutuhan Admin.

---

# 266. ADMIN CUSTOMER SITEMAP

```text id="dv4d6j"
CUSTOMERS
│
├── Customer List
└── Customer Detail
    ├── Profile
    ├── Orders
    ├── Addresses
    └── Customer Summary
```

Admin tidak melihat:

* password;
* raw payment credentials.

---

# 267. PROMOTION SITEMAP

```text id="hpcvfa"
PROMOTIONS
│
├── Promotions
└── Vouchers
    ├── Voucher List
    ├── Add Voucher
    ├── Edit Voucher
    ├── Voucher Detail
    └── Usage History
```

---

# 268. ADMIN REPORT SITEMAP

```text id="zj0943"
REPORTS
│
├── Sales
│   ├── Overview
│   ├── Online
│   └── POS
│
├── Products
│   ├── Best Sellers
│   └── Variant Performance
│
├── Inventory
│   ├── Current Stock
│   ├── Low Stock
│   └── Movement
│
└── Customers
    ├── New Customers
    └── Returning Customers
```

---

# 269. USER MANAGEMENT SITEMAP

```text id="9rszwe"
USERS & ACCESS
│
├── Users
│   ├── Admin
│   ├── Cashier
│   └── Management
│
├── Roles
└── Permissions
```

Customer account tidak harus dicampur dengan internal User Management jika UI menjadi terlalu ramai.

Dapat dipisahkan:

```text id="9q0cxq"
CUSTOMERS
```

dan:

```text id="6ymx39"
INTERNAL USERS
```

---

# 270. ADMIN SETTINGS SITEMAP

```text id="js1chg"
SETTINGS
│
├── Store
│   ├── Name
│   ├── Logo
│   ├── Contact
│   └── Address
│
├── Commerce
│   ├── Currency
│   ├── Low Stock Threshold
│   └── Order Settings
│
├── Payment
│
├── Shipping
│
├── Notification
│
└── System
```

Sensitive configuration sebaiknya tidak semuanya editable langsung melalui UI jika berhubungan dengan secret credential.

---

# 271. MANAGEMENT SITEMAP

```text id="wzagn9"
MANAGEMENT
│
├── Dashboard
│
├── Sales
│   ├── Overview
│   ├── Online Sales
│   ├── POS Sales
│   └── Channel Comparison
│
├── Products
│   ├── Best Sellers
│   ├── Product Performance
│   └── Variant Performance
│
├── Inventory
│   ├── Current Stock
│   ├── Low Stock
│   └── Out of Stock
│
├── Customers
│   ├── Customer Growth
│   └── Repeat Customer
│
├── Reports
│
├── Notifications
│
├── Profile
└── Logout
```

---

# 272. MANAGEMENT DASHBOARD

```text id="y6ws86"
MANAGEMENT DASHBOARD
│
├── Date Range
│
├── Revenue
├── Orders
├── Units Sold
├── Average Order Value
│
├── Sales Trend
│
├── Online vs POS
│
├── Best Selling Products
│
├── Inventory Alert
└── Customer Trend
```

---

# 273. MANAGEMENT NAVIGATION PRINCIPLE

Menu Management lebih sedikit dibanding Admin.

Management tidak membutuhkan:

```text id="2kv663"
Product Create
Product Edit
Stock Adjustment
Order Processing
User Management
Settings
```

Tujuannya agar dashboard Pimpinan fokus pada pengambilan keputusan.

---

# 274. GLOBAL HEADER STRUCTURE

Storefront:

```text id="60nsfo"
LOGO
NAVIGATION

SEARCH
ACCOUNT
WISHLIST
CART
```

Internal:

```text id="i60m6i"
PAGE TITLE

NOTIFICATION
PROFILE
```

Sidebar digunakan untuk Admin / Management.

POS dapat menggunakan simplified header tanpa sidebar penuh agar ruang transaksi maksimal.

---

# 275. GLOBAL FOOTER

Storefront footer:

```text id="jiid2b"
19HOUSE

SHOP
├── New Arrivals
├── Collection
└── All Products

HELP
├── Contact
├── Shipping
├── Size Guide
└── Return Policy

LEGAL
├── Privacy
└── Terms

SOCIAL
├── Instagram
└── TikTok

Newsletter (optional)
```

---

# 276. RECOMMENDED URL STRUCTURE

Public:

```text id="cc5z2n"
/
```

Home.

```text id="cz93zv"
/shop
```

All products.

```text id="onv6ad"
/collections/{slug}
```

Collection.

```text id="fl1scs"
/categories/{slug}
```

Category.

```text id="p81t7x"
/products/{slug}
```

Product Detail.

```text id="gocmeb"
/search
```

Search.

```text id="uhb8d8"
/cart
```

Cart.

```text id="fd0w4u"
/checkout
```

Checkout.

---

# 277. AUTH ROUTES

```text id="loc3hb"
/login
/register
/forgot-password
/reset-password/{token}
```

---

# 278. CUSTOMER ACCOUNT ROUTES

```text id="rz8cl1"
/account
/account/orders
/account/orders/{order}
/account/wishlist
/account/addresses
/account/addresses/create
/account/addresses/{address}/edit
/account/notifications
/account/profile
/account/security
```

---

# 279. CASHIER ROUTES

Recommended prefix:

```text id="v76n21"
/cashier
```

Structure:

```text id="3pcgzh"
/cashier/dashboard
/cashier/pos
/cashier/transactions
/cashier/transactions/{transaction}
/cashier/notifications
/cashier/profile
```

---

# 280. ADMIN ROUTES

Prefix:

```text id="0e10if"
/admin
```

Example:

```text id="dtzhsv"
/admin/dashboard

/admin/products
/admin/products/create
/admin/products/{product}
/admin/products/{product}/edit

/admin/categories

/admin/inventory
/admin/inventory/movements

/admin/orders
/admin/orders/{order}

/admin/payments
/admin/payments/{payment}

/admin/cancellations

/admin/customers
/admin/customers/{customer}

/admin/vouchers
/admin/promotions

/admin/reports/sales
/admin/reports/inventory
/admin/reports/products

/admin/users
/admin/roles

/admin/activity-logs
/admin/settings
```

---

# 281. MANAGEMENT ROUTES

Recommended prefix:

```text id="mzfo1h"
/management
```

Structure:

```text id="gsa4kr"
/management/dashboard

/management/sales
/management/sales/channels

/management/products
/management/inventory
/management/customers

/management/reports

/management/notifications
/management/profile
```

---

# 282. ROUTE GROUP DIRECTION

Laravel route grouping secara konseptual:

```text id="mltsv0"
PUBLIC
│
└── No authentication required

CUSTOMER
│
├── auth
└── role/customer

CASHIER
│
├── auth
└── role/cashier

ADMIN
│
├── auth
└── role/admin

MANAGEMENT
│
├── auth
└── role/management
```

Authorization granular tetap menggunakan permission/policy.

---

# 283. ROUTE NAMING CONVENTION

Gunakan route names yang konsisten.

Contoh:

```text id="uczn71"
home

shop.index

products.show

cart.index
cart.store

checkout.index
checkout.store

account.index
account.orders.index
account.orders.show

cashier.dashboard
cashier.pos.index

admin.dashboard
admin.products.index
admin.products.create
admin.products.store

management.dashboard
management.reports.sales
```

---

# 284. LAYOUT STRUCTURE

Disarankan mempunyai beberapa layout utama:

```text id="yzm4hc"
layouts.storefront

layouts.account

layouts.admin

layouts.cashier

layouts.management

layouts.guest
```

Storefront dan Account masih dapat berbagi component header/footer yang sama.

---

# 285. LIVEWIRE PAGE / COMPONENT DIRECTION

Tidak semua page harus menjadi Livewire page.

Gunakan Livewire pada area interaktif seperti:

```text id="2gjgrf"
Product Variant Selector
Product Gallery
Cart Drawer
Cart
Search
Filter
Wishlist
Checkout
Notification
POS
Inventory Filter
Order Filter
Dashboard Filter
```

Blade cukup untuk content statis atau server-rendered page yang tidak membutuhkan state dinamis kompleks.

---

# 286. CUSTOMER NAVIGATION STATE

Saat Customer berada di:

```text id="76hg5e"
/products/...
```

navigasi Shop dapat dianggap active.

Saat berada di:

```text id="dhhjqa"
/account/...
```

storefront header tetap dapat digunakan, tetapi Account memiliki secondary navigation.

---

# 287. MOBILE ACCOUNT NAVIGATION

Desktop:

```text id="xjpt60"
Account Sidebar
+
Page Content
```

Mobile:

```text id="7en49p"
Account Menu / Dropdown
↓
Page Content
```

Tidak memaksakan sidebar sempit di smartphone.

---

# 288. ADMIN SIDEBAR

Recommended grouping:

```text id="eqzckw"
OVERVIEW
Dashboard

COMMERCE
Orders
Payments
Cancellations

CATALOG
Products
Categories

INVENTORY
Stock
Movements

CUSTOMERS
Customers

MARKETING
Promotions
Vouchers
Content

ANALYTICS
Reports

SYSTEM
Users & Roles
Activity Logs
Settings
```

---

# 289. CASHIER NAVIGATION

Kasir dibuat jauh lebih sederhana:

```text id="kuiuf7"
Dashboard
POS
Transactions
```

Footer/sidebar secondary:

```text id="wh663o"
Notifications
Profile
Logout
```

---

# 290. MANAGEMENT SIDEBAR

```text id="psqj85"
Dashboard

Sales

Products

Inventory

Customers

Reports
```

Secondary:

```text id="9h3d52"
Notifications
Profile
Logout
```

---

# 291. BREADCRUMB STRATEGY

Storefront sebaiknya menggunakan breadcrumb dengan ringan pada halaman seperti Product.

Contoh:

```text id="5l52s8"
Home / Tops / Oversized Tee
```

Internal dashboard dapat menggunakan breadcrumb lebih eksplisit:

```text id="lhz03d"
Products / Essential Tee / Edit
```

---

# 292. ERROR PAGES

Global system harus memiliki:

```text id="k69kjy"
403
404
419 / Session Expired
500
503 / Maintenance
```

Visual error page storefront harus tetap sesuai brand 19HOUSE.

Internal error page lebih functional.

---

# 293. EMPTY PAGE STATES

Setiap list harus menyediakan empty state.

Contoh Admin Products:

```text id="g0kgx7"
No products yet.

[ ADD PRODUCT ]
```

Customer Orders:

```text id="j3p9gh"
No orders yet.

[ EXPLORE COLLECTION ]
```

Cashier Transaction:

```text id="hlqlqt"
No transactions today.

[ OPEN POS ]
```

---

# 294. SITEMAP ACCESS MATRIX

| Area              |          Guest | Customer | Cashier | Admin | Management |
| ----------------- | -------------: | -------: | ------: | ----: | ---------: |
| Home              |              ✓ |        ✓ |       ✓ |     ✓ |          ✓ |
| Shop              |              ✓ |        ✓ |       ✓ |     ✓ |          ✓ |
| Product           |              ✓ |        ✓ |       ✓ |     ✓ |          ✓ |
| Cart              |              ✓ |        ✓ |       — |     — |          — |
| Checkout          | Login required |        ✓ |       — |     — |          — |
| Customer Account  |              — |        ✓ |       — |     — |          — |
| POS               |              — |        — |       ✓ |     △ |          — |
| Cashier Dashboard |              — |        — |       ✓ |     — |          — |
| Admin             |              — |        — |       — |     ✓ |          — |
| Management        |              — |        — |       — |     △ |          ✓ |
| Reports           |              — | Personal | Limited |     ✓ |          ✓ |

`△` berarti hanya jika permission tambahan diberikan.

---

# 295. NAVIGATION ACCEPTANCE CRITERIA

Navigation dianggap benar apabila:

```text id="xjabep"
✓ Guest dapat menjelajahi storefront
✓ Customer dapat kembali ke storefront dari Account
✓ Customer tidak melihat internal menu
✓ Cashier mendapatkan navigation sederhana
✓ Admin mendapatkan seluruh operational module
✓ Management hanya mendapatkan analytical module
✓ Protected URL tetap memerlukan authorization
✓ Mobile navigation tetap usable
✓ Current page mempunyai active navigation state
✓ Back/return flow tidak kehilangan context penting
```

---

# 296. FINAL SITEMAP SUMMARY

```text id="qyyhm8"
19HOUSE
│
├── STOREFRONT
│   ├── Home
│   ├── Shop
│   ├── Collection
│   ├── Product
│   ├── Search
│   ├── Wishlist
│   ├── Cart
│   └── Checkout
│
├── CUSTOMER ACCOUNT
│   ├── Overview
│   ├── Orders
│   ├── Wishlist
│   ├── Addresses
│   ├── Notifications
│   └── Profile
│
├── CASHIER
│   ├── Dashboard
│   ├── POS
│   └── Transactions
│
├── ADMIN
│   ├── Dashboard
│   ├── Catalog
│   ├── Inventory
│   ├── Orders
│   ├── Payments
│   ├── Customers
│   ├── Promotions
│   ├── Reports
│   ├── Users
│   ├── Activity Logs
│   └── Settings
│
└── MANAGEMENT
    ├── Dashboard
    ├── Sales
    ├── Products
    ├── Inventory
    ├── Customers
    └── Reports
```

Sitemap ini menjadi referensi untuk route structure, layout, navigation, permission, dan wireframe 19HOUSE.
