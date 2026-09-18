# 151. USER FLOW

User Flow menjelaskan alur pengguna dalam menyelesaikan tujuan tertentu di 19HOUSE.

Flow utama dibagi menjadi:

```text
CUSTOMER FLOW
CASHIER FLOW
ADMIN FLOW
MANAGEMENT FLOW
SYSTEM FLOW
```

---

# 152. CUSTOMER MAIN SHOPPING FLOW

Flow utama Customer:

```text
HOME
  ↓
COLLECTION / SEARCH
  ↓
PRODUCT DETAIL
  ↓
SELECT VARIANT
  ↓
SELECT SIZE
  ↓
ADD TO CART
  ↓
CART
  ↓
CHECKOUT
  ↓
ADDRESS
  ↓
SHIPPING
  ↓
VOUCHER
  ↓
PAYMENT METHOD
  ↓
PLACE ORDER
  ↓
PAYMENT
  ↓
ORDER CONFIRMED
  ↓
PROCESSING
  ↓
SHIPPED
  ↓
DELIVERED
  ↓
COMPLETED
```

---

# 153. CUSTOMER DISCOVERY FLOW

```text
HOME
│
├── New Arrivals
├── Featured Collection
├── Editorial Campaign
├── Search
└── Navigation
      │
      ▼
COLLECTION
      │
      ├── Filter
      ├── Sort
      └── Product Grid
              │
              ▼
        PRODUCT DETAIL
```

Customer dapat masuk ke Product Detail melalui berbagai entry point.

---

# 154. SEARCH FLOW

```text
USER OPENS SEARCH
       ↓
TYPE KEYWORD
       ↓
SEARCH REQUEST
       ↓
RESULT FOUND?
   ┌───────┴────────┐
   │                │
  YES              NO
   │                │
   ▼                ▼
RESULT GRID     EMPTY STATE
   │                │
   ▼                ├── Try another keyword
PRODUCT             └── Browse Collection
```

---

# 155. FILTER FLOW

```text
COLLECTION
    ↓
OPEN FILTER
    ↓
SELECT FILTER
├── Category
├── Size
├── Color
├── Price
└── Availability
    ↓
APPLY
    ↓
PRODUCT RESULT UPDATED
    ↓
USER MAY
├── Add another filter
├── Remove filter
└── Reset all
```

Pada mobile, filter sebaiknya menggunakan drawer atau full-screen panel.

---

# 156. PRODUCT DETAIL FLOW

```text
PRODUCT DETAIL
      ↓
DEFAULT VARIANT
      ↓
DISPLAY VARIANT IMAGES
      ↓
SELECT COLOR
      ↓
GALLERY CHANGES
      ↓
SELECT SIZE
      ↓
CHECK STOCK
      ↓
AVAILABLE?
 ┌──────┴──────┐
 │             │
YES            NO
 │             │
 ▼             ▼
ADD TO CART   SIZE DISABLED /
ENABLED       OUT OF STOCK
```

---

# 157. PRODUCT IMAGE FLOW

```text
PRODUCT DETAIL
      ↓
SELECT BLACK
      ↓
BLACK GALLERY

SELECT WHITE
      ↓
WHITE GALLERY

SELECT NAVY
      ↓
NAVY GALLERY
```

Perubahan gallery tidak memerlukan full page reload.

---

# 158. SIZE GUIDE FLOW

```text
PRODUCT DETAIL
      ↓
SIZE GUIDE
      ↓
OPEN MODAL / DRAWER
      ↓
VIEW MEASUREMENT
      ↓
CLOSE
      ↓
RETURN TO PRODUCT
```

---

# 159. ADD TO CART FLOW

```text
PRODUCT DETAIL
      ↓
CLICK ADD TO CART
      ↓
VARIANT SELECTED?
 ┌──────┴──────┐
 │             │
NO            YES
 │             │
 ▼             ▼
ASK SELECT    SIZE SELECTED?
VARIANT        │
          ┌────┴────┐
          │         │
         NO        YES
          │         │
          ▼         ▼
      ASK SIZE   STOCK VALID?
                  │
              ┌───┴───┐
              │       │
             NO      YES
              │       │
              ▼       ▼
        OUT OF STOCK  ADD CART
                        ↓
                  CART DRAWER
```

---

# 160. CART DRAWER FLOW

Setelah Add to Cart:

```text
ADD TO CART
    ↓
CART DRAWER
    │
    ├── Continue Shopping
    │       ↓
    │   Close Drawer
    │
    └── View Cart / Checkout
            ↓
           CART
```

---

# 161. CART FLOW

```text
CART
 │
 ├── Update Quantity
 │      ↓
 │   Validate Stock
 │
 ├── Remove Item
 │
 ├── Continue Shopping
 │
 └── CHECKOUT
        ↓
    CART REVALIDATION
```

---

# 162. CART REVALIDATION FLOW

Sebelum checkout:

```text
CART
 ↓
VALIDATE ALL ITEMS
 ↓
CHECK
├── Product active?
├── Variant active?
├── Size valid?
├── Stock available?
└── Current price?
 ↓
ALL VALID?
┌───────┴────────┐
│                │
YES              NO
│                │
▼                ▼
CHECKOUT      UPDATE CART
             + MESSAGE
```

---

# 163. GUEST CART FLOW

Guest diperbolehkan menyimpan item di cart.

```text
GUEST
  ↓
PRODUCT
  ↓
ADD TO CART
  ↓
SESSION CART
  ↓
CHECKOUT
  ↓
LOGIN REQUIRED
```

---

# 164. GUEST CHECKOUT TO LOGIN FLOW

```text
GUEST CART
    ↓
CHECKOUT
    ↓
AUTHENTICATED?
 ┌──────┴──────┐
 │             │
YES            NO
 │             │
 ▼             ▼
CHECKOUT     LOGIN / REGISTER
                  ↓
              SUCCESS
                  ↓
          MERGE GUEST CART
                  ↓
             RETURN TO
              CHECKOUT
```

Customer tidak boleh kehilangan cart setelah login.

---

# 165. CART MERGE FLOW

```text
GUEST CART
SKU-A Qty 1

ACCOUNT CART
SKU-A Qty 2
SKU-B Qty 1

LOGIN
 ↓
MERGE
 ↓

SKU-A Qty 3
SKU-B Qty 1
```

Jika stock SKU-A hanya 2:

```text
Result
SKU-A Qty 2

Show information:
Quantity adjusted based on available stock.
```

---

# 166. WISHLIST FLOW

```text
PRODUCT
  ↓
CLICK WISHLIST
  ↓
AUTHENTICATED?
 ┌──────┴──────┐
 │             │
YES            NO
 │             │
 ▼             ▼
SAVE         LOGIN
 │             ↓
 ▼         RETURN TO
FEEDBACK     PRODUCT
```

---

# 167. WISHLIST TO CART FLOW

```text
WISHLIST
   ↓
SELECT PRODUCT
   ↓
PRODUCT DETAIL
   ↓
SELECT VARIANT
   ↓
SELECT SIZE
   ↓
ADD TO CART
```

Wishlist menyimpan product, bukan langsung SKU.

---

# 168. CHECKOUT MAIN FLOW

```text
CHECKOUT
   ↓
SHIPPING ADDRESS
   ↓
SHIPPING METHOD
   ↓
VOUCHER
   ↓
ORDER SUMMARY
   ↓
PAYMENT METHOD
   ↓
PLACE ORDER
```

Checkout sebaiknya tetap sederhana dan tidak terlalu banyak halaman.

Implementasi dapat berbentuk:

```text
Single Page Checkout
```

atau:

```text
Step Checkout
```

Rekomendasi awal 19HOUSE:

**Single Page Checkout dengan section yang jelas.**

---

# 169. CHECKOUT PAGE STRUCTURE

```text
CHECKOUT
│
├── CONTACT
│
├── SHIPPING ADDRESS
│
├── SHIPPING METHOD
│
├── VOUCHER
│
├── PAYMENT METHOD
│
└── ORDER SUMMARY
       │
       ▼
   PLACE ORDER
```

Desktop:

```text
┌──────────────────────────┬──────────────────┐
│ Checkout Information     │ Order Summary    │
│                          │                  │
│ Address                  │ Items            │
│ Shipping                 │ Subtotal         │
│ Voucher                  │ Discount         │
│ Payment                  │ Shipping         │
│                          │ Total            │
│                          │                  │
│                          │ [PLACE ORDER]    │
└──────────────────────────┴──────────────────┘
```

Mobile:

```text
Checkout Information
        ↓
Order Summary
        ↓
Place Order
```

---

# 170. ADDRESS FLOW

```text
CHECKOUT
  ↓
ADDRESS EXISTS?
 ┌──────┴───────┐
 │              │
YES             NO
 │              │
 ▼              ▼
SELECT       ADD ADDRESS
ADDRESS          ↓
 │            SAVE
 └──────┬───────┘
        ▼
CONTINUE
```

---

# 171. ADD ADDRESS FLOW

```text
ADD ADDRESS
   ↓
INPUT
├── Recipient
├── Phone
├── Province
├── City
├── District
├── Postal Code
└── Full Address
   ↓
VALID?
 ┌─────┴─────┐
 │           │
NO          YES
 │           │
 ▼           ▼
ERROR       SAVE
             ↓
      SELECT ADDRESS
```

---

# 172. SHIPPING FLOW

```text
ADDRESS SELECTED
       ↓
LOAD SHIPPING OPTIONS
       ↓
OPTIONS AVAILABLE?
  ┌──────┴──────┐
  │             │
 YES            NO
  │             │
  ▼             ▼
SELECT        SHOW ERROR /
COURIER       MANUAL SUPPORT
  ↓
UPDATE SHIPPING COST
  ↓
UPDATE GRAND TOTAL
```

---

# 173. VOUCHER FLOW

```text
CHECKOUT
  ↓
ENTER VOUCHER
  ↓
APPLY
  ↓
VALIDATE
  ↓
VALID?
┌─────┴─────┐
│           │
NO         YES
│           │
▼           ▼
ERROR      APPLY DISCOUNT
             ↓
       RECALCULATE TOTAL
```

---

# 174. VOUCHER ERROR FLOW

Possible errors:

```text
Voucher not found
Voucher inactive
Voucher expired
Usage limit reached
Customer usage limit reached
Minimum purchase not reached
Voucher not applicable
```

---

# 175. PLACE ORDER FLOW

```text
CLICK PLACE ORDER
       ↓
DISABLE DUPLICATE SUBMIT
       ↓
SERVER FINAL VALIDATION
       ↓
VALID?
 ┌──────┴───────┐
 │              │
NO             YES
 │              │
 ▼              ▼
RETURN ERROR   DATABASE TRANSACTION
               │
               ├── Create Order
               ├── Create Order Items
               ├── Address Snapshot
               ├── Voucher Usage
               ├── Stock Reservation
               └── Create Payment
                       ↓
                  PAYMENT FLOW
```

---

# 176. DUPLICATE ORDER PREVENTION FLOW

User double-click:

```text
PLACE ORDER
PLACE ORDER
```

tidak boleh menghasilkan:

```text
Order A
Order B
```

UI:

```text
First Click
↓
Button Loading + Disabled
```

Server tetap harus mempunyai protection karena UI saja tidak cukup.

---

# 177. PAYMENT FLOW

```text
ORDER CREATED
     ↓
PAYMENT CREATED
     ↓
PAYMENT INSTRUCTION
     ↓
CUSTOMER PAYS
     ↓
PAYMENT GATEWAY
     ↓
WEBHOOK
     ↓
VERIFY
     ↓
PAYMENT STATUS?
```

---

# 178. PAYMENT SUCCESS FLOW

```text
WEBHOOK VERIFIED
      ↓
PAYMENT → PAID
      ↓
ORDER → PAID
      ↓
RESERVED STOCK
      ↓
CONVERT TO SALE
      ↓
STOCK MOVEMENT
      ↓
ORDER HISTORY
      ↓
NOTIFICATION
      ↓
ORDER SUCCESS PAGE
```

---

# 179. PAYMENT PENDING FLOW

```text
PAYMENT PENDING
      ↓
CUSTOMER MAY LEAVE PAGE
      ↓
ORDER REMAINS
PENDING_PAYMENT
      ↓
CUSTOMER OPENS ORDER
      ↓
PAYMENT STATUS
      ↓
CONTINUE PAYMENT
```

jika payment provider mendukung.

---

# 180. PAYMENT FAILED FLOW

```text
PAYMENT
   ↓
FAILED
   ↓
ORDER STILL
PENDING_PAYMENT
   ↓
RETRY AVAILABLE?
 ┌──────┴──────┐
 │             │
YES            NO
 │             │
 ▼             ▼
RETRY       WAIT / CANCEL
```

---

# 181. PAYMENT EXPIRED FLOW

```text
PAYMENT EXPIRED
      ↓
PAYMENT → EXPIRED
      ↓
ORDER → CANCELLED
      ↓
RELEASE RESERVED STOCK
      ↓
CREATE STATUS HISTORY
      ↓
CUSTOMER NOTIFICATION
```

---

# 182. PAYMENT STATUS RECOVERY FLOW

Jika browser Customer ditutup setelah pembayaran:

```text
CUSTOMER PAYS
     ↓
BROWSER CLOSED
     ↓
GATEWAY WEBHOOK
     ↓
SYSTEM UPDATES ORDER
     ↓
CUSTOMER LOGIN LATER
     ↓
ORDER SHOWS PAID
```

Sistem tidak bergantung pada Customer kembali ke success page.

---

# 183. ORDER SUCCESS FLOW

```text
PAYMENT CONFIRMED
       ↓
ORDER SUCCESS
       ↓
DISPLAY
├── Order Number
├── Payment Status
├── Order Summary
└── Continue Shopping
       ↓
ORDER DETAIL
```

---

# 184. CUSTOMER ORDER LIST FLOW

```text
ACCOUNT
  ↓
ORDERS
  ↓
ORDER LIST
  │
  ├── All
  ├── Pending Payment
  ├── Processing
  ├── Shipped
  ├── Completed
  └── Cancelled
  ↓
ORDER DETAIL
```

---

# 185. ORDER DETAIL FLOW

```text
ORDER DETAIL
│
├── Order Number
├── Status
├── Status Timeline
├── Items
├── Payment
├── Shipping Address
├── Shipping Method
├── Tracking Number
├── Pricing Summary
└── Available Action
```

Available Action berubah sesuai status.

---

# 186. ORDER ACTION STATE

Example:

```text
PENDING_PAYMENT
├── Pay Now
└── Cancel

PAID
└── Request Cancellation

PROCESSING
└── Contact Support

SHIPPED
└── Track Package

DELIVERED
└── Confirm Received / View Details

COMPLETED
└── Buy Again
```

Action final menyesuaikan business rules.

---

# 187. ORDER TRACKING FLOW

```text
ORDER
  ↓
SHIPPED?
 ┌─────┴─────┐
 │           │
NO          YES
 │           │
 ▼           ▼
STATUS      TRACKING NUMBER
TIMELINE       ↓
            COURIER
               ↓
        SHIPPING TRACKING
```

---

# 188. CANCEL UNPAID ORDER FLOW

```text
ORDER
PENDING_PAYMENT
      ↓
CANCEL ORDER
      ↓
CONFIRM?
 ┌─────┴─────┐
 │           │
NO          YES
 │           │
 ▼           ▼
RETURN      INPUT REASON
              ↓
           CANCEL
              ↓
ORDER CANCELLED
PAYMENT CANCELLED
RESERVATION RELEASED
```

---

# 189. PAID CANCELLATION REQUEST FLOW

```text
ORDER = PAID
     ↓
REQUEST CANCELLATION
     ↓
INPUT REASON
     ↓
SUBMIT
     ↓
REQUESTED
     ↓
ADMIN NOTIFICATION
     ↓
ADMIN REVIEW
 ┌──────┴──────┐
 │             │
APPROVE       REJECT
 │             │
 ▼             ▼
CANCEL +      ORDER CONTINUES
REFUND FLOW   + CUSTOMER NOTICE
```

---

# 190. NOTIFICATION FLOW

```text
SYSTEM EVENT
    ↓
CREATE NOTIFICATION
    ↓
USER RECEIVES
    ↓
NOTIFICATION CENTER
    ↓
OPEN ITEM
    ↓
MARK READ
    ↓
RELATED RESOURCE
```

Example:

```text
"Your order has shipped"
        ↓
Order Detail
```

---

# 191. CUSTOMER ACCOUNT FLOW

```text
ACCOUNT
│
├── Overview
├── Orders
├── Wishlist
├── Addresses
├── Notifications
├── Profile
└── Logout
```

---

# 192. CUSTOMER ACCOUNT OVERVIEW

Overview dapat menampilkan:

```text
Hello, Customer

Active Orders
Recent Orders
Wishlist Count
Unread Notifications
Default Address
```

Interface tetap minimal dan tidak menggunakan gaya dashboard admin.

---

# 193. BUY AGAIN FLOW

P1 feature:

```text
COMPLETED ORDER
      ↓
BUY AGAIN
      ↓
CHECK EACH SKU
      ↓
AVAILABLE?
 ┌─────┴──────┐
 │            │
YES          NO
 │            │
 ▼            ▼
ADD CART    SKIP + INFORM
```

Harga menggunakan harga saat ini, bukan historical order price.

---

# 194. CASHIER LOGIN FLOW

```text
LOGIN
 ↓
VALID ROLE?
 ┌─────┴─────┐
 │           │
NO          YES
 │           │
 ▼           ▼
DENIED   CASHIER DASHBOARD
```

---

# 195. CASHIER DASHBOARD FLOW

```text
CASHIER DASHBOARD
│
├── Open POS
├── Sales Today
├── Transactions Today
└── Recent Transactions
```

---

# 196. POS MAIN FLOW

```text
OPEN POS
   ↓
SEARCH PRODUCT / SKU
   ↓
SELECT PRODUCT
   ↓
SELECT VARIANT
   ↓
SELECT SIZE
   ↓
CHECK STOCK
   ↓
ADD TO POS CART
   ↓
ADD MORE ITEMS?
 ┌──────┴──────┐
 │             │
YES            NO
 │             │
 └── SEARCH    ▼
            PAYMENT
               ↓
        COMPLETE SALE
               ↓
             RECEIPT
```

---

# 197. POS PRODUCT SEARCH FLOW

```text
SEARCH
├── Product Name
├── SKU
└── Barcode (future)
     ↓
RESULT
     ↓
SELECT
     ↓
VARIANT + SIZE
```

---

# 198. POS OUT-OF-STOCK FLOW

```text
SELECT SKU
   ↓
AVAILABLE = 0
   ↓
DISABLE ADD
   ↓
SHOW OUT OF STOCK
```

Kasir tidak dapat override negative inventory secara default.

---

# 199. POS CART FLOW

```text
POS CART
│
├── Item
├── Variant
├── Size
├── Quantity
├── Unit Price
└── Subtotal
     ↓
OPTION
├── Update Quantity
├── Remove
├── Apply Allowed Discount
└── Payment
```

---

# 200. POS CASH PAYMENT FLOW

```text
PAYMENT
  ↓
CASH
  ↓
TOTAL = 250.000
  ↓
AMOUNT RECEIVED
  ↓
300.000
  ↓
VALID?
 ┌─────┴─────┐
 │           │
NO          YES
 │           │
 ▼           ▼
ERROR     CHANGE
          50.000
             ↓
         CONFIRM
             ↓
       COMPLETE SALE
```

---

# 201. POS NON-CASH FLOW

```text
PAYMENT
   ↓
QRIS / TRANSFER
   ↓
PAYMENT PROCESS
   ↓
CONFIRMED?
 ┌─────┴─────┐
 │           │
NO          YES
 │           │
 ▼           ▼
RETRY      COMPLETE
```

---

# 202. COMPLETE POS SALE FLOW

```text
CONFIRM SALE
    ↓
BEGIN DATABASE TRANSACTION
    ↓
RECHECK STOCK
    ↓
VALID?
 ┌──────┴───────┐
 │              │
NO             YES
 │              │
 ▼              ▼
ROLLBACK      CREATE TRANSACTION
ERROR         CREATE ITEMS
              RECORD PAYMENT
              REDUCE STOCK
              STOCK MOVEMENTS
              COMMIT
                 ↓
               RECEIPT
```

---

# 203. POS RECEIPT FLOW

```text
TRANSACTION SUCCESS
        ↓
RECEIPT
        │
        ├── Print
        ├── View
        └── New Transaction
```

---

# 204. POS TRANSACTION HISTORY FLOW

```text
CASHIER DASHBOARD
      ↓
TRANSACTIONS
      ↓
FILTER
├── Date
├── Transaction ID
└── Payment
      ↓
TRANSACTION DETAIL
```

---

# 205. ADMIN MAIN FLOW

```text
LOGIN
 ↓
ADMIN DASHBOARD
 │
 ├── Catalog
 ├── Inventory
 ├── Orders
 ├── Payments
 ├── Customers
 ├── Promotions
 ├── Reports
 ├── Users
 ├── Activity Logs
 └── Settings
```

---

# 206. ADMIN PRODUCT CREATION FLOW

```text
PRODUCTS
   ↓
ADD PRODUCT
   ↓
PRODUCT INFORMATION
   ↓
SAVE AS DRAFT
   ↓
ADD VARIANT
   ↓
ADD SIZE
   ↓
GENERATE / INPUT SKU
   ↓
UPLOAD VARIANT IMAGES
   ↓
SET STOCK
   ↓
REVIEW
   ↓
ACTIVATE PRODUCT
```

---

# 207. PRODUCT ACTIVATION FLOW

```text
ACTIVATE PRODUCT
      ↓
CHECK
├── Product info complete?
├── Category exists?
├── Active variant?
├── SKU exists?
├── Variant image?
└── Valid price?
      ↓
PASS?
 ┌──────┴──────┐
 │             │
NO            YES
 │             │
 ▼             ▼
SHOW ERROR    ACTIVE
```

---

# 208. ADMIN VARIANT FLOW

```text
PRODUCT
  ↓
VARIANTS
  ↓
ADD VARIANT
  ↓
COLOR / NAME
  ↓
ADD IMAGES
  ↓
ADD SIZES
  ↓
CREATE SKU
  ↓
SET STOCK
```

---

# 209. ADMIN STOCK-IN FLOW

```text
INVENTORY
   ↓
SELECT SKU
   ↓
STOCK IN
   ↓
QUANTITY
   ↓
REFERENCE / NOTE
   ↓
CONFIRM
   ↓
UPDATE STOCK
   ↓
CREATE MOVEMENT
```

---

# 210. ADMIN STOCK ADJUSTMENT FLOW

```text
INVENTORY
   ↓
SELECT SKU
   ↓
ADJUST
   ↓
CURRENT STOCK
   ↓
INCREASE / DECREASE
   ↓
QUANTITY
   ↓
REASON REQUIRED
   ↓
CONFIRM
   ↓
VALID RESULT?
 ┌──────┴──────┐
 │             │
NO            YES
 │             │
 ▼             ▼
ERROR       UPDATE
             ↓
       STOCK MOVEMENT
             ↓
        ACTIVITY LOG
```

---

# 211. ADMIN ORDER PROCESSING FLOW

```text
NEW PAID ORDER
      ↓
ADMIN NOTIFICATION
      ↓
ORDER DETAIL
      ↓
VERIFY ORDER
      ↓
START PROCESSING
      ↓
PACKING
      ↓
READY TO SHIP
      ↓
ADD SHIPPING INFO
      ↓
SHIPPED
      ↓
CUSTOMER NOTIFIED
```

---

# 212. ADMIN ORDER QUEUE

Recommended order grouping:

```text
ORDERS
│
├── Pending Payment
├── Paid / New
├── Processing
├── Ready to Ship
├── Shipped
├── Completed
└── Cancelled
```

Admin operational focus utama:

```text
PAID / NEW
+
PROCESSING
+
READY TO SHIP
```

---

# 213. ADMIN CANCELLATION REVIEW FLOW

```text
CANCELLATION REQUEST
        ↓
ORDER DETAIL
        ↓
CHECK
├── Payment
├── Order Status
├── Fulfillment
└── Reason
        ↓
DECISION
 ┌──────┴───────┐
 │              │
APPROVE        REJECT
 │              │
 ▼              ▼
REFUND?      INPUT NOTE
 │              │
STOCK?          ▼
 │          CUSTOMER NOTICE
 ▼
CANCEL ORDER
 │
CUSTOMER NOTICE
```

---

# 214. ADMIN VOUCHER FLOW

```text
PROMOTIONS
   ↓
VOUCHERS
   ↓
CREATE
   ↓
INPUT
├── Code
├── Type
├── Value
├── Min Purchase
├── Max Discount
├── Start
├── End
├── Usage Limit
└── Customer Limit
   ↓
VALIDATE
   ↓
ACTIVATE
```

---

# 215. ADMIN USER MANAGEMENT FLOW

```text
USERS
  ↓
SELECT USER
  ↓
ACTION
├── Create
├── Update
├── Activate
├── Deactivate
└── Change Role
      ↓
AUTHORIZATION
      ↓
SAVE
      ↓
ACTIVITY LOG
```

---

# 216. ADMIN REPORT FLOW

```text
REPORTS
   ↓
SELECT REPORT
├── Sales
├── Inventory
├── Product
└── Customer
   ↓
SELECT PERIOD
   ↓
FILTER
   ↓
VIEW RESULT
   ↓
EXPORT (if permitted)
```

---

# 217. MANAGEMENT MAIN FLOW

```text
LOGIN
 ↓
MANAGEMENT DASHBOARD
 │
 ├── Revenue
 ├── Orders
 ├── Sales Trend
 ├── Online vs POS
 ├── Best Sellers
 ├── Inventory
 └── Customers
```

---

# 218. MANAGEMENT DATE FILTER FLOW

```text
DASHBOARD
   ↓
DATE FILTER
├── Today
├── 7 Days
├── 30 Days
├── This Month
└── Custom
   ↓
REFRESH METRICS
```

Semua metric harus menggunakan date range yang sama kecuali diberi label berbeda.

---

# 219. SALES CHANNEL COMPARISON FLOW

```text
REPORT
  ↓
CHANNEL COMPARISON
  ↓
ONLINE vs POS
  ↓
COMPARE
├── Revenue
├── Transactions
├── Units Sold
└── Average Transaction
```

---

# 220. MANAGEMENT PRODUCT PERFORMANCE FLOW

```text
PRODUCT PERFORMANCE
       ↓
SORT / FILTER
       ↓
VIEW
├── Best Seller
├── Revenue
├── Units Sold
└── Variant Performance
       ↓
PRODUCT DETAIL ANALYTICS
```

Management tetap read-only.

---

# 221. MANAGEMENT INVENTORY FLOW

```text
INVENTORY REPORT
    ↓
VIEW
├── Current Stock
├── Low Stock
├── Out of Stock
└── Movement Summary
```

Tidak ada adjustment action.

---

# 222. NOTIFICATION NAVIGATION FLOW

Untuk seluruh role:

```text
HEADER
  ↓
NOTIFICATION ICON
  ↓
UNREAD COUNT
  ↓
OPEN DROPDOWN / PANEL
  ↓
SELECT NOTIFICATION
  ↓
MARK READ
  ↓
OPEN RELATED PAGE
```

---

# 223. PROFILE DROPDOWN FLOW

Untuk authenticated user:

```text
HEADER
 ↓
PROFILE
 ↓
DROPDOWN
├── Profile
├── Account / Dashboard
├── Notifications
└── Logout
```

Item menyesuaikan role.

---

# 224. LOGOUT FLOW

```text
PROFILE DROPDOWN
       ↓
LOGOUT
       ↓
INVALIDATE SESSION
       ↓
REDIRECT
```

Customer:

```text
Home / Login
```

Internal role:

```text
Internal Login
```

---

# 225. SESSION EXPIRED FLOW

```text
USER ACTION
   ↓
SESSION EXPIRED
   ↓
LOGIN REQUIRED
   ↓
LOGIN
   ↓
RETURN TO SAFE PREVIOUS FLOW
```

Untuk operasi sensitif seperti checkout/payment, sistem harus memuat ulang current server state setelah login.

---

# 226. 403 ACCESS FLOW

```text
USER OPENS PROTECTED URL
        ↓
AUTHORIZED?
   ┌────┴────┐
   │         │
  YES       NO
   │         │
   ▼         ▼
 PAGE      403 / SAFE REDIRECT
```

Data protected tidak boleh dirender terlebih dahulu.

---

# 227. 404 PRODUCT FLOW

```text
PRODUCT URL
   ↓
FOUND?
 ┌────┴────┐
 │         │
YES       NO
 │         │
 ▼         ▼
DETAIL   404 PAGE
```

Jika product archived/inactive, behavior dapat berupa 404 atau unavailable page berdasarkan SEO decision.

---

# 228. LOW STOCK SYSTEM FLOW

```text
STOCK MOVEMENT
      ↓
CALCULATE AVAILABLE
      ↓
<= THRESHOLD?
 ┌──────┴───────┐
 │              │
NO             YES
 │              │
END         ALERT ACTIVE?
            ┌────┴────┐
            │         │
           YES       NO
            │         │
            ▼         ▼
           END      CREATE ALERT
                       ↓
                 ADMIN NOTIFICATION
```

---

# 229. PAYMENT WEBHOOK SYSTEM FLOW

```text
PAYMENT PROVIDER
      ↓
WEBHOOK
      ↓
VERIFY SIGNATURE
      ↓
VALID?
 ┌──────┴──────┐
 │             │
NO            YES
 │             │
 ▼             ▼
REJECT       FIND PAYMENT
                ↓
           ALREADY PROCESSED?
            ┌────┴────┐
            │         │
           YES       NO
            │         │
            ▼         ▼
           ACK      UPDATE PAYMENT
                      ↓
                  UPDATE ORDER
                      ↓
                  INVENTORY
                      ↓
                 NOTIFICATION
```

---

# 230. PAYMENT EXPIRATION SYSTEM FLOW

```text
PAYMENT PENDING
      ↓
EXPIRATION TIME REACHED
      ↓
VERIFY PROVIDER STATUS
      ↓
STILL UNPAID
      ↓
PAYMENT EXPIRED
      ↓
ORDER CANCELLED
      ↓
RELEASE STOCK
      ↓
NOTIFY CUSTOMER
```

---

# 231. CUSTOMER COMPLETE JOURNEY

End-to-end flow:

```text
DISCOVER
   ↓
HOME
   ↓
COLLECTION
   ↓
PRODUCT
   ↓
VARIANT
   ↓
SIZE
   ↓
CART
   ↓
LOGIN
   ↓
CHECKOUT
   ↓
ADDRESS
   ↓
SHIPPING
   ↓
VOUCHER
   ↓
PAYMENT
   ↓
ORDER
   ↓
PROCESSING
   ↓
SHIPPED
   ↓
DELIVERED
   ↓
COMPLETED
   ↓
RETURN TO SHOP
```

---

# 232. ADMIN COMPLETE OPERATION FLOW

```text
CATALOG
   ↓
PRODUCT
   ↓
VARIANT
   ↓
SIZE
   ↓
IMAGES
   ↓
SKU
   ↓
INVENTORY
   ↓
PRODUCT ACTIVE
   ↓
CUSTOMER ORDER
   ↓
PAYMENT
   ↓
ORDER PROCESS
   ↓
SHIPMENT
   ↓
COMPLETED
   ↓
REPORT
```

---

# 233. 19HOUSE OMNICHANNEL FLOW

19HOUSE harus mempertahankan hubungan:

```text
                     ┌─────────────┐
                     │  INVENTORY  │
                     └──────┬──────┘
                            │
                 ┌──────────┴──────────┐
                 │                     │
                 ▼                     ▼
          ONLINE STORE                POS
                 │                     │
                 ▼                     ▼
          ONLINE ORDER            POS SALE
                 │                     │
                 └──────────┬──────────┘
                            ▼
                       SALES DATA
                            ↓
                         REPORT
                            ↓
                       MANAGEMENT
```

Inventory menjadi sumber stok tunggal untuk kedua channel.

---

# 234. USER FLOW DESIGN PRINCIPLES

Semua flow harus mengikuti prinsip berikut:

### Minimum Friction

Jangan menambahkan langkah jika tidak diperlukan.

### Preserve Context

Setelah Login dari Checkout, Customer kembali ke Checkout.

Setelah Login dari Wishlist, Customer kembali ke Product.

### Clear Next Action

Setiap halaman utama harus memiliki primary action yang jelas.

### Recoverable Errors

Kesalahan seperti payment failure atau insufficient stock harus menyediakan tindakan selanjutnya.

### Server as Source of Truth

Cart, price, stock, payment, dan order selalu divalidasi kembali oleh server.

### Responsive Flow

Flow harus dapat diselesaikan pada smartphone tanpa bergantung pada hover atau desktop-only interaction.

---

# 235. USER FLOW ACCEPTANCE CHECKLIST

Customer flow dianggap lengkap apabila:

```text
✓ Browse tanpa login
✓ Search dan filter
✓ Product variant switching
✓ Variant-specific gallery
✓ Size selection
✓ Guest cart
✓ Login return flow
✓ Cart revalidation
✓ Address management
✓ Shipping
✓ Voucher
✓ Checkout
✓ Payment success
✓ Payment failure
✓ Payment expiry
✓ Order history
✓ Tracking
✓ Cancellation
✓ Notifications
```

Cashier flow:

```text
✓ Login
✓ POS
✓ Search SKU
✓ Cart
✓ Stock validation
✓ Cash payment
✓ Non-cash payment
✓ Transaction completion
✓ Receipt
✓ History
```

Admin flow:

```text
✓ Product lifecycle
✓ Variant lifecycle
✓ Image management
✓ Inventory
✓ Order processing
✓ Cancellation
✓ Voucher
✓ User management
✓ Reports
```

Management flow:

```text
✓ Dashboard
✓ Date filtering
✓ Channel comparison
✓ Product performance
✓ Inventory visibility
✓ Reports
```

User Flow ini menjadi dasar untuk menentukan struktur halaman pada **Sitemap 19HOUSE** dan setelah itu diterjemahkan menjadi **wireframe per halaman**.
