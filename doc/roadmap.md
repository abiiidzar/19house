# 674. DEVELOPMENT ROADMAP

Development Roadmap 19HOUSE dibagi menjadi fase yang mengikuti dependency sistem.

Urutan besar:

```text
PROJECT FOUNDATION
        ↓
AUTH & ROLE
        ↓
CATALOG
        ↓
VARIANT / SKU
        ↓
INVENTORY
        ↓
STOREFRONT
        ↓
CART
        ↓
CHECKOUT
        ↓
PAYMENT
        ↓
ORDER MANAGEMENT
        ↓
CUSTOMER ACCOUNT
        ↓
POS
        ↓
PROMOTION
        ↓
REPORTING
        ↓
HARDENING
        ↓
DEPLOYMENT
```

Prinsip utama:

> Jangan membangun checkout sebelum struktur Product, SKU, dan Inventory sudah stabil.

---

# 675. ROADMAP OBJECTIVE

Roadmap bertujuan untuk memastikan:

```text
✓ Development berjalan bertahap
✓ Dependency antar modul jelas
✓ Fitur inti selesai lebih dahulu
✓ Testing dapat dilakukan per fase
✓ Tidak terlalu banyak fitur dibangun sekaligus
✓ MVP dapat digunakan sebelum fitur lanjutan selesai
✓ Risiko refactor besar dapat dikurangi
```

---

# 676. DEVELOPMENT STRATEGY

19HOUSE menggunakan strategi:

```text
FOUNDATION FIRST
+
VERTICAL FEATURE DEVELOPMENT
+
INCREMENTAL RELEASE
```

Artinya:

1. fondasi project diselesaikan;
2. fitur dibangun sampai usable end-to-end;
3. setiap fase harus menghasilkan fungsi yang dapat diuji;
4. modul lanjutan tidak dibuat sebelum dependency utamanya stabil.

---

# 677. ROADMAP PHASES

Roadmap utama:

```text
PHASE 0
Project Preparation

PHASE 1
Foundation & Authentication

PHASE 2
Catalog Management

PHASE 3
Variant, SKU & Inventory

PHASE 4
Storefront

PHASE 5
Cart & Wishlist

PHASE 6
Checkout & Address

PHASE 7
Payment & Order

PHASE 8
Admin Order Fulfillment

PHASE 9
Customer Account

PHASE 10
Cashier POS

PHASE 11
Voucher & Promotion

PHASE 12
Reporting & Management

PHASE 13
Notification & Audit

PHASE 14
Testing & Hardening

PHASE 15
Deployment & Launch

PHASE 16
Post-MVP Enhancement
```

---

# 678. PHASE 0 — PROJECT PREPARATION

Objective:

Menyiapkan project sebelum development fitur.

Tasks:

```text
Create repository

Prepare Laravel project

Configure environment

Configure database

Configure source control

Prepare branch strategy

Install frontend dependencies

Prepare Tailwind

Prepare Livewire

Prepare base folder structure

Prepare coding convention

Prepare .env.example

Prepare local storage

Prepare base README
```

Initial result:

```text
Application runs locally
Database connects
Frontend compiles
Laravel environment stable
```

---

# 679. PHASE 0 — VERSION CONTROL BASELINE

Recommended branches:

```text
main
develop
feature/*
fix/*
```

Example:

```text
feature/authentication
feature/product-management
feature/cart
feature/checkout
```

Alternative simpler workflow:

```text
main
feature/*
```

untuk tim kecil.

---

# 680. PHASE 0 — PROJECT CONFIGURATION

Initial settings:

```text
APP_NAME=19HOUSE
APP_ENV=local
APP_DEBUG=true
APP_TIMEZONE=Asia/Jakarta
```

Currency default:

```text
IDR
```

Locale dapat ditentukan sejak awal untuk memastikan:

* number formatting;
* date formatting;
* timezone consistency.

---

# 681. PHASE 0 — BASE UI FOUNDATION

Sebelum membuat halaman besar, siapkan:

```text
Typography
Color tokens
Spacing
Container
Buttons
Inputs
Forms
Modal
Drawer
Toast
Loading
Empty state
```

Jangan langsung membuat seluruh high-fidelity component.

Fokus pada reusable primitive.

---

# 682. PHASE 0 ACCEPTANCE

Phase 0 selesai jika:

```text
✓ Laravel berjalan
✓ Database berjalan
✓ Livewire berjalan
✓ Tailwind berjalan
✓ Authentication dependency siap
✓ Repository siap
✓ Environment documented
✓ Basic UI tokens tersedia
```

---

# 683. PHASE 1 — AUTHENTICATION & ROLE FOUNDATION

Objective:

Membangun fondasi akses seluruh sistem.

Features:

```text
Login
Logout
Register Customer
Forgot Password
Reset Password
Profile baseline
Role
Permission baseline
Route protection
```

Roles:

```text
ADMIN
CASHIER
MANAGEMENT
CUSTOMER
```

---

# 684. PHASE 1 — DATABASE

Implement:

```text
roles
permissions
permission_role
users
```

Seed default roles.

Example internal accounts for development:

```text
Admin
Cashier
Management
Customer
```

---

# 685. PHASE 1 — AUTHORIZATION

Implement:

```text
Role middleware
Policies
Permission checks
Ownership checks
```

Test:

```text
Customer cannot access /admin

Cashier cannot access product editor

Management cannot modify inventory

Admin can access operational modules
```

---

# 686. PHASE 1 — ROLE REDIRECTION

After login:

```text
Admin
→ /admin/dashboard

Cashier
→ /cashier/dashboard

Management
→ /management/dashboard

Customer
→ previous intended page / home
```

---

# 687. PHASE 1 ACCEPTANCE

```text
✓ All roles can login
✓ Customer can register
✓ Password reset works
✓ Role redirect works
✓ Protected routes reject unauthorized access
✓ Ownership baseline available
✓ Disabled account cannot login
```

---

# 688. PHASE 2 — CATALOG MANAGEMENT

Objective:

Admin dapat membangun catalog fashion.

Modules:

```text
Categories
Collections
Products
Sizes
```

---

# 689. PHASE 2 — CATEGORY

Implement:

```text
Category list
Create
Edit
Activate
Deactivate
Display order
```

Required fields:

```text
Name
Slug
Status
```

Optional:

```text
Description
Image
```

---

# 690. PHASE 2 — PRODUCT

Implement:

```text
Product list
Create product
Edit product
Product detail
Draft
Active
Inactive
Archive
```

Fields:

```text
Name
Slug
Category
Description
Base Price
Status
Featured
```

---

# 691. PHASE 2 — COLLECTION

Implement:

```text
Create Collection
Edit Collection
Add Products
Remove Products
Sort Products
Activate / Deactivate
```

Example:

```text
New Arrivals
Core Collection
Summer 26
```

---

# 692. PHASE 2 — SIZE MASTER

Implement:

```text
XS
S
M
L
XL
XXL
```

with:

```text
display_order
status
```

---

# 693. PHASE 2 ACCEPTANCE

```text
✓ Admin can create categories
✓ Admin can create products
✓ Products support statuses
✓ Products can belong to collections
✓ Sizes can be managed
✓ Archived products are not hard deleted
```

---

# 694. PHASE 3 — PRODUCT VARIANT, SKU & IMAGES

Objective:

Membangun model produk sesuai struktur fashion 19HOUSE.

Structure:

```text
Product
  ↓
Variant
  ↓
Size
  ↓
SKU
```

---

# 695. PHASE 3 — VARIANT

Implement:

```text
Add variant
Edit variant
Deactivate variant
Color name
Color HEX
Display order
```

Example:

```text
Black
White
Navy
```

---

# 696. PHASE 3 — VARIANT IMAGES

Implement:

```text
Multiple images per variant
Primary image
Image order
Delete image
Image preview
```

Rule:

```text
ACTIVE PRODUCT
must have usable images.
```

---

# 697. PHASE 3 — SKU

Generate or input SKU.

Example:

```text
19H-TEE-BLK-S
19H-TEE-BLK-M
19H-TEE-BLK-L
```

Validate:

```text
SKU unique
Variant + Size unique
```

---

# 698. PHASE 3 — PRICE RESOLUTION

Implement:

```text
SKU price override
        ↓
if NULL
        ↓
Product base price
```

One centralized price resolver should be used.

Avoid calculating price differently in:

```text
Product Detail
Cart
Checkout
POS
```

---

# 699. PHASE 3 ACCEPTANCE

```text
✓ Product can have multiple colors
✓ Every variant can have multiple images
✓ Each variant supports multiple sizes
✓ Each sellable item has unique SKU
✓ Price resolver works consistently
✓ Variant image order works
```

---

# 700. PHASE 4 — INVENTORY ENGINE

Objective:

Menjadikan Product SKU sebagai sumber inventory online dan POS.

Implement:

```text
Inventory stock
Stock in
Adjustment
Movement history
Low stock
Reservation foundation
```

---

# 701. PHASE 4 — STOCK

For each SKU:

```text
On Hand
Reserved
Available
```

Formula:

```text
Available = On Hand - Reserved
```

---

# 702. PHASE 4 — STOCK IN

Admin:

```text
Select SKU
Quantity
Reference
Note
Confirm
```

Result:

```text
inventory stock updated
inventory movement created
activity log created
```

---

# 703. PHASE 4 — STOCK ADJUSTMENT

Implement:

```text
Adjustment In
Adjustment Out
```

Reason mandatory.

Negative resulting stock forbidden.

---

# 704. PHASE 4 — MOVEMENT HISTORY

Track:

```text
SKU
Type
Quantity
Before
After
Reference
Actor
Timestamp
```

---

# 705. PHASE 4 — RESERVATION SERVICE

Prepare service for:

```text
Reserve Stock
Consume Reservation
Release Reservation
Expire Reservation
```

This must be ready before Checkout.

---

# 706. PHASE 4 — CONCURRENCY TEST

Test case:

```text
Available stock = 1

Customer A checkout
Customer B checkout
```

Expected:

```text
only one valid reservation
```

No negative stock.

---

# 707. PHASE 4 ACCEPTANCE

```text
✓ Stock exists per SKU
✓ Stock in works
✓ Adjustment works
✓ Every change creates movement
✓ Negative stock rejected
✓ Available stock calculation correct
✓ Reservations can be created/released
✓ Concurrent requests do not oversell
```

---

# 708. PHASE 5 — STOREFRONT

Objective:

Membangun visual shopping experience mengikuti video reference.

Pages:

```text
Home
Collection
Category
Search
Product Detail
```

---

# 709. PHASE 5 — GLOBAL STOREFRONT

Build:

```text
Header
Mobile Menu
Search
Footer
Product Card
Product Grid
Editorial Sections
```

Follow:

```text
Fashion editorial
Whitespace
Photography first
Minimal UI
```

---

# 710. PHASE 5 — HOME

Implement:

```text
Hero
New Arrivals
Featured Collection
Editorial Campaign
Selected Products
Brand Message
```

Homepage content may initially be hardcoded/config-based before full CMS.

---

# 711. PHASE 5 — COLLECTION

Implement:

```text
Product grid
Category
Collection
Search
Filter
Sort
Pagination / Load More
```

---

# 712. PHASE 5 — PRODUCT DETAIL

Critical implementation:

```text
Variant selector
Dynamic gallery
Size selector
Stock state
Price
Add to Cart button placeholder
Size guide
Description
Related products
```

---

# 713. PHASE 5 — LIVEWIRE PRODUCT STATE

Livewire handles:

```text
selectedVariant
selectedSku
selectedSize
gallery
stock availability
price
```

Ensure `wire:key` prevents state conflict.

---

# 714. PHASE 5 RESPONSIVE QA

Validate:

```text
375px mobile
768px tablet
1024px laptop
1440px desktop
```

Especially:

```text
Hero
Product grid
Product detail
Navigation
```

---

# 715. PHASE 5 ACCEPTANCE

```text
✓ Storefront follows design direction
✓ Products render correctly
✓ Variant switches gallery
✓ Size state matches inventory
✓ Out-of-stock size disabled
✓ Search works
✓ Filters work
✓ Responsive layout works
```

---

# 716. PHASE 6 — CART & WISHLIST

Objective:

Make shopping selection persistent.

---

# 717. PHASE 6 — CART

Implement:

```text
Guest cart
Authenticated cart
Add item
Update quantity
Remove item
Cart drawer
Full cart
Cart count
```

Cart item identity:

```text
product_sku_id
```

---

# 718. PHASE 6 — GUEST CART

Store guest cart using:

```text
session_id
```

Customer may:

```text
Browse
Add to cart
Close/open pages
```

without login.

---

# 719. PHASE 6 — CART MERGE

On login:

```text
Guest Cart
+
Customer Cart
=
Merged Cart
```

Revalidate stock.

---

# 720. PHASE 6 — CART SERVER VALIDATION

Every mutation validates:

```text
SKU active
Product active
Quantity valid
Stock
```

Cart does not reserve stock.

---

# 721. PHASE 6 — WISHLIST

Implement:

```text
Authenticated only
Add
Remove
Wishlist page
```

Store:

```text
user_id
product_id
```

---

# 722. PHASE 6 ACCEPTANCE

```text
✓ Guest cart works
✓ Cart survives navigation
✓ Login merges cart
✓ Duplicate SKU increments quantity
✓ Quantity cannot exceed available stock
✓ Wishlist requires customer login
```

---

# 723. PHASE 7 — CUSTOMER ADDRESS & CHECKOUT

Objective:

Customer can proceed from cart to valid order creation.

---

# 724. PHASE 7 — ADDRESS

Implement:

```text
Address list
Create
Edit
Delete
Default address
Checkout selection
```

---

# 725. PHASE 7 — CHECKOUT

Single-page checkout:

```text
Contact
Address
Shipping
Voucher placeholder
Payment method
Order summary
```

At this phase voucher can be disabled until Promotion phase.

---

# 726. PHASE 7 — SHIPPING

For MVP initial development:

```text
Configured shipping methods
```

Example:

```text
Regular
Express
```

Cost can initially come from settings/rules.

External courier integration can follow later.

---

# 727. PHASE 7 — FINAL VALIDATION

Before order creation:

```text
Cart not empty
Customer authenticated
Address valid
SKU active
Stock available
Price current
Shipping valid
Totals recalculated
```

---

# 728. PHASE 7 — ORDER CREATION

Transaction:

```text
BEGIN

Create Order
Create Order Items
Save Address Snapshot
Create Stock Reservations
Create Order History

COMMIT
```

Then payment initialized.

---

# 729. PHASE 7 ACCEPTANCE

```text
✓ Address CRUD works
✓ Customer can select address
✓ Shipping selection works
✓ Checkout totals accurate
✓ Browser data cannot manipulate total
✓ Order snapshots correct
✓ Inventory reservation created
```

---

# 730. PHASE 8 — PAYMENT

Objective:

Order can move reliably from unpaid to paid.

---

# 731. PHASE 8 — PAYMENT CORE

Implement:

```text
Create payment attempt
Payment reference
Payment status
Expiry
Retry
Payment instructions
```

---

# 732. PHASE 8 — PROVIDER INTEGRATION

Integrate chosen payment gateway.

Handle:

```text
Create transaction
Redirect/payment URL
Webhook
Verification
Expiry
Failure
```

---

# 733. PHASE 8 — WEBHOOK

Critical flow:

```text
Gateway
↓
Webhook
↓
Verify
↓
Idempotency
↓
Payment Update
↓
Order Update
↓
Inventory Update
```

---

# 734. PHASE 8 — PAYMENT SUCCESS

On verified paid event:

```text
Payment → PAID
Order → PAID
Reservation → CONSUMED
Inventory → SALE
Inventory Movement
Notification
```

---

# 735. PHASE 8 — PAYMENT EXPIRED

```text
Payment → EXPIRED
Order → CANCELLED
Reservation → RELEASED
Notification
```

---

# 736. PHASE 8 — PAYMENT RECOVERY

Test:

```text
Customer pays
Closes browser
Never visits success page
```

Expected:

```text
Webhook still updates order.
```

---

# 737. PHASE 8 ACCEPTANCE

```text
✓ Payment attempts recorded
✓ Webhook verified
✓ Duplicate webhook safe
✓ Paid order updates once
✓ Expiry releases inventory
✓ Retry supported
✓ Browser redirect is not source of truth
```

---

# 738. PHASE 9 — ADMIN ORDER MANAGEMENT

Objective:

Admin can fulfill online orders.

Features:

```text
Order list
Order detail
Processing
Ready to Ship
Shipment
Tracking
Delivered / Complete
Cancellation
```

---

# 739. PHASE 9 — ORDER QUEUE

Views:

```text
Paid / New
Processing
Ready to Ship
Shipped
Completed
Cancelled
```

---

# 740. PHASE 9 — STATUS TRANSITIONS

Enforce:

```text
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

No invalid jumps unless specially authorized.

---

# 741. PHASE 9 — SHIPMENT

Admin inputs:

```text
Courier
Service
Tracking Number
```

Result:

```text
Shipment created
Order → SHIPPED
Customer notification
```

---

# 742. PHASE 9 — CANCELLATION

Implement:

```text
Customer unpaid cancellation
Paid cancellation request
Admin review
Approve
Reject
Stock release/return rules
```

Refund integration can be manual/MVP depending payment gateway capability.

---

# 743. PHASE 9 ACCEPTANCE

```text
✓ Admin can see paid orders
✓ Valid order transitions enforced
✓ Shipping information recorded
✓ Tracking visible to customer
✓ Cancellation rules enforced
✓ History records every transition
```

---

# 744. PHASE 10 — CUSTOMER ACCOUNT

Objective:

Customer can manage post-purchase experience.

Pages:

```text
Account Overview
Orders
Order Detail
Wishlist
Addresses
Notifications
Profile
Security
```

---

# 745. PHASE 10 — ORDER TIMELINE

Display:

```text
Order Placed
Payment Confirmed
Processing
Shipped
Delivered
Completed
```

Data comes from status histories.

---

# 746. PHASE 10 — CUSTOMER OWNERSHIP SECURITY

All account resources enforce:

```text
resource.user_id === auth()->id()
```

Customer cannot access another customer's order via URL manipulation.

---

# 747. PHASE 10 ACCEPTANCE

```text
✓ Customer order history works
✓ Order details use snapshot data
✓ Timeline works
✓ Tracking visible
✓ Address/profile works
✓ Ownership checks pass
```

---

# 748. PHASE 11 — CASHIER POS

Objective:

Physical/store sale uses same product and stock database.

---

# 749. PHASE 11 — POS PRODUCT SEARCH

Search by:

```text
Product Name
SKU
```

Future:

```text
Barcode
```

---

# 750. PHASE 11 — POS CART

Implement:

```text
Select variant
Select size
Add item
Quantity
Remove
Subtotal
Allowed discounts
```

---

# 751. PHASE 11 — CASH PAYMENT

Implement:

```text
Total
Amount Received
Change
Confirmation
```

Reject:

```text
Amount Received < Total
```

---

# 752. PHASE 11 — NON-CASH PAYMENT

Support configured methods such as:

```text
QRIS
TRANSFER
```

Provider integration may reuse existing payment service if suitable.

---

# 753. PHASE 11 — ATOMIC POS COMPLETION

Transaction:

```text
BEGIN

Lock SKU
Validate Stock
Create POS Transaction
Create Items
Deduct Inventory
Create Movements
Record Payment State

COMMIT
```

---

# 754. PHASE 11 — RECEIPT

Implement:

```text
Receipt screen
Printable receipt
Transaction history
Transaction detail
```

---

# 755. PHASE 11 ACCEPTANCE

```text
✓ Cashier can find products
✓ SKU stock visible
✓ POS uses shared inventory
✓ Cash works
✓ Non-cash works
✓ Transaction is atomic
✓ Inventory updates instantly
✓ Receipt available
```

---

# 756. PHASE 12 — VOUCHER & PROMOTION

Objective:

Add controlled marketing functionality after commerce core is stable.

---

# 757. PHASE 12 — VOUCHER

Implement:

```text
Fixed discount
Percentage discount
Minimum purchase
Maximum discount
Date period
Total usage limit
Per-user limit
Active/inactive
```

---

# 758. PHASE 12 — CHECKOUT INTEGRATION

Voucher server validation:

```text
Code exists
Active
Within period
Usage available
Customer eligible
Minimum purchase met
```

---

# 759. PHASE 12 — USAGE RECORD

On successful order/payment according to selected policy:

```text
voucher_usages
```

must be recorded.

Prevent usage race conditions.

---

# 760. PHASE 12 ACCEPTANCE

```text
✓ Voucher validation server-side
✓ Fixed discount works
✓ Percentage discount works
✓ Limits respected
✓ One voucher per order
✓ Stacking disabled
✓ Usage traceable
```

---

# 761. PHASE 13 — REPORTING & MANAGEMENT

Objective:

Turn transactions into useful business information.

---

# 762. PHASE 13 — SALES REPORT

Metrics:

```text
Revenue
Orders
Transactions
Units Sold
Average Order Value
```

Filters:

```text
Date
Channel
```

---

# 763. PHASE 13 — CHANNEL REPORTING

Separate:

```text
ONLINE
POS
```

and combined:

```text
ALL CHANNELS
```

---

# 764. PHASE 13 — PRODUCT REPORT

Metrics:

```text
Units Sold
Revenue
Product Performance
Variant Performance
Best Sellers
```

---

# 765. PHASE 13 — INVENTORY REPORT

```text
Current Stock
Reserved Stock
Available Stock
Low Stock
Out of Stock
Movement
```

---

# 766. PHASE 13 — CUSTOMER REPORT

P1:

```text
New Customers
Returning Customers
Order Count
Customer Purchase Value
```

---

# 767. PHASE 13 — MANAGEMENT DASHBOARD

Build:

```text
Revenue KPI
Order KPI
AOV
Sales Trend
Online vs POS
Best Sellers
Inventory Alerts
Customer Trend
```

Management remains read-only.

---

# 768. PHASE 13 ACCEPTANCE

```text
✓ Reports match transaction data
✓ Cancelled unpaid orders excluded
✓ Date range consistent
✓ Online/POS comparison accurate
✓ Management cannot mutate operational data
```

---

# 769. PHASE 14 — NOTIFICATION & AUDIT

Some notification functionality may be built earlier, but this phase completes it.

---

# 770. PHASE 14 — CUSTOMER NOTIFICATIONS

Events:

```text
Payment confirmed
Order processing
Order shipped
Order completed
Cancellation update
Payment expired
```

---

# 771. PHASE 14 — ADMIN NOTIFICATIONS

Events:

```text
New paid order
Cancellation request
Low stock
Critical inventory state
```

---

# 772. PHASE 14 — ACTIVITY LOG

Track:

```text
Product changes
Price changes
Inventory adjustment
Order status changes
Cancellation
Voucher updates
Role changes
Settings changes
```

---

# 773. PHASE 14 ACCEPTANCE

```text
✓ Notifications generated by events
✓ Unread/read works
✓ Relevant links work
✓ Critical admin actions logged
✓ Old/new values available when applicable
```

---

# 774. PHASE 15 — TESTING & HARDENING

This is not the first time testing occurs.

Testing must happen throughout development.

This phase performs complete system validation.

---

# 775. UNIT TEST TARGETS

Test business services:

```text
Price Resolver
Stock Service
Stock Reservation
Voucher Validator
Order Totals
Order Status Transition
Payment Handler
POS Completion
```

---

# 776. FEATURE TEST TARGETS

Test:

```text
Authentication
Authorization
Catalog
Cart
Checkout
Payment
Order
Cancellation
POS
Inventory
Management Access
```

---

# 777. CRITICAL E2E SCENARIOS

Scenario 1:

```text
Customer
→ Browse
→ Product
→ Cart
→ Login
→ Checkout
→ Pay
→ Order
```

Scenario 2:

```text
Payment expires
→ Reservation released
```

Scenario 3:

```text
Online order stock 1
+
POS attempts same SKU
→ No oversell
```

Scenario 4:

```text
Customer tries other customer's order URL
→ 403/404
```

Scenario 5:

```text
Duplicate payment webhook
→ no duplicate inventory deduction
```

---

# 778. RESPONSIVE QA

Test storefront:

```text
360px
375px
390px
768px
1024px
1280px
1440px+
```

Priority pages:

```text
Home
Collection
Product Detail
Cart
Checkout
Login
Account
```

---

# 779. BROWSER QA

Minimum:

```text
Chrome
Edge
Firefox
Safari
```

Mobile:

```text
Chrome Android
Safari iOS
```

if devices are available.

---

# 780. PERFORMANCE QA

Check:

```text
Image sizes
LCP
Layout shift
N+1 queries
Large Livewire payload
Slow database queries
Product listing pagination
Dashboard queries
```

---

# 781. SECURITY QA

Review:

```text
CSRF
Authorization
IDOR / ownership
Mass assignment
File upload
Rate limiting
Webhook verification
SQL injection protection
XSS
Sensitive data exposure
Debug mode
```

---

# 782. INVENTORY RECONCILIATION TEST

Validate:

```text
Initial Stock
+
Stock In
+
Returns
-
Online Sales
-
POS Sales
+/-
Adjustments
=
Current On Hand
```

No unexplained mismatch.

---

# 783. PAYMENT RECONCILIATION TEST

Validate:

```text
Provider payment
↔
payments table
↔
order payment status
```

Identify:

```text
paid provider / unpaid system
unpaid provider / paid system
duplicate callback
```

---

# 784. PHASE 15 ACCEPTANCE

```text
✓ Critical tests pass
✓ No known overselling issue
✓ No critical authorization issue
✓ Payment idempotency works
✓ Checkout calculations correct
✓ Mobile storefront usable
✓ Production errors hidden
✓ Performance acceptable
```

---

# 785. PHASE 16 — DEPLOYMENT PREPARATION

Prepare production environment.

Checklist:

```text
Production server
Domain
SSL
Database
File storage
Queue
Scheduler
Mail
Payment credentials
Backup
Logging
Monitoring
```

---

# 786. PRODUCTION ENVIRONMENT

Set:

```text
APP_ENV=production
APP_DEBUG=false
```

Ensure:

```text
HTTPS
Secure cookies
Production database
Production payment keys
```

---

# 787. DATABASE DEPLOYMENT

Deployment flow:

```text
Backup
↓
Pull Release
↓
Install dependencies
↓
Build frontend
↓
Run migrations
↓
Cache config/routes/views
↓
Restart workers
↓
Health check
```

---

# 788. FILE STORAGE

Production assets:

```text
Product Images
Collection Images
Hero Images
Profile Photos
```

should use reliable storage.

Avoid relying on temporary deployment filesystem when infrastructure does not guarantee persistence.

---

# 789. QUEUE & SCHEDULER

Background processing may handle:

```text
Email
Notifications
Image processing
Payment follow-up
Stock/payment expiry
Reports/export
```

Scheduler handles:

```text
Payment expiry
Reservation expiry
Cleanup
```

---

# 790. BACKUP

Minimum:

```text
Database backup
Media backup
```

Test restoration.

A backup that has never been restored is not considered fully validated.

---

# 791. PRODUCTION LOGGING

Log:

```text
Application errors
Payment webhook errors
Inventory failures
Queue failures
Critical authorization anomalies
```

Do not log sensitive payment credentials.

---

# 792. PRE-LAUNCH DATA

Prepare:

```text
Admin account
Cashier accounts
Management account

Categories
Sizes
Collections
Products
Variants
Images
Initial stock

Store settings
Shipping options
Payment configuration
```

---

# 793. STAGING / UAT

Before production launch, use staging environment if possible.

Stakeholders test:

```text
Customer journey
Admin operations
POS
Management reports
Payment
Shipping
Cancellation
```

---

# 794. USER ACCEPTANCE TEST — CUSTOMER

Checklist:

```text
Can browse
Can search
Can select color
Can select size
Can add cart
Can login
Can checkout
Can pay
Can view order
Can track order
```

---

# 795. USER ACCEPTANCE TEST — ADMIN

```text
Can create product
Can upload variant images
Can create SKU
Can add stock
Can process order
Can ship order
Can handle cancellation
Can create voucher
```

---

# 796. USER ACCEPTANCE TEST — CASHIER

```text
Can open POS
Can find product
Can select SKU
Can sell
Can accept cash
Can accept configured non-cash method
Can print/view receipt
```

---

# 797. USER ACCEPTANCE TEST — MANAGEMENT

```text
Can view KPIs
Can compare channels
Can view product performance
Can view inventory report
Cannot change operational data
```

---

# 798. GO-LIVE CHECKLIST

```text
✓ Production domain active
✓ HTTPS active
✓ APP_DEBUG=false
✓ Database migrated
✓ Seed/master data ready
✓ Images available
✓ Mail works
✓ Payment production keys configured
✓ Webhook URL active
✓ Queue running
✓ Scheduler running
✓ Backup active
✓ Admin access tested
✓ Customer purchase tested
✓ POS transaction tested
✓ Inventory reconciliation tested
```

---

# 799. LAUNCH SMOKE TEST

Immediately after deployment, test:

```text
Homepage
Product page
Login
Cart
Checkout
Payment initiation
Webhook
Admin login
Order list
POS
```

Do not assume successful deployment means successful application.

---

# 800. MVP LAUNCH DEFINITION

19HOUSE MVP is considered launch-ready when the following journey works:

```text
ADMIN
Create Product
↓
Create Variant
↓
Create SKU
↓
Add Stock

CUSTOMER
Browse
↓
Select Variant
↓
Select Size
↓
Cart
↓
Checkout
↓
Payment
↓
Order

ADMIN
Process
↓
Ship

CUSTOMER
Track

CASHIER
POS Sale
↓
Shared Inventory Updated

MANAGEMENT
View Sales
```

---

# 801. MVP P0 DELIVERY SCOPE

Must be complete:

```text
Authentication & Roles
Product Catalog
Variant
Images
Sizes
SKU
Inventory
Inventory Movement
Storefront
Search
Cart
Address
Checkout
Payment
Order
Order History
Shipping
Cancellation basic
POS
Notifications basic
Admin Dashboard
Management Dashboard basic
```

---

# 802. P1 AFTER MVP

Next development cycle:

```text
Wishlist enhancements
Voucher
Advanced filtering
Advanced reports
Low-stock dashboard
Recommendations
Advanced notifications
Report export
Customer analytics
```

Some P1 items such as Wishlist/Voucher may be moved into MVP if timeline permits, but core commerce takes priority.

---

# 803. P2 FUTURE FEATURES

```text
Product Reviews
Ratings
Back in Stock Notification
Loyalty / Points
Gift Cards
Advanced Promotion Engine
Multi Warehouse
Supplier Management
Purchase Orders
Returns / Exchange Module
Advanced CRM
Advanced Analytics
AI Recommendation
Native Mobile App
```

---

# 804. FUTURE — RETURN & EXCHANGE

A full return/exchange module should not be mixed into simple cancellation.

Future flow:

```text
Delivered
↓
Return Request
↓
Review
↓
Return Shipment
↓
Inspection
↓
Refund / Exchange
↓
Inventory Decision
```

This deserves its own domain.

---

# 805. FUTURE — MULTI WAREHOUSE

Current MVP:

```text
One shared inventory pool
```

Future:

```text
Warehouse
Store
Branch
```

Stock becomes:

```text
SKU + Location
```

Do not introduce multi-location complexity before required.

---

# 806. FUTURE — BARCODE POS

Later:

```text
Barcode Scanner
↓
SKU
↓
Add to POS Cart
```

The current `product_skus.barcode` field can prepare for this feature.

---

# 807. FUTURE — LOYALTY

Possible:

```text
Customer
↓
Points
↓
Reward
↓
Voucher / Benefit
```

Should be introduced only after purchase flow is stable.

---

# 808. DEVELOPMENT ORDER — CRITICAL PATH

Critical path for the project:

```text
AUTH
 ↓
PRODUCT
 ↓
VARIANT
 ↓
SKU
 ↓
INVENTORY
 ↓
STOREFRONT
 ↓
CART
 ↓
CHECKOUT
 ↓
ORDER
 ↓
PAYMENT
 ↓
FULFILLMENT
```

If one of these is unstable, later modules will be affected.

---

# 809. MODULE DEPENDENCY MAP

```text
USERS / ROLE
     │
     ├───────────────────────┐
     ▼                       ▼
CATALOG                    INTERNAL ACCESS
     │
     ▼
VARIANT / SKU
     │
     ▼
INVENTORY
     │
     ├──────────────┐
     ▼              ▼
STOREFRONT          POS
     │              │
     ▼              │
CART                │
     │              │
     ▼              │
CHECKOUT            │
     │              │
     ▼              │
ORDER               │
     │              │
     ▼              │
PAYMENT             │
     │              │
     └──────┬───────┘
            ▼
         REPORT
```

---

# 810. DEVELOPMENT RULE — NO PREMATURE FEATURE EXPANSION

During development, avoid inserting features such as:

```text
Loyalty
AI recommendation
Complex promotion
Product review
Multiwarehouse
Advanced CRM
```

before core transaction is complete.

Reason:

```text
Complexity increases
↓
Core development slows
↓
Testing becomes harder
↓
Launch risk rises
```

---

# 811. SUGGESTED FEATURE COMPLETION ORDER

Recommended backlog order:

```text
01 Authentication
02 Roles / Permissions
03 Categories
04 Products
05 Collections
06 Sizes
07 Variants
08 Images
09 SKU
10 Inventory
11 Stock Movement
12 Reservation
13 Home
14 Shop
15 Search
16 Product Detail
17 Cart
18 Address
19 Checkout
20 Order
21 Payment
22 Admin Order
23 Shipment
24 Customer Account
25 POS
26 Voucher
27 Notification
28 Reporting
29 Management
30 Hardening
31 Deployment
```

---

# 812. DEVELOPMENT DEFINITION OF DONE

A feature is not considered done only because:

```text
"page sudah tampil"
```

Feature is done when:

```text
✓ UI finished
✓ Responsive
✓ Validation
✓ Authorization
✓ Business rules
✓ Database integrity
✓ Error states
✓ Loading states
✓ Success states
✓ Tests
✓ Related logging
✓ No known blocker
```

---

# 813. BUG PRIORITY

## P0 — Critical

```text
Cannot checkout
Incorrect payment
Negative stock
Unauthorized data access
Duplicate transaction
Data loss
```

Must block launch.

## P1 — High

```text
Important workflow broken
Wrong totals
Order state issue
POS problem
Major responsive issue
```

Must be fixed before release where relevant.

## P2 — Medium

```text
Minor UX issue
Non-critical filter issue
Visual inconsistency
```

Can enter next patch if necessary.

## P3 — Low

```text
Cosmetic improvement
Microcopy
Minor visual polish
```

---

# 814. RELEASE STRATEGY

Suggested:

```text
MVP
↓
Stabilization
↓
P1
↓
Optimization
↓
P2
```

Do not launch too many experimental modules simultaneously.

---

# 815. MVP MILESTONE — FOUNDATION COMPLETE

Milestone 1:

```text
Authentication
Roles
Catalog
Variant
SKU
Inventory
```

At this stage:

Admin can create sellable inventory.

---

# 816. MVP MILESTONE — STOREFRONT COMPLETE

Milestone 2:

```text
Home
Shop
Product
Search
Cart
```

At this stage:

Customer can browse and prepare cart.

---

# 817. MVP MILESTONE — COMMERCE COMPLETE

Milestone 3:

```text
Address
Checkout
Order
Payment
```

At this stage:

Customer can actually purchase.

This is the most important milestone.

---

# 818. MVP MILESTONE — FULFILLMENT COMPLETE

Milestone 4:

```text
Admin order handling
Shipment
Tracking
Cancellation
```

At this stage:

Online sale can run end-to-end operationally.

---

# 819. MVP MILESTONE — RETAIL COMPLETE

Milestone 5:

```text
POS
Shared stock
Receipt
Transaction history
```

At this stage:

Online and physical sales are integrated.

---

# 820. MVP MILESTONE — MANAGEMENT COMPLETE

Milestone 6:

```text
Reporting
Management dashboard
Audit
Notifications
```

At this stage:

System provides operational and decision support.

---

# 821. DEVELOPMENT ROADMAP SUMMARY

Complete sequence:

```text
PHASE 0
Project Setup
        ↓
PHASE 1
Authentication & Roles
        ↓
PHASE 2
Catalog
        ↓
PHASE 3
Variant / Image / Size / SKU
        ↓
PHASE 4
Inventory
        ↓
PHASE 5
Storefront
        ↓
PHASE 6
Cart / Wishlist
        ↓
PHASE 7
Address / Checkout
        ↓
PHASE 8
Payment
        ↓
PHASE 9
Order Fulfillment
        ↓
PHASE 10
Customer Account
        ↓
PHASE 11
POS
        ↓
PHASE 12
Voucher
        ↓
PHASE 13
Reporting / Management
        ↓
PHASE 14
Notifications / Audit
        ↓
PHASE 15
Testing / Hardening
        ↓
PHASE 16
Deployment / Launch
        ↓
POST MVP
Enhancement
```

---

# 822. FINAL DEVELOPMENT PRINCIPLE

Prioritas development 19HOUSE harus selalu mengikuti:

```text
DATA INTEGRITY
      ↓
BUSINESS LOGIC
      ↓
FUNCTIONAL FLOW
      ↓
USABILITY
      ↓
VISUAL POLISH
      ↓
OPTIONAL FEATURES
```

Visual storefront memang sangat penting bagi 19HOUSE, tetapi sistem tidak boleh terlihat bagus sementara:

```text
Stock tidak akurat
Payment tidak reliable
Order tidak konsisten
POS tidak sinkron
```

Target akhirnya adalah:

> **19HOUSE terlihat seperti fashion editorial storefront di depan, tetapi bekerja seperti sistem retail yang terstruktur di belakang.**

---

# 823. PRD COMPLETION STATUS

Dengan Development Roadmap ini, bagian utama PRD yang telah diselesaikan adalah:

```text
✓ Product Overview
✓ Vision & Goals
✓ Scope
✓ Functional Requirements
✓ Non-Functional Requirements
✓ Roles & Permissions
✓ Business Rules
✓ Use Cases
✓ User Flow
✓ Sitemap
✓ Database Design / ERD
✓ UI/UX Design System
✓ Wireframe Specification
✓ Development Roadmap
```

Bagian **Technology Stack & System Architecture** sengaja tidak dimasukkan sesuai keputusan project saat ini.

PRD 19HOUSE sekarang sudah cukup lengkap untuk digunakan sebagai dasar **design, database implementation, backlog development, dan pembangunan aplikasi secara bertahap**.
