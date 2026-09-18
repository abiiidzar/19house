# 564. WIREFRAME SPECIFICATION

Wireframe 19HOUSE dibagi menjadi empat area utama:

```text
A. STOREFRONT / CUSTOMER
B. CUSTOMER ACCOUNT
C. ADMIN
D. CASHIER / POS
E. MANAGEMENT
```

Prinsip wireframe:

```text
CONTENT FIRST
PRODUCT FIRST
CLEAR HIERARCHY
MINIMAL INTERFACE
MOBILE FIRST
RESPONSIVE
```

---

# 565. STOREFRONT GLOBAL STRUCTURE

Desktop:

```text
┌──────────────────────────────────────────────────────────────┐
│ OPTIONAL ANNOUNCEMENT                                       │
├──────────────────────────────────────────────────────────────┤
│ 19HOUSE     SHOP   NEW   COLLECTIONS       SEARCH  ♡  BAG  │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│                       PAGE CONTENT                           │
│                                                              │
├──────────────────────────────────────────────────────────────┤
│                         FOOTER                               │
└──────────────────────────────────────────────────────────────┘
```

Mobile:

```text
┌──────────────────────────────┐
│ OPTIONAL ANNOUNCEMENT        │
├──────────────────────────────┤
│ ☰       19HOUSE      ⌕  BAG │
├──────────────────────────────┤
│                              │
│        PAGE CONTENT          │
│                              │
├──────────────────────────────┤
│            FOOTER            │
└──────────────────────────────┘
```

---

# 566. WIREFRAME — HOME PAGE

## Desktop

Home harus terasa seperti halaman sebuah fashion label, bukan marketplace.

```text
┌──────────────────────────────────────────────────────────────┐
│ 19HOUSE       SHOP   NEW   COLLECTIONS          ⌕  ♡  BAG  │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│                                                              │
│                                                              │
│                      HERO PHOTOGRAPHY                        │
│                                                              │
│                                                              │
│   SUMMER 26                                                  │
│   NEW COLLECTION                                             │
│                                                              │
│   DISCOVER →                                                 │
│                                                              │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│                       NEW ARRIVALS                           │
│                                                              │
│  ┌────────────┐ ┌────────────┐ ┌────────────┐ ┌────────────┐ │
│  │            │ │            │ │            │ │            │ │
│  │ PRODUCT    │ │ PRODUCT    │ │ PRODUCT    │ │ PRODUCT    │ │
│  │ IMAGE      │ │ IMAGE      │ │ IMAGE      │ │ IMAGE      │ │
│  │            │ │            │ │            │ │            │ │
│  └────────────┘ └────────────┘ └────────────┘ └────────────┘ │
│  Product Name   Product Name   Product Name   Product Name   │
│  Rp199.000      Rp229.000      Rp249.000      Rp189.000      │
│                                                              │
│                       VIEW ALL →                             │
│                                                              │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│  ┌───────────────────────────┬──────────────────────────────┐ │
│  │                           │                              │ │
│  │                           │                              │ │
│  │     EDITORIAL IMAGE       │      EDITORIAL IMAGE        │ │
│  │                           │                              │ │
│  │                           │                              │ │
│  └───────────────────────────┴──────────────────────────────┘ │
│                                                              │
│                       ESSENTIAL FORM                         │
│                  DISCOVER COLLECTION →                      │
│                                                              │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│                    SELECTED PRODUCTS                         │
│                                                              │
│       PRODUCT         PRODUCT         PRODUCT                │
│                                                              │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│                         19HOUSE                              │
│                                                              │
│              Designed for everyday movement.                │
│                                                              │
├──────────────────────────────────────────────────────────────┤
│ FOOTER                                                       │
└──────────────────────────────────────────────────────────────┘
```

---

# 567. HOME PAGE CONTENT HIERARCHY

Recommended:

```text
Header
↓
Hero
↓
New Arrivals
↓
Editorial Campaign
↓
Featured Collection
↓
Selected Products
↓
Brand Statement
↓
Footer
```

Tidak semua section harus tampil pada setiap campaign.

Admin dapat mengatur section tertentu sebagai active/inactive.

---

# 568. HOME HERO BEHAVIOR

Hero image:

```text
Desktop
80–100vh
```

Text menggunakan negative space pada photography.

Possible positions:

```text
Bottom Left
Bottom Center
Center
```

Admin tidak memilih posisi bebas per pixel.

Sediakan predefined position agar visual tetap konsisten.

---

# 569. HOME MOBILE

```text
┌──────────────────────────────┐
│ ☰       19HOUSE      ⌕  BAG │
├──────────────────────────────┤
│                              │
│                              │
│                              │
│        HERO IMAGE            │
│                              │
│                              │
│ SUMMER 26                    │
│ NEW COLLECTION               │
│                              │
│ DISCOVER →                   │
│                              │
├──────────────────────────────┤
│                              │
│ NEW ARRIVALS                 │
│                              │
│ ┌────────────┐ ┌────────────┐│
│ │ PRODUCT    │ │ PRODUCT    ││
│ │            │ │            ││
│ └────────────┘ └────────────┘│
│ Product        Product       │
│ Rp199k         Rp229k        │
│                              │
│ ┌────────────┐ ┌────────────┐│
│ │ PRODUCT    │ │ PRODUCT    ││
│ └────────────┘ └────────────┘│
│                              │
│ VIEW ALL →                   │
├──────────────────────────────┤
│                              │
│     EDITORIAL IMAGE          │
│                              │
│ ESSENTIAL FORM               │
│ DISCOVER →                   │
│                              │
├──────────────────────────────┤
│         19HOUSE              │
│                              │
│          FOOTER              │
└──────────────────────────────┘
```

---

# 570. WIREFRAME — COLLECTION / SHOP

Desktop:

```text
┌──────────────────────────────────────────────────────────────┐
│ HEADER                                                       │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│ SHOP                                                         │
│                                                              │
│ Explore the latest pieces from 19HOUSE.                      │
│                                                              │
│ 48 PRODUCTS                                                  │
│                                                              │
├──────────────────────────────────────────────────────────────┤
│ FILTER +                                      SORT: NEWEST   │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│ ┌────────────┐ ┌────────────┐ ┌────────────┐ ┌────────────┐  │
│ │            │ │            │ │            │ │            │  │
│ │ PRODUCT    │ │ PRODUCT    │ │ PRODUCT    │ │ PRODUCT    │  │
│ │ IMAGE      │ │ IMAGE      │ │ IMAGE      │ │ IMAGE      │  │
│ │            │ │            │ │            │ │            │  │
│ └────────────┘ └────────────┘ └────────────┘ └────────────┘  │
│ Name           Name           Name           Name            │
│ Rp...          Rp...          Rp...          Rp...           │
│                                                              │
│ ┌────────────┐ ┌────────────┐ ┌────────────┐ ┌────────────┐  │
│ │ PRODUCT    │ │ PRODUCT    │ │ PRODUCT    │ │ PRODUCT    │  │
│ │            │ │            │ │            │ │            │  │
│ └────────────┘ └────────────┘ └────────────┘ └────────────┘  │
│ Name           Name           Name           Name            │
│ Rp...          Rp...          Rp...          Rp...           │
│                                                              │
│                       LOAD MORE                              │
│                                                              │
├──────────────────────────────────────────────────────────────┤
│ FOOTER                                                       │
└──────────────────────────────────────────────────────────────┘
```

---

# 571. COLLECTION FILTER

Click:

```text
FILTER +
```

dapat membuka drawer:

```text
┌────────────────────────────────────┐
│ FILTER                          ×  │
├────────────────────────────────────┤
│                                    │
│ CATEGORY                         + │
│                                    │
│ SIZE                             + │
│                                    │
│ COLOR                            + │
│                                    │
│ PRICE                            + │
│                                    │
│ AVAILABILITY                     + │
│                                    │
│                                    │
│ [        APPLY FILTERS         ]   │
│                                    │
│ CLEAR ALL                          │
└────────────────────────────────────┘
```

---

# 572. COLLECTION MOBILE

```text
┌──────────────────────────────┐
│ HEADER                       │
├──────────────────────────────┤
│                              │
│ SHOP                         │
│                              │
│ 48 PRODUCTS                  │
│                              │
├──────────────────────────────┤
│ FILTER                SORT   │
├──────────────────────────────┤
│                              │
│ ┌────────────┐ ┌────────────┐│
│ │ PRODUCT    │ │ PRODUCT    ││
│ │ IMAGE      │ │ IMAGE      ││
│ │            │ │            ││
│ └────────────┘ └────────────┘│
│ Name           Name          │
│ Rp...          Rp...         │
│                              │
│ ┌────────────┐ ┌────────────┐│
│ │ PRODUCT    │ │ PRODUCT    ││
│ └────────────┘ └────────────┘│
│ Name           Name          │
│ Rp...          Rp...         │
│                              │
│         LOAD MORE            │
└──────────────────────────────┘
```

---

# 573. WIREFRAME — SEARCH

Desktop search overlay:

```text
┌──────────────────────────────────────────────────────────────┐
│ SEARCH                                                    × │
│                                                              │
│ Search products...                                           │
│ ──────────────────────────────────────────────────────────── │
│                                                              │
│ POPULAR SEARCHES                                             │
│                                                              │
│ Oversized Tee                                                │
│ Outerwear                                                    │
│ Black                                                        │
│ New Arrivals                                                 │
│                                                              │
└──────────────────────────────────────────────────────────────┘
```

After typing:

```text
SEARCH

oversized
────────────────────────────────────

PRODUCTS

[IMG] Essential Oversized Tee
      Black / White
      Rp199.000

[IMG] Graphic Oversized Tee
      Rp229.000

VIEW ALL RESULTS →
```

---

# 574. WIREFRAME — PRODUCT DETAIL

Desktop:

```text
┌──────────────────────────────────────────────────────────────┐
│ HEADER                                                       │
├─────────────────────────────────────┬────────────────────────┤
│                                     │                        │
│ ┌────────────────┐ ┌──────────────┐ │ ESSENTIAL             │
│ │                │ │              │ │ OVERSIZED TEE          │
│ │ IMAGE 01       │ │ IMAGE 02     │ │                        │
│ │                │ │              │ │ Rp199.000              │
│ └────────────────┘ └──────────────┘ │                        │
│                                     │ COLOR — BLACK          │
│ ┌────────────────┐ ┌──────────────┐ │ ●  ○  ○                │
│ │                │ │              │ │                        │
│ │ IMAGE 03       │ │ IMAGE 04     │ │ SIZE                   │
│ │                │ │              │ │                        │
│ └────────────────┘ └──────────────┘ │ [S] [M] [L] [XL]      │
│                                     │                        │
│                                     │ SIZE GUIDE →           │
│                                     │                        │
│                                     │ [   ADD TO CART   ]    │
│                                     │                        │
│                                     │ PRODUCT DETAILS      + │
│                                     │ SHIPPING & RETURNS   + │
│                                     │ CARE                 + │
│                                     │                        │
└─────────────────────────────────────┴────────────────────────┘
```

Right panel:

```text
sticky
```

while gallery scrolls.

---

# 575. PRODUCT DETAIL — VARIANT SWITCH

Initial:

```text
COLOR — BLACK

● Black
○ White
```

Gallery:

```text
Black Image 01
Black Image 02
Black Image 03
```

User chooses White:

```text
COLOR — WHITE

○ Black
● White
```

Gallery updates:

```text
White Image 01
White Image 02
White Image 03
```

No full page reload.

---

# 576. PRODUCT DETAIL — SIZE STATE

```text
SIZE

[S] [M] [L] [XL]
```

Selected:

```text
[S] [■M■] [L] [XL]
```

Out of stock:

```text
[S] [M] [L̶] [XL]
```

If user clicks Add to Cart without size:

```text
PLEASE SELECT A SIZE.
```

---

# 577. PRODUCT DETAIL MOBILE

```text
┌──────────────────────────────┐
│ HEADER                       │
├──────────────────────────────┤
│                              │
│       PRODUCT IMAGE          │
│                              │
│          ● ○ ○ ○             │
├──────────────────────────────┤
│                              │
│ ESSENTIAL OVERSIZED TEE      │
│ Rp199.000                    │
│                              │
│ COLOR — BLACK                │
│ ●  ○                         │
│                              │
│ SIZE                         │
│                              │
│ [ S ] [ M ] [ L ] [ XL ]    │
│                              │
│ SIZE GUIDE →                 │
│                              │
│ [       ADD TO CART       ]  │
│                              │
│ PRODUCT DETAILS           +  │
│ SHIPPING & RETURNS        +  │
│ CARE                      +  │
│                              │
├──────────────────────────────┤
│ YOU MAY ALSO LIKE            │
│                              │
│ PRODUCT     PRODUCT          │
└──────────────────────────────┘
```

Gallery dapat swipe horizontal.

---

# 578. WIREFRAME — CART DRAWER

Desktop:

```text
                           ┌──────────────────────────────┐
                           │ YOUR BAG                 ×  │
                           ├──────────────────────────────┤
                           │                              │
                           │ ┌────────┐                   │
                           │ │ IMAGE  │ Essential Tee     │
                           │ │        │ Black / M         │
                           │ └────────┘ Rp199.000         │
                           │                              │
                           │      −   1   +      REMOVE   │
                           │                              │
                           ├──────────────────────────────┤
                           │ ┌────────┐                   │
                           │ │ IMAGE  │ Essential Pants   │
                           │ └────────┘ Black / L         │
                           │            Rp299.000         │
                           │                              │
                           ├──────────────────────────────┤
                           │                              │
                           │ SUBTOTAL          Rp498.000  │
                           │                              │
                           │ Shipping calculated          │
                           │ at checkout.                 │
                           │                              │
                           │ [       CHECKOUT          ]  │
                           │                              │
                           │ VIEW BAG →                   │
                           └──────────────────────────────┘
```

---

# 579. WIREFRAME — FULL CART PAGE

```text
┌──────────────────────────────────────────────────────────────┐
│ HEADER                                                       │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│ YOUR BAG                                                     │
│                                                              │
│ ┌──────────────────────────────────┬───────────────────────┐  │
│ │ ITEMS                            │ SUMMARY               │  │
│ │                                  │                       │  │
│ │ [IMG] Essential Tee             │ SUBTOTAL Rp498.000    │  │
│ │       Black / M                  │                       │  │
│ │       Rp199.000                  │ Shipping calculated   │  │
│ │       − 1 +       REMOVE         │ at checkout           │  │
│ │                                  │                       │  │
│ │ [IMG] Essential Pants           │ [    CHECKOUT      ]  │  │
│ │       Black / L                  │                       │  │
│ │       Rp299.000                  │                       │  │
│ └──────────────────────────────────┴───────────────────────┘  │
│                                                              │
│ CONTINUE SHOPPING →                                          │
└──────────────────────────────────────────────────────────────┘
```

---

# 580. EMPTY CART

```text
YOUR BAG IS EMPTY

Explore the latest pieces from 19HOUSE.

[ SHOP NEW ARRIVALS ]
```

No large decorative illustration required.

---

# 581. WIREFRAME — LOGIN

Desktop:

```text
┌──────────────────────────────┬───────────────────────────────┐
│                              │                               │
│                              │ SIGN IN                       │
│                              │                               │
│                              │ Welcome back.                 │
│      EDITORIAL IMAGE         │                               │
│                              │ EMAIL                         │
│                              │ [________________________]    │
│                              │                               │
│                              │ PASSWORD                      │
│                              │ [________________________]    │
│                              │                               │
│                              │ FORGOT PASSWORD?              │
│                              │                               │
│                              │ [        SIGN IN         ]    │
│                              │                               │
│                              │ New to 19HOUSE? CREATE ACCOUNT│
└──────────────────────────────┴───────────────────────────────┘
```

---

# 582. LOGIN MOBILE

```text
┌──────────────────────────────┐
│                              │
│           19HOUSE            │
│                              │
│ SIGN IN                      │
│                              │
│ EMAIL                        │
│ [________________________]   │
│                              │
│ PASSWORD                     │
│ [________________________]   │
│                              │
│ FORGOT PASSWORD?             │
│                              │
│ [        SIGN IN         ]   │
│                              │
│ CREATE ACCOUNT →             │
│                              │
└──────────────────────────────┘
```

Editorial image dapat dihilangkan di mobile agar login lebih cepat.

---

# 583. WIREFRAME — REGISTER

```text
CREATE ACCOUNT

NAME
[________________________]

EMAIL
[________________________]

PHONE
[________________________]

PASSWORD
[________________________]

CONFIRM PASSWORD
[________________________]

[      CREATE ACCOUNT      ]

Already have an account?
SIGN IN →
```

Form tetap sederhana.

Address tidak perlu diminta saat register.

Address baru diminta ketika diperlukan saat checkout atau Account → Addresses.

---

# 584. WIREFRAME — CHECKOUT

Desktop:

```text
┌──────────────────────────────────────────────────────────────┐
│                         19HOUSE                              │
│                      SECURE CHECKOUT                         │
├──────────────────────────────────┬───────────────────────────┤
│                                  │                           │
│ CONTACT                          │ ORDER SUMMARY             │
│                                  │                           │
│ abi@email.com                    │ [IMG] Essential Tee      │
│                                  │       Black / M           │
│ SHIPPING ADDRESS                 │       ×1                  │
│                                  │       Rp199.000           │
│ [ Address Card                ]  │                           │
│                                  │ [IMG] Pants              │
│ + ADD NEW ADDRESS                │       Black / L           │
│                                  │       ×1                  │
│ SHIPPING METHOD                  │       Rp299.000           │
│                                  │                           │
│ ○ Regular        Rp20.000        │ ────────────────────────  │
│ ○ Express        Rp35.000        │                           │
│                                  │ SUBTOTAL       Rp498.000 │
│ PAYMENT                          │ SHIPPING        Rp20.000  │
│                                  │ DISCOUNT              -  │
│ ○ Payment Gateway                │                           │
│                                  │ TOTAL          Rp518.000 │
│ VOUCHER                          │                           │
│                                  │ [     PLACE ORDER      ] │
│ [ CODE________ ] [ APPLY ]       │                           │
│                                  │                           │
└──────────────────────────────────┴───────────────────────────┘
```

---

# 585. CHECKOUT MOBILE

```text
┌──────────────────────────────┐
│           19HOUSE            │
│       SECURE CHECKOUT        │
├──────────────────────────────┤
│ CONTACT                      │
│                              │
│ abi@email.com                │
│                              │
├──────────────────────────────┤
│ SHIPPING ADDRESS             │
│                              │
│ Home                         │
│ Abi                          │
│ 08xxxx                       │
│ Padang...                    │
│                              │
│ CHANGE →                     │
├──────────────────────────────┤
│ SHIPPING                     │
│                              │
│ ○ Regular       Rp20.000     │
│ ○ Express       Rp35.000     │
├──────────────────────────────┤
│ VOUCHER                      │
│                              │
│ [CODE________] [APPLY]       │
├──────────────────────────────┤
│ PAYMENT                      │
│                              │
│ ○ Payment Gateway            │
├──────────────────────────────┤
│ ORDER SUMMARY                │
│                              │
│ [IMG] Tee Black / M ×1       │
│ Rp199.000                    │
│                              │
│ SUBTOTAL        Rp199.000    │
│ SHIPPING         Rp20.000    │
│                              │
│ TOTAL           Rp219.000    │
│                              │
│ [      PLACE ORDER       ]   │
└──────────────────────────────┘
```

---

# 586. WIREFRAME — PAYMENT PENDING

```text
┌──────────────────────────────────────┐
│                                      │
│             PAYMENT                  │
│                                      │
│          Rp518.000                   │
│                                      │
│ Complete payment before              │
│                                      │
│ 12 Sep 2026 • 02:30                  │
│                                      │
│ ORDER                                │
│ #19H-20260912-00001                  │
│                                      │
│ [     CONTINUE PAYMENT      ]        │
│                                      │
│ VIEW ORDER →                         │
│                                      │
└──────────────────────────────────────┘
```

---

# 587. WIREFRAME — PAYMENT SUCCESS

```text
PAYMENT CONFIRMED

Thank you for your order.

#19H-20260912-00001

Rp518.000

We are preparing your order.

[ VIEW ORDER ]

CONTINUE SHOPPING →
```

---

# 588. WIREFRAME — CUSTOMER ACCOUNT

Desktop:

```text
┌──────────────────────────────────────────────────────────────┐
│ HEADER                                                       │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│ ACCOUNT                                                      │
│                                                              │
│ ┌──────────────────────┬───────────────────────────────────┐ │
│ │                      │                                   │ │
│ │ OVERVIEW             │ Hello, Customer                   │ │
│ │ ORDERS               │                                   │ │
│ │ WISHLIST             │ RECENT ORDER                      │ │
│ │ ADDRESSES            │                                   │ │
│ │ PROFILE              │ #19H-20260912-00001              │ │
│ │                      │ Processing                        │ │
│ │ LOGOUT               │ Rp518.000                         │ │
│ │                      │                                   │ │
│ │                      │ VIEW ORDER →                      │ │
│ │                      │                                   │ │
│ └──────────────────────┴───────────────────────────────────┘ │
└──────────────────────────────────────────────────────────────┘
```

---

# 589. CUSTOMER ACCOUNT MOBILE

```text
ACCOUNT

Hello, Customer

────────────────────────

ORDERS                →
WISHLIST              →
ADDRESSES             →
PROFILE               →
NOTIFICATIONS         →

────────────────────────

LOGOUT
```

Avoid permanent sidebar on mobile.

---

# 590. WIREFRAME — ORDER LIST

```text
ORDERS

┌────────────────────────────────────────────────────┐
│ #19H-20260912-00001              PROCESSING       │
│                                                    │
│ 12 September 2026                                  │
│                                                    │
│ [IMG] [IMG]                                        │
│                                                    │
│ 2 Items                              Rp518.000      │
│                                                    │
│ VIEW ORDER →                                      │
└────────────────────────────────────────────────────┘

┌────────────────────────────────────────────────────┐
│ #19H-20260905-00018              COMPLETED        │
│ ...                                                │
└────────────────────────────────────────────────────┘
```

---

# 591. WIREFRAME — ORDER DETAIL

```text
ORDER
#19H-20260912-00001

PROCESSING

────────────────────────────────────────

ORDER TIMELINE

● ORDER PLACED
│  12 Sep • 01:30
│
● PAYMENT CONFIRMED
│  12 Sep • 01:32
│
● PROCESSING
│
○ SHIPPED
│
○ DELIVERED

────────────────────────────────────────

ITEMS

[IMG]
Essential Oversized Tee
Black / M
1 × Rp199.000

[IMG]
Essential Pants
Black / L
1 × Rp299.000

────────────────────────────────────────

SHIPPING ADDRESS

Customer Name
08xxxxxxxxxx
Address...

────────────────────────────────────────

PAYMENT

Payment Method
PAID

────────────────────────────────────────

SUMMARY

Subtotal          Rp498.000
Shipping           Rp20.000
Discount                  -
──────────────────────────
TOTAL             Rp518.000
```

---

# 592. ORDER DETAIL — SHIPPED STATE

Tambahkan:

```text
SHIPMENT

JNE REGULAR
Tracking Number

JP1234567890

[ TRACK PACKAGE ]
```

---

# 593. ORDER CANCELLATION

Jika masih:

```text
PENDING_PAYMENT
```

Customer dapat melihat:

```text
CANCEL ORDER
```

Confirmation:

```text
CANCEL ORDER?

Are you sure you want to cancel
#19H-20260912-00001?

Reason
[ Select reason ▼ ]

[ KEEP ORDER ]

[ CANCEL ORDER ]
```

---

# 594. WIREFRAME — WISHLIST

```text
WISHLIST

┌────────────┐ ┌────────────┐ ┌────────────┐
│ PRODUCT    │ │ PRODUCT    │ │ PRODUCT    │
│            │ │            │ │            │
│         ♥  │ │         ♥  │ │         ♥  │
└────────────┘ └────────────┘ └────────────┘

Product Name   Product Name   Product Name
Rp199.000      Rp249.000      Rp299.000
```

Mobile:

```text
2-column
```

seperti collection.

---

# 595. WIREFRAME — ADDRESSES

```text
ADDRESSES

                              + ADD ADDRESS

┌──────────────────────────────────────────┐
│ HOME                             DEFAULT │
│                                          │
│ Customer Name                            │
│ 08xxxxxxxxxx                             │
│ Jl. ...                                  │
│ Padang, Sumatera Barat                   │
│                                          │
│ EDIT                           DELETE     │
└──────────────────────────────────────────┘
```

---

# 596. WIREFRAME — NOTIFICATIONS

```text
NOTIFICATIONS

ALL      ORDERS      PAYMENTS

────────────────────────────────────────

● ORDER SHIPPED
  Your order #19H-... has been shipped.
  2 hours ago

────────────────────────────────────────

● PAYMENT CONFIRMED
  Payment for #19H-... was successful.
  Yesterday

────────────────────────────────────────

  ORDER COMPLETED
  Order #19H-... has been completed.
  5 Sep
```

Unread menggunakan subtle dot/weight.

---

# 597. ADMIN APPLICATION SHELL

Desktop:

```text
┌───────────────────┬──────────────────────────────────────────┐
│                   │ PAGE TITLE             🔔    PROFILE ▼ │
│ 19HOUSE           ├──────────────────────────────────────────┤
│                   │                                          │
│ Dashboard         │                                          │
│                   │                                          │
│ COMMERCE          │             PAGE CONTENT                 │
│ Orders            │                                          │
│ Payments          │                                          │
│                   │                                          │
│ CATALOG           │                                          │
│ Products          │                                          │
│ Categories        │                                          │
│ Collections       │                                          │
│                   │                                          │
│ INVENTORY         │                                          │
│ Stock             │                                          │
│ Movements         │                                          │
│                   │                                          │
│ MARKETING         │                                          │
│ Vouchers          │                                          │
│                   │                                          │
│ SYSTEM            │                                          │
│ Users             │                                          │
│ Reports           │                                          │
│ Settings          │                                          │
│ Activity Log      │                                          │
└───────────────────┴──────────────────────────────────────────┘
```

---

# 598. ADMIN SIDEBAR STRUCTURE

Recommended:

```text
19HOUSE

OVERVIEW
Dashboard

COMMERCE
Orders
Payments
Cancellations

CATALOG
Products
Categories
Collections
Sizes

INVENTORY
Stock
Stock Movements

MARKETING
Vouchers

REPORTS
Sales
Inventory
Products

SYSTEM
Users
Activity Logs
Settings
```

---

# 599. WIREFRAME — ADMIN DASHBOARD

```text
DASHBOARD                         Today ▼

────────────────────────────────────────────────────

┌────────────────┐ ┌────────────────┐ ┌────────────────┐
│ TODAY SALES    │ │ ORDERS         │ │ LOW STOCK      │
│                │ │                │ │                │
│ Rp8.450.000    │ │ 32             │ │ 8 SKU          │
│ +8.2%          │ │ +4             │ │ VIEW →         │
└────────────────┘ └────────────────┘ └────────────────┘

────────────────────────────────────────────────────

SALES OVERVIEW

┌──────────────────────────────────────────────────┐
│                                                  │
│                  LINE CHART                      │
│                                                  │
└──────────────────────────────────────────────────┘

────────────────────────────────────────────────────

RECENT ORDERS

Order            Customer       Total       Status
#19H-001         Customer A     Rp499k      Paid
#19H-002         Customer B     Rp299k      Processing
#19H-003         Customer C     Rp599k      Shipped

VIEW ALL →
```

---

# 600. WIREFRAME — ADMIN PRODUCTS

```text
PRODUCTS

[ Search products... ]   Category ▼   Status ▼

                                      + ADD PRODUCT

────────────────────────────────────────────────────────────

IMAGE   PRODUCT          CATEGORY       PRICE       STATUS

[IMG]   Essential Tee    T-Shirts       Rp199k      Active
        6 SKUs

[IMG]   Essential Pants  Bottoms        Rp299k      Active
        8 SKUs

[IMG]   Hoodie 01        Outerwear      Rp399k      Draft

────────────────────────────────────────────────────────────

                        < 1 2 3 >
```

---

# 601. WIREFRAME — CREATE / EDIT PRODUCT

```text
PRODUCT INFORMATION

Product Name
[________________________________]

Category
[ Select Category ▼ ]

Description
[________________________________]
[________________________________]

Base Price
[________________________________]

Status
[ Draft ▼ ]

────────────────────────────────────────────

VARIANTS

BLACK

Color
[ Black ]   HEX [ #000000 ]

IMAGES

[ + Upload ]
[IMG] [IMG] [IMG]

SIZES / SKU

SIZE    SKU                 PRICE       STOCK

S       19H-TEE-BLK-S       Default     10
M       19H-TEE-BLK-M       Default     15
L       19H-TEE-BLK-L       Default      8

+ ADD SIZE

────────────────────────────────────────────

+ ADD VARIANT

────────────────────────────────────────────

[ SAVE DRAFT ]             [ PUBLISH ]
```

---

# 602. PRODUCT EDIT — VARIANT ORGANIZATION

Recommended UI:

```text
VARIANTS

[ BLACK ] [ WHITE ] [+ ADD VARIANT]
```

Click Black:

```text
BLACK
├── Images
├── Size S / SKU / Stock
├── Size M / SKU / Stock
└── Size L / SKU / Stock
```

Click White switches editor context.

This prevents excessively long forms.

---

# 603. WIREFRAME — ADMIN INVENTORY

```text
INVENTORY

[ Search SKU / Product ]     Stock Status ▼

──────────────────────────────────────────────────────────────

PRODUCT          SKU               VARIANT  SIZE  STOCK  RESERVED AVAILABLE

Essential Tee    19H-TEE-BLK-S     Black    S     10     2        8
Essential Tee    19H-TEE-BLK-M     Black    M     15     0       15
Essential Tee    19H-TEE-WHT-M     White    M      4     1        3

──────────────────────────────────────────────────────────────
```

Row action:

```text
VIEW MOVEMENT
ADJUST STOCK
STOCK IN
```

---

# 604. WIREFRAME — STOCK ADJUSTMENT

```text
ADJUST STOCK

Essential Oversized Tee
Black / M

SKU
19H-TEE-BLK-M

CURRENT STOCK
15

TYPE
○ Adjustment In
○ Adjustment Out

QUANTITY
[ 2 ]

REASON
[________________________________]
[________________________________]

Resulting stock: 17

[ CANCEL ]             [ CONFIRM ADJUSTMENT ]
```

---

# 605. WIREFRAME — STOCK MOVEMENTS

```text
STOCK MOVEMENTS

[ SKU / Product ]   Type ▼   Date ▼

──────────────────────────────────────────────────────────

DATE       PRODUCT / SKU      TYPE            QTY   BEFORE AFTER

12 Sep     Tee Black M        ONLINE SALE     -1     15     14
12 Sep     Tee Black M        STOCK IN       +10      5     15
11 Sep     Tee White S        POS SALE        -1      8      7
```

---

# 606. WIREFRAME — ADMIN ORDERS

```text
ORDERS

[ Search Order / Customer ]

ALL | PENDING | PAID | PROCESSING | SHIPPED | COMPLETED

──────────────────────────────────────────────────────────────

ORDER          CUSTOMER       DATE       TOTAL      STATUS

#19H-00021     Customer A     12 Sep     Rp518k     PAID
#19H-00020     Customer B     12 Sep     Rp299k     PROCESSING
#19H-00019     Customer C     11 Sep     Rp729k     SHIPPED
```

---

# 607. WIREFRAME — ADMIN ORDER DETAIL

```text
ORDER #19H-20260912-00021

PAID

────────────────────────────────────────────

CUSTOMER
Customer Name
Email
Phone

SHIPPING ADDRESS
...

────────────────────────────────────────────

ITEMS

[IMG] Essential Tee
      Black / M
      ×1
      Rp199.000

────────────────────────────────────────────

PAYMENT
PAID
Provider...
Reference...

────────────────────────────────────────────

FULFILLMENT

Current:
PAID

[ MARK AS PROCESSING ]

After processing:

[ MARK READY TO SHIP ]

Then:

Courier
[________]

Tracking Number
[________________]

[ MARK AS SHIPPED ]

────────────────────────────────────────────

ORDER HISTORY

12 Sep 01:32   Payment confirmed     SYSTEM
12 Sep 01:31   Order created         CUSTOMER
```

---

# 608. WIREFRAME — CANCELLATION MANAGEMENT

```text
CANCELLATION REQUESTS

Order          Customer       Reason       Status

#19H-001       Customer A     Wrong size   Requested

────────────────────────────────────────

REQUEST DETAIL

Order
Customer
Payment Status
Current Order Status
Reason

[ REJECT ]

[ APPROVE CANCELLATION ]
```

Confirmation should explain:

```text
Approve cancellation?

This action may:
• cancel the order
• release/restore inventory
• require refund processing
```

---

# 609. WIREFRAME — VOUCHERS

```text
VOUCHERS

                                      + CREATE VOUCHER

CODE          TYPE         VALUE       USAGE       STATUS

NEW19         Percentage   10%         24 / 100    Active
WELCOME50     Fixed        Rp50k       42 / 50     Active
DROP26        Percentage   15%         100 / 100   Expired
```

---

# 610. WIREFRAME — CREATE VOUCHER

```text
CREATE VOUCHER

CODE
[ NEW19 ]

NAME
[ New Customer Voucher ]

DISCOUNT TYPE
[ Percentage ▼ ]

VALUE
[ 10 ]

MAXIMUM DISCOUNT
[ 50000 ]

MINIMUM PURCHASE
[ 250000 ]

TOTAL USAGE LIMIT
[ 100 ]

PER CUSTOMER
[ 1 ]

START
[ Date / Time ]

END
[ Date / Time ]

STATUS
[ Active ]

[ SAVE VOUCHER ]
```

---

# 611. WIREFRAME — ADMIN USERS

```text
USERS

[ Search user... ]      Role ▼      Status ▼

                                      + ADD USER

────────────────────────────────────────────────────

NAME             EMAIL              ROLE        STATUS

Admin A          ...                Admin       Active
Cashier A        ...                Cashier     Active
Manager A        ...                Management  Active
```

Customer management may be separated from internal users if customer volume becomes large.

---

# 612. WIREFRAME — ACTIVITY LOG

```text
ACTIVITY LOG

[ User ]    [ Action ]    [ Date ]

────────────────────────────────────────────────────────

12 Sep  02:10
Admin A
Adjusted stock
19H-TEE-BLK-M
14 → 20

────────────────────────────────────────────────────────

12 Sep  01:55
Admin A
Updated product price
Essential Tee
199000 → 219000
```

---

# 613. WIREFRAME — SETTINGS

```text
SETTINGS

STORE
COMMERCE
INVENTORY
HOMEPAGE

────────────────────────────────────────

STORE INFORMATION

Store Name
[ 19HOUSE ]

Email
[________________]

Phone
[________________]

Address
[________________]

────────────────────────────────────────

COMMERCE

Currency
[ IDR ]

Payment Expiration
[ 24 hours ]

────────────────────────────────────────

[ SAVE CHANGES ]
```

---

# 614. CASHIER APPLICATION SHELL

POS memiliki shell lebih sederhana daripada Admin.

```text
┌──────────────────────────────────────────────────────────────┐
│ 19HOUSE POS          TRANSACTIONS               CASHIER ▼   │
├───────────────────────────────────────┬──────────────────────┤
│                                       │                      │
│ PRODUCT SEARCH                        │ CURRENT ORDER        │
│                                       │                      │
│ PRODUCT GRID                          │ CART                 │
│                                       │                      │
│                                       │ SUMMARY              │
│                                       │                      │
│                                       │ PAYMENT              │
└───────────────────────────────────────┴──────────────────────┘
```

---

# 615. WIREFRAME — POS MAIN SCREEN

```text
┌────────────────────────────────────────┬───────────────────────────┐
│ Search product / SKU...                │ CURRENT ORDER             │
│                                        │                           │
│ ALL  T-SHIRTS  PANTS  OUTERWEAR        │ Essential Tee             │
│                                        │ Black / M                 │
│ ┌──────────┐ ┌──────────┐ ┌──────────┐ │ Rp199.000                 │
│ │ PRODUCT  │ │ PRODUCT  │ │ PRODUCT  │ │ −  1  +                   │
│ │ IMAGE    │ │ IMAGE    │ │ IMAGE    │ │                           │
│ └──────────┘ └──────────┘ └──────────┘ │ Essential Pants           │
│ Tee          Pants        Hoodie       │ Black / L                 │
│ Rp199k       Rp299k       Rp399k       │ Rp299.000                 │
│                                        │ −  1  +                   │
│ ┌──────────┐ ┌──────────┐ ┌──────────┐ │                           │
│ │ PRODUCT  │ │ PRODUCT  │ │ PRODUCT  │ ├───────────────────────────┤
│ └──────────┘ └──────────┘ └──────────┘ │ SUBTOTAL       Rp498.000 │
│                                        │ DISCOUNT               - │
│                                        │                           │
│                                        │ TOTAL          Rp498.000 │
│                                        │                           │
│                                        │ [ PAYMENT — Rp498.000 ]  │
└────────────────────────────────────────┴───────────────────────────┘
```

---

# 616. POS PRODUCT SELECTION

Click product:

```text
┌──────────────────────────────────────┐
│ ESSENTIAL OVERSIZED TEE          ×  │
│                                      │
│ [ Product Image ]                    │
│                                      │
│ COLOR                                │
│                                      │
│ [ BLACK ] [ WHITE ]                  │
│                                      │
│ SIZE                                 │
│                                      │
│ [ S ] [ M ] [ L ] [ XL ]            │
│                                      │
│ Black / M                            │
│ Available: 15                        │
│                                      │
│ [       ADD TO ORDER       ]         │
└──────────────────────────────────────┘
```

---

# 617. POS PAYMENT — CASH

```text
PAYMENT

TOTAL
Rp498.000

METHOD

[ CASH ]   QRIS   OTHER

CASH RECEIVED
[ 500000 ]

CHANGE
Rp2.000

[ COMPLETE PAYMENT ]
```

---

# 618. POS PAYMENT — QRIS

```text
PAYMENT

TOTAL
Rp498.000

METHOD

CASH   [ QRIS ]   OTHER

┌────────────────────────┐
│                        │
│        QR CODE         │
│                        │
└────────────────────────┘

Waiting for payment...

[ CANCEL ]
```

After provider confirmation:

```text
PAYMENT SUCCESSFUL

Rp498.000

[ PRINT RECEIPT ]

[ NEW TRANSACTION ]
```

---

# 619. POS RECEIPT

```text
19HOUSE

Transaction
POS-20260912-00021

12 Sep 2026 • 14:20
Cashier: Cashier A

────────────────────────

Essential Tee
Black / M
1 × Rp199.000

Essential Pants
Black / L
1 × Rp299.000

────────────────────────

Subtotal       Rp498.000
Total          Rp498.000

Cash           Rp500.000
Change           Rp2.000

────────────────────────

THANK YOU
```

---

# 620. POS TRANSACTION HISTORY

```text
TRANSACTIONS

[ Search transaction ]       Date ▼

────────────────────────────────────────────────────

TRANSACTION       TIME       CASHIER      TOTAL

POS-00021         14:20      Cashier A    Rp498k
POS-00020         14:02      Cashier A    Rp199k
POS-00019         13:55      Cashier B    Rp729k
```

---

# 621. POS TABLET LAYOUT

Tablet landscape:

```text
┌──────────────────────────┬──────────────────┐
│ PRODUCT GRID             │ CURRENT ORDER    │
│                          │                  │
│ 2–3 columns              │                  │
│                          │                  │
│                          │ PAYMENT          │
└──────────────────────────┴──────────────────┘
```

POS harus dioptimalkan minimal untuk:

```text
Desktop
Tablet Landscape
```

Smartphone bukan primary POS target.

---

# 622. MANAGEMENT APPLICATION SHELL

```text
┌───────────────────┬──────────────────────────────────────────┐
│ 19HOUSE           │ BUSINESS OVERVIEW       PROFILE ▼       │
│                   ├──────────────────────────────────────────┤
│ Overview          │                                          │
│ Sales             │                                          │
│ Products          │                                          │
│ Inventory         │             ANALYTICS                    │
│ Customers         │                                          │
│ Reports           │                                          │
│                   │                                          │
└───────────────────┴──────────────────────────────────────────┘
```

Read-only by default.

---

# 623. WIREFRAME — MANAGEMENT DASHBOARD

```text
BUSINESS OVERVIEW

1 SEP — 30 SEP 2026 ▼             ALL CHANNELS ▼

──────────────────────────────────────────────────────────────

REVENUE                 ORDERS                  AOV

Rp124.800.000            482                    Rp258.921
+8.4%                    +12.1%                 -1.2%

──────────────────────────────────────────────────────────────

SALES TREND

┌────────────────────────────────────────────────────────────┐
│                                                            │
│                     LINE CHART                             │
│                                                            │
└────────────────────────────────────────────────────────────┘

──────────────────────────────────────────────────────────────

CHANNEL PERFORMANCE

ONLINE                                   POS

Rp78.000.000                             Rp46.800.000
62.5%                                    37.5%

──────────────────────────────────────────────────────────────

BEST SELLERS

1  Essential Tee Black
2  Essential Pants
3  Hoodie 01

──────────────────────────────────────────────────────────────

INVENTORY

Low Stock        8 SKU
Out of Stock     3 SKU

VIEW INVENTORY REPORT →
```

---

# 624. MANAGEMENT SALES REPORT

```text
SALES REPORT

DATE RANGE ▼       CHANNEL ▼

────────────────────────────────────────────

TOTAL REVENUE
Rp124.800.000

ONLINE
Rp78.000.000

POS
Rp46.800.000

────────────────────────────────────────────

                    SALES TREND

────────────────────────────────────────────

DATE       ONLINE       POS        TOTAL

1 Sep      Rp2.1m       Rp1.2m     Rp3.3m
2 Sep      Rp2.8m       Rp900k     Rp3.7m
...
```

---

# 625. MANAGEMENT PRODUCT PERFORMANCE

```text
PRODUCT PERFORMANCE

Date ▼

────────────────────────────────────────────────────────────

PRODUCT              SOLD      REVENUE       STOCK

Essential Tee        182       Rp36.2m       42
Essential Pants      124       Rp37.0m       31
Hoodie 01             74       Rp29.5m       12
```

Click product dapat membuka analytics detail, bukan product editor.

---

# 626. MANAGEMENT INVENTORY

```text
INVENTORY OVERVIEW

TOTAL SKU        LOW STOCK       OUT OF STOCK

148              8               3

────────────────────────────────────────────────────

SKU               PRODUCT           AVAILABLE    STATUS

19H-TEE-BLK-M     Essential Tee     2            LOW
19H-HOOD-BLK-L    Hoodie            0            OUT
```

Tidak ada:

```text
ADJUST STOCK
STOCK IN
```

karena Management read-only.

---

# 627. MANAGEMENT CUSTOMER OVERVIEW

```text
CUSTOMERS

TOTAL CUSTOMERS
2,418

NEW CUSTOMERS
+182

RETURNING CUSTOMERS
31%

────────────────────────────────────────

CUSTOMER TREND

[ CHART ]

────────────────────────────────────────

TOP CUSTOMERS

Customer A       12 Orders      Rp4.2m
Customer B        9 Orders      Rp3.8m
```

Data harus mengikuti kebijakan privacy yang diperlukan.

---

# 628. WIREFRAME — NOTIFICATION DROPDOWN INTERNAL

```text
┌─────────────────────────────────────┐
│ NOTIFICATIONS                       │
├─────────────────────────────────────┤
│ ● Low stock                         │
│   Essential Tee Black / M           │
│   10m                               │
│                                     │
│ ● New paid order                    │
│   #19H-00021                        │
│   18m                               │
│                                     │
│   Cancellation request              │
│   #19H-00018                        │
│   1h                                │
├─────────────────────────────────────┤
│ VIEW ALL                            │
└─────────────────────────────────────┘
```

Content berbeda berdasarkan role.

---

# 629. PROFILE DROPDOWN INTERNAL

```text
┌──────────────────────────────┐
│ Admin Name                   │
│ admin@19house...             │
├──────────────────────────────┤
│ PROFILE                      │
│ ACCOUNT                      │
├──────────────────────────────┤
│ LOGOUT                       │
└──────────────────────────────┘
```

Tidak perlu menampilkan nama dan jabatan secara permanen pada sidebar.

---

# 630. RESPONSIVE ADMIN SHELL

Desktop:

```text
Sidebar + Workspace
```

Tablet:

```text
Collapsible Sidebar + Workspace
```

Mobile:

```text
Topbar
+
Drawer Navigation
+
Single Column Content
```

Admin tables dapat menggunakan:

```text
Horizontal scroll
```

jika kolom memang tidak dapat dikurangi dengan aman.

---

# 631. RESPONSIVE MANAGEMENT

Management dashboard mobile:

```text
REVENUE
Rp...

ORDERS
...

AOV
...

↓

SALES CHART

↓

CHANNEL PERFORMANCE

↓

BEST SELLERS
```

KPI berubah dari horizontal menjadi vertical/2-column.

---

# 632. RESPONSIVE POS

POS tidak disederhanakan menjadi tiny two-column pada smartphone.

Jika mobile support diperlukan:

```text
PRODUCTS
↓
POS CART
↓
PAYMENT
```

Tetapi target utama tetap tablet landscape/desktop.

---

# 633. WIREFRAME — GLOBAL ERROR PAGE

404:

```text
19HOUSE

404

PAGE NOT FOUND

The page you're looking for
doesn't exist.

[ BACK TO SHOP ]
```

---

# 634. WIREFRAME — SERVER ERROR

```text
19HOUSE

SOMETHING WENT WRONG

Please try again.

[ TRY AGAIN ]

BACK TO HOME →
```

Jangan menampilkan exception Laravel pada production.

---

# 635. WIREFRAME — MAINTENANCE

```text
19HOUSE

WE'LL BE BACK SOON.

19HOUSE is currently being updated.

Instagram →
```

Minimal dan tetap sesuai branding.

---

# 636. WIREFRAME — SOLD OUT PRODUCT

Product card:

```text
┌──────────────────────────┐
│                          │
│       PRODUCT IMAGE      │
│                          │
│ SOLD OUT                 │
└──────────────────────────┘

Essential Tee
Rp199.000
```

Product Detail:

```text
SIZE

[S̶] [M̶] [L̶] [XL̶]

[      SOLD OUT       ]
```

Button disabled.

---

# 637. WIREFRAME — LOW STOCK

Jangan membuat warning besar.

Example:

```text
SIZE

[S] [M] [L]

Only 2 left
```

Small supporting text cukup.

---

# 638. WIREFRAME — FILTER ACTIVE STATE

```text
SHOP

FILTER (3)                        SORT: NEWEST

Black ×
Size M ×
T-Shirts ×

CLEAR ALL
```

Filter aktif harus mudah dihapus.

---

# 639. WIREFRAME — NO SEARCH RESULTS

```text
SEARCH

"yellow hoodie"

NO RESULTS

We couldn't find anything for
"yellow hoodie".

CLEAR SEARCH

EXPLORE NEW ARRIVALS →
```

---

# 640. WIREFRAME — ORDER EMPTY STATE

```text
ORDERS

NO ORDERS YET

Your orders will appear here
after your first purchase.

[ EXPLORE COLLECTION ]
```

---

# 641. WIREFRAME — WISHLIST EMPTY STATE

```text
WISHLIST

YOUR WISHLIST IS EMPTY

Save pieces you want to return to.

[ EXPLORE COLLECTION ]
```

---

# 642. WIREFRAME — NOTIFICATION EMPTY STATE

```text
NOTIFICATIONS

YOU'RE ALL CAUGHT UP.

New order and payment updates
will appear here.
```

---

# 643. WIREFRAME — ADMIN EMPTY TABLE

```text
PRODUCTS

NO PRODUCTS YET

Create the first product
for the 19HOUSE catalog.

[ + ADD PRODUCT ]
```

CTA hanya ditampilkan jika user memiliki permission.

---

# 644. WIREFRAME — ADMIN MOBILE PRODUCT LIST

Daripada memaksa seluruh desktop table:

```text
PRODUCTS

[ Search... ]

────────────────────────────

[IMG]
Essential Tee

T-Shirts
Rp199.000
ACTIVE

6 SKU

VIEW →

────────────────────────────

[IMG]
Essential Pants

Bottoms
Rp299.000
ACTIVE

8 SKU

VIEW →
```

Responsive representation dapat berubah dari table menjadi list/card ringan.

---

# 645. PAGE WIDTH STRATEGY

Storefront:

```text
Editorial Sections
→ Full Width

Product Grid
→ Wide Container

Forms
→ Narrow Container

Account
→ Medium/Wide Container
```

Internal application:

```text
Workspace
→ Fluid Width
```

dengan reasonable max-width untuk form.

---

# 646. WIREFRAME SPACING PRINCIPLE

Storefront:

```text
Section
        ↓
        ↓
        ↓
Next Section
```

Gunakan large vertical rhythm.

Dashboard:

```text
Section
   ↓
Next Section
```

lebih compact.

Dengan demikian visual identity tetap sama tetapi usability sesuai context.

---

# 647. NAVIGATION PRIORITY — CUSTOMER

Primary:

```text
SHOP
NEW
COLLECTIONS
```

Utility:

```text
SEARCH
WISHLIST
ACCOUNT
BAG
```

Secondary/footer:

```text
ABOUT
SHIPPING
RETURNS
CONTACT
SOCIAL
```

---

# 648. NAVIGATION PRIORITY — ADMIN

```text
Dashboard
Orders
Products
Inventory
Vouchers
Reports
Users
Settings
```

Operational frequency menjadi dasar ordering.

---

# 649. NAVIGATION PRIORITY — CASHIER

```text
POS
Transactions
Profile
Logout
```

Cashier tidak membutuhkan sidebar kompleks.

---

# 650. NAVIGATION PRIORITY — MANAGEMENT

```text
Overview
Sales
Products
Inventory
Customers
Reports
```

Read-only analytics oriented.

---

# 651. CRITICAL CUSTOMER CTA HIERARCHY

Product Detail:

```text
PRIMARY
ADD TO CART

SECONDARY
SIZE GUIDE

TERTIARY
Product information
```

Cart:

```text
PRIMARY
CHECKOUT

SECONDARY
CONTINUE SHOPPING
```

Checkout:

```text
PRIMARY
PLACE ORDER
```

Order:

```text
PRIMARY
TRACK PACKAGE
```

jika sudah shipped.

---

# 652. CRITICAL ADMIN CTA HIERARCHY

Product List:

```text
PRIMARY
ADD PRODUCT
```

Product Editor:

```text
PRIMARY
PUBLISH / SAVE

SECONDARY
SAVE DRAFT
```

Order Detail:

```text
PRIMARY
NEXT VALID STATUS
```

Stock:

```text
PRIMARY
STOCK IN / ADJUST
```

tergantung context.

---

# 653. CRITICAL POS CTA HIERARCHY

```text
Select Product
↓
Select SKU
↓
Add to Order
↓
PAYMENT
↓
COMPLETE PAYMENT
↓
PRINT / NEW TRANSACTION
```

Tidak boleh ada CTA lain yang secara visual mengalahkan PAYMENT.

---

# 654. WIREFRAME INTERACTION MAP

```text
HOME
 │
 ├── Search
 │
 ├── Collection
 │
 └── Product
       │
       ▼
 PRODUCT DETAIL
       │
       ├── Variant
       ├── Size
       └── Add to Cart
              │
              ▼
         CART DRAWER
              │
              ├── Continue Shopping
              │
              └── Checkout
                     │
                     ▼
                   LOGIN
                     │
                     ▼
                 CHECKOUT
                     │
                     ▼
                  PAYMENT
                     │
                     ▼
                ORDER DETAIL
```

---

# 655. CUSTOMER ACCOUNT INTERACTION MAP

```text
ACCOUNT
 │
 ├── Orders
 │     └── Order Detail
 │           ├── Payment
 │           ├── Tracking
 │           └── Cancellation
 │
 ├── Wishlist
 │     └── Product Detail
 │
 ├── Addresses
 │     ├── Create
 │     └── Edit
 │
 ├── Notifications
 │
 └── Profile
```

---

# 656. ADMIN INTERACTION MAP

```text
ADMIN DASHBOARD
 │
 ├── Orders
 │     └── Order Detail
 │           ├── Process
 │           ├── Ship
 │           └── Cancellation
 │
 ├── Products
 │     ├── Create
 │     └── Edit
 │           ├── Variant
 │           ├── Images
 │           ├── SKU
 │           └── Size
 │
 ├── Inventory
 │     ├── Stock
 │     ├── Stock In
 │     ├── Adjustment
 │     └── Movements
 │
 ├── Vouchers
 │
 ├── Reports
 │
 ├── Users
 │
 └── Settings
```

---

# 657. POS INTERACTION MAP

```text
POS
 │
 ▼
SEARCH PRODUCT
 │
 ▼
SELECT PRODUCT
 │
 ▼
VARIANT
 │
 ▼
SIZE / SKU
 │
 ▼
POS CART
 │
 ▼
PAYMENT
 │
 ├── Cash
 ├── QRIS
 └── Other
 │
 ▼
TRANSACTION SUCCESS
 │
 ├── Receipt
 └── New Transaction
```

---

# 658. MANAGEMENT INTERACTION MAP

```text
MANAGEMENT
 │
 ├── Overview
 ├── Sales
 ├── Product Performance
 ├── Inventory
 ├── Customers
 └── Reports
       │
       └── Export
```

Tidak ada mutation flow secara default.

---

# 659. WIREFRAME RESPONSIVE MATRIX

| Screen         | Mobile             | Tablet      | Desktop               |
| -------------- | ------------------ | ----------- | --------------------- |
| Home           | ✓                  | ✓           | ✓                     |
| Collection     | 2 col              | 2–3 col     | 3–4 col               |
| Product Detail | Stack              | Stack/Split | Gallery + Sticky Info |
| Cart           | Full screen/drawer | Drawer      | Drawer/Page           |
| Checkout       | Stack              | Stack/Split | 2 columns             |
| Account        | List nav           | Adaptive    | Side navigation       |
| Admin          | Drawer nav         | Collapsible | Sidebar               |
| POS            | Secondary          | Primary     | Primary               |
| Management     | Stack              | Adaptive    | Dashboard grid        |

---

# 660. WIREFRAME BREAKPOINT BEHAVIOR

Wireframe tidak hanya "mengecil".

Layout harus berubah.

Example:

```text
DESKTOP PRODUCT DETAIL

Gallery │ Product Info
```

menjadi:

```text
MOBILE

Gallery
   ↓
Product Info
```

Desktop checkout:

```text
Form │ Summary
```

Mobile:

```text
Form
 ↓
Summary
```

---

# 661. WIREFRAME PHOTOGRAPHY RULE

Pada storefront wireframe, setiap block bertanda:

```text
PRODUCT IMAGE
EDITORIAL IMAGE
HERO IMAGE
```

harus dianggap sebagai **primary visual element**, bukan placeholder kecil yang nantinya dikelilingi card decoration.

Final UI harus mempertahankan dominasi photography tersebut.

---

# 662. WIREFRAME BORDER RULE

Garis pada ASCII wireframe:

```text
┌───────────────┐
│               │
└───────────────┘
```

menunjukkan **layout boundary**, bukan berarti setiap elemen final harus mempunyai border.

Sebagian besar boundary pada storefront harus hilang pada final UI.

---

# 663. WIREFRAME CARD RULE

Block seperti:

```text
┌──────────────┐
│ PRODUCT      │
└──────────────┘
```

bukan instruction untuk membuat:

```text
rounded-xl
shadow-lg
border
background-white
```

Product card final tetap:

```text
Photography
```
