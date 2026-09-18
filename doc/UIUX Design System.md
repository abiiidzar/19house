# 390. UI/UX DESIGN SYSTEM & GUIDELINES

UI/UX 19HOUSE menggunakan visual direction:

**Minimal Fashion Editorial Commerce**

Referensi utama berasal dari video visual yang telah ditentukan untuk project 19HOUSE.

Karakter utamanya:

```text
FASHION EDITORIAL
+
MINIMAL
+
PRODUCT PHOTOGRAPHY FIRST
+
LARGE WHITESPACE
+
CLEAN TYPOGRAPHY
+
SUBTLE INTERACTION
+
MOBILE FIRST
```

Tujuan design system bukan membuat website terlihat penuh dengan komponen UI.

Sebaliknya, interface harus menjadi **frame bagi produk dan photography 19HOUSE**.

Prinsip utamanya:

> Product is the visual focus. Interface stays quiet.

---

# 391. VISUAL DIRECTION

Visual direction 19HOUSE:

```text
Minimal
Editorial
Modern
Clean
Quiet
Confident
Image-focused
Fashion-oriented
Premium without looking luxurious
```

19HOUSE **tidak** diarahkan menjadi interface marketplace seperti:

```text
Shopee
Tokopedia
Marketplace-heavy UI
```

yang biasanya mempunyai:

* banyak badge;
* banyak warna;
* banyak promo;
* card bertumpuk;
* CTA berlebihan;
* banner yang ramai;
* informasi sangat padat.

19HOUSE harus lebih dekat dengan pengalaman digital sebuah **fashion label**.

---

# 392. DESIGN PHILOSOPHY

Design philosophy:

```text
LESS UI
MORE PRODUCT
```

User harus lebih banyak melihat:

```text
Product
Photography
Collection
Editorial Content
```

daripada:

```text
Container
Border
Shadow
Badge
Decoration
```

---

# 393. CORE DESIGN PRINCIPLES

## 393.1 Product First

Produk harus menjadi elemen paling dominan.

Urutan visual:

```text
1. Product Image
2. Product Name
3. Price
4. Variant / Size
5. Supporting Information
6. UI Decoration
```

UI decoration berada pada prioritas terakhir.

---

## 393.2 Photography First

Photography merupakan komponen visual terbesar.

Product card sebaiknya didominasi:

```text
70–90%
Product Image
```

dan hanya sedikit informasi di bawahnya.

---

## 393.3 Whitespace Is Part of the Design

Whitespace bukan ruang kosong yang harus diisi.

Whitespace digunakan untuk:

* memberikan fokus;
* membangun kesan editorial;
* memisahkan section;
* meningkatkan readability;
* memberi visual rhythm;
* membuat photography lebih dominan.

---

## 393.4 Minimal but Not Empty

Minimal tidak berarti kehilangan informasi.

Contoh Product Detail tetap harus mempunyai:

```text
Product Name
Price
Color
Size
Availability
Size Guide
Add to Cart
Description
```

tetapi disusun secara tenang tanpa terlalu banyak visual container.

---

## 393.5 Content Before Decoration

Jika suatu elemen tidak membantu:

* navigation;
* comprehension;
* action;
* branding;

elemen tersebut sebaiknya tidak ditambahkan.

---

# 394. VISUAL LANGUAGE BY APPLICATION AREA

19HOUSE memiliki dua bahasa UI yang masih berada dalam satu sistem.

## Storefront

```text
Editorial
Minimal
Photography driven
Large whitespace
Low visual density
```

## Internal Application

Admin, Cashier, dan Management:

```text
Clean
Functional
Information driven
Higher density
Structured
```

Internal dashboard tidak perlu dipaksakan menjadi editorial seperti storefront.

Namun tetap menggunakan typography, spacing, neutral palette, dan interaction language yang konsisten.

---

# 395. BRAND COLOR DIRECTION

Visual reference menggunakan pendekatan warna yang sangat netral.

Recommended core palette:

```text
Background        #F7F7F5
Surface           #FFFFFF

Primary Text      #111111
Secondary Text    #6D6D6D
Muted Text        #999999

Border            #E5E5E2
Border Strong     #CFCFCC

Dark Surface      #111111
Dark Text         #FFFFFF
```

Background dapat menggunakan:

```text
#FFFFFF
```

atau warm off-white:

```text
#F7F7F5
```

tergantung final brand direction.

Rekomendasi 19HOUSE:

```text
Primary Background
#F7F7F5

Product / Card Surface
#FFFFFF

Primary Black
#111111
```

Off-white memberikan sedikit karakter fashion editorial dibanding pure white pada seluruh interface.

---

# 396. ACCENT COLOR PRINCIPLE

19HOUSE tidak membutuhkan accent color terang sebagai elemen utama.

Brand emphasis datang dari:

```text
Photography
Typography
Composition
Whitespace
```

bukan:

```text
Bright buttons
Gradient
Neon accent
```

Jika suatu saat brand mempunyai seasonal color, warna tersebut dapat digunakan pada:

* campaign;
* collection;
* editorial photography;
* limited visual accent.

Bukan sebagai global UI color.

---

# 397. SEMANTIC COLORS

Functional colors tetap diperlukan.

## Success

```text
#2F6B45
```

Digunakan untuk:

```text
Payment successful
Order completed
Stock available
```

## Warning

```text
#9A6A22
```

Digunakan untuk:

```text
Low stock
Pending payment
```

## Error

```text
#A63D40
```

Digunakan untuk:

```text
Validation error
Payment failed
Destructive action
```

## Info

```text
#4B617A
```

Digunakan untuk system information jika diperlukan.

Semantic colors tidak boleh mendominasi normal storefront.

---

# 398. COLOR USAGE RATIO

Rekomendasi visual:

```text
80–90% Neutral Surface
10–15% Typography / Dark Elements
<5% Semantic / Accent
```

Tujuannya mempertahankan visual minimal.

---

# 399. DARK SECTION

Dark background dapat digunakan secara terbatas untuk:

* footer;
* campaign section;
* special editorial section;
* occasional inverted block.

Contoh:

```text
Background
#111111

Text
#F7F7F5
```

Jangan membuat terlalu banyak section bergantian:

```text
White
Black
White
Black
White
```

karena akan mengurangi kesan editorial yang tenang.

---

# 400. TYPOGRAPHY DIRECTION

Typography harus:

```text
Neutral
Modern
Readable
Simple
Fashion Editorial
```

Hindari font yang:

* terlalu dekoratif;
* terlalu rounded;
* terlalu playful;
* terlalu futuristic.

---

# 401. PRIMARY TYPEFACE

Recommended starting point:

```text
Inter
```

karena:

* sangat readable;
* cocok untuk web;
* clean;
* mendukung dashboard;
* memiliki banyak weight;
* mudah diintegrasikan dengan Tailwind.

Alternative final branding dapat menggunakan:

```text
Helvetica-like grotesk
Neue Haas-style sans
General Sans
Manrope
Satoshi
DM Sans
```

jika lisensi dan asset tersedia.

Untuk implementation awal:

**Inter menjadi base font 19HOUSE.**

---

# 402. EDITORIAL TYPOGRAPHY

Agar tidak terasa seperti SaaS website, heading dapat menggunakan typography treatment yang lebih editorial tanpa perlu font dekoratif.

Contoh:

```text
NEW COLLECTION
SUMMER 26
ESSENTIAL FORM
```

menggunakan:

* uppercase;
* regular/medium weight;
* tracking;
* large scale;
* whitespace.

Editorial feel berasal dari composition, bukan font aneh.

---

# 403. TYPOGRAPHY WEIGHTS

Recommended:

```text
Regular     400
Medium      500
Semibold    600
```

Hindari terlalu banyak:

```text
700
800
900
```

pada storefront.

Bold hanya digunakan ketika benar-benar diperlukan.

Fashion editorial UI umumnya terlihat lebih refined dengan regular/medium typography.

---

# 404. STOREFRONT TYPE SCALE

Suggested desktop baseline:

```text
Display XL
64–88px

Display
48–64px

H1
40–48px

H2
30–36px

H3
24–28px

Body Large
18px

Body
16px

Body Small
14px

Label
12–13px

Micro
11–12px
```

Ini bukan ukuran mutlak.

Final implementation harus menggunakan responsive typography.

---

# 405. MOBILE TYPE SCALE

Recommended:

```text
Display
38–48px

H1
32–40px

H2
26–32px

H3
20–24px

Body
15–16px

Small
13–14px

Label
11–12px
```

Heading besar harus tetap proporsional pada mobile.

---

# 406. RESPONSIVE TYPOGRAPHY

Gunakan responsive scale seperti:

```text
Mobile
36px

Tablet
48px

Desktop
64px
```

atau CSS:

```text
clamp()
```

untuk heading besar.

Contoh konsep:

```text
font-size:
clamp(2.25rem, 5vw, 5rem)
```

---

# 407. LINE HEIGHT

Recommended:

```text
Display
0.95–1.1

Heading
1.1–1.25

Body
1.5–1.7

Label
1.2–1.4
```

Heading editorial boleh lebih compact.

Body text harus lebih longgar.

---

# 408. LETTER SPACING

Uppercase navigation dapat menggunakan:

```text
0.04em – 0.12em
```

Contoh:

```text
SHOP
COLLECTIONS
NEW ARRIVALS
```

Large display text:

```text
-0.02em
```

atau neutral.

Jangan gunakan excessive letter spacing pada seluruh text.

---

# 409. TEXT CASE RULE

Recommended:

### Navigation

```text
UPPERCASE
```

atau title case minimal.

### Product Name

```text
Title Case
```

atau brand naming asli.

### Button

```text
UPPERCASE
```

dapat digunakan untuk memberikan fashion-label character.

### Body Text

Sentence case.

---

# 410. SPACING SYSTEM

Gunakan sistem 4px atau 8px.

Recommended base:

```text
4px
```

Token:

```text
4
8
12
16
20
24
32
40
48
64
80
96
120
160
```

---

# 411. WHITESPACE SCALE

Storefront tidak harus takut menggunakan spacing besar.

Desktop section gap:

```text
96–160px
```

Mobile:

```text
64–96px
```

Product information internal gap:

```text
12–32px
```

---

# 412. PAGE CONTAINER

Untuk text/content section:

```text
max-width:
1280–1440px
```

Tetapi photography section boleh:

```text
full width
```

atau hampir full viewport.

Desktop horizontal padding:

```text
32–64px
```

Large desktop:

```text
64–96px
```

Tablet:

```text
24–32px
```

Mobile:

```text
16–20px
```

---

# 413. GRID SYSTEM

Desktop:

```text
12-column grid
```

Tablet:

```text
8-column grid
```

Mobile:

```text
4-column grid
```

Untuk product listing, tidak harus menunjukkan struktur grid secara visual.

---

# 414. PRODUCT GRID

Recommended:

### Mobile

```text
2 columns
```

### Tablet

```text
2–3 columns
```

### Desktop

```text
3–4 columns
```

Large editorial collection dapat menggunakan:

```text
2-column large image grid
```

untuk section tertentu.

---

# 415. PRODUCT GRID GAP

Mobile:

```text
8–16px horizontal
24–32px vertical
```

Desktop:

```text
16–32px horizontal
40–56px vertical
```

Product image lebih penting daripada gap dekoratif.

---

# 416. EDITORIAL GRID VARIATION

Tidak semua halaman perlu memiliki grid yang terlalu uniform.

Home dapat menggunakan variation:

```text
Large image
+
Two small products
+
Full-width campaign
+
Four-column products
```

Contoh:

```text
┌──────────────────────┐
│                      │
│    LARGE CAMPAIGN    │
│                      │
└──────────────────────┘

┌──────────┐ ┌──────────┐
│ Product  │ │ Product  │
│          │ │          │
└──────────┘ └──────────┘
```

Ini memberikan karakter editorial.

---

# 417. PRODUCT IMAGE RATIO

Konsistensi photography sangat penting.

Recommended product catalog:

```text
3:4
```

Contoh:

```text
1200 × 1600
```

atau:

```text
1500 × 2000
```

Alternative:

```text
4:5
```

juga cocok untuk fashion.

Rekomendasi utama:

**3:4 portrait untuk product catalog.**

---

# 418. EDITORIAL IMAGE RATIOS

Campaign dapat menggunakan:

```text
16:9
3:2
4:5
3:4
1:1
```

namun harus digunakan secara sengaja.

Jangan membuat seluruh gambar memiliki ratio random.

---

# 419. PRODUCT PHOTOGRAPHY GUIDELINE

Photography 19HOUSE harus konsisten dalam:

* background;
* lighting;
* crop;
* model positioning;
* image ratio;
* color treatment;
* contrast;
* editing;
* white balance.

---

# 420. PRODUCT PHOTO TYPES

Idealnya variant mempunyai:

```text
01 HERO / FRONT
02 BACK
03 SIDE
04 DETAIL
05 MODEL / LIFESTYLE
```

Tidak semua wajib lima foto, tetapi urutan photography sebaiknya predictable.

---

# 421. PRODUCT CARD IMAGE

Product card:

```text
Image
↓
Name
Price
```

Jangan terlalu banyak metadata.

Recommended:

```text
┌─────────────────────────┐
│                         │
│                         │
│      PRODUCT IMAGE      │
│                         │
│                         │
└─────────────────────────┘

Essential Oversized Tee
Rp199.000
```

---

# 422. PRODUCT CARD INFORMATION

Default:

```text
Product Name
Price
```

Optional:

```text
Available Colors
```

Tidak direkomendasikan menampilkan sekaligus:

```text
Category
Rating
Stock count
Discount %
SKU
Shipping
Multiple badges
```

karena merusak minimal presentation.

---

# 423. PRODUCT CARD BORDER

Default:

```text
No border
No shadow
```

Image menjadi boundary alami card.

Jika perlu:

```text
1px subtle border
```

hanya digunakan pada area tertentu.

---

# 424. PRODUCT CARD RADIUS

Reference direction lebih cocok dengan:

```text
0–4px
```

daripada rounded card besar.

Avoid:

```text
16px
24px
32px
```

untuk product photography utama jika ingin mempertahankan editorial aesthetic.

---

# 425. PRODUCT CARD HOVER

Desktop:

```text
Default image
       ↓ hover
Secondary image
```

atau subtle:

```text
Scale 1.00
↓
Scale 1.015
```

Duration:

```text
250–400ms
```

Tidak menggunakan scale berlebihan.

---

# 426. IMAGE HOVER PRINCIPLE

Recommended:

```text
Image transition
Opacity
Subtle scale
Secondary photo reveal
```

Avoid:

```text
3D rotation
Heavy zoom
Bright overlay
Large shadow
```

---

# 427. PRODUCT DETAIL LAYOUT

Desktop recommended:

```text
┌───────────────────────────────┬──────────────────────┐
│                               │                      │
│                               │ Product Name         │
│                               │ Price                │
│      PRODUCT GALLERY          │                      │
│                               │ Color                │
│                               │ Size                 │
│                               │                      │
│                               │ [ ADD TO CART ]      │
│                               │                      │
│                               │ Product Info         │
│                               │ Shipping             │
│                               │                      │
└───────────────────────────────┴──────────────────────┘
```

Gallery:

```text
60–70%
```

Information:

```text
30–40%
```

---

# 428. PRODUCT DETAIL GALLERY

Desktop dapat menggunakan:

```text
Vertical image stack
```

atau:

```text
2-column editorial gallery
```

Contoh:

```text
┌────────────┐ ┌────────────┐
│ Front      │ │ Back       │
│            │ │            │
└────────────┘ └────────────┘

┌────────────┐ ┌────────────┐
│ Detail     │ │ Model      │
│            │ │            │
└────────────┘ └────────────┘
```

Mobile:

```text
Swipe gallery
```

lebih praktis.

---

# 429. STICKY PRODUCT INFORMATION

Desktop product information dapat menggunakan:

```text
position: sticky
```

sehingga informasi:

```text
Name
Price
Variant
Size
Add to Cart
```

tetap visible saat user melihat gallery panjang.

---

# 430. COLOR VARIANT SELECTOR

Variant color dapat menggunakan:

```text
Color Name
+
Small Swatch
```

Contoh:

```text
COLOR — BLACK

●   ○   ○
```

Selected state harus jelas.

Swatch tidak boleh menjadi satu-satunya indikator karena accessibility.

---

# 431. SIZE SELECTOR

Recommended:

```text
SIZE

[S] [M] [L] [XL]
```

Style:

```text
Transparent
Thin Border
Minimal Radius
```

Selected:

```text
Black Background
White Text
```

Out of stock:

```text
Muted
Disabled
Strike / Visual Indicator
```

---

# 432. SIZE BUTTON DIMENSION

Minimum tap target:

```text
44 × 44px
```

terutama untuk mobile.

UI dapat terlihat minimal tetapi touch target tetap accessible.

---

# 433. QUANTITY SELECTOR

Recommended minimal:

```text
−   1   +
```

Tidak perlu container besar.

---

# 434. PRIMARY BUTTON

Primary CTA:

```text
Black Background
White Text
```

Example:

```text
ADD TO CART
CHECKOUT
PLACE ORDER
```

Height:

```text
48–56px
```

Radius:

```text
0–4px
```

Fashion editorial style lebih cocok dengan almost-square corners.

---

# 435. PRIMARY BUTTON BEHAVIOR

Default:

```text
#111111
```

Hover:

```text
slightly lighter
```

atau opacity.

Avoid:

```text
Heavy shadow
Gradient
Glow
Bounce
```

---

# 436. SECONDARY BUTTON

Recommended:

```text
White / Transparent
Black text
1px Border
```

Example:

```text
VIEW CART
CONTINUE SHOPPING
```

---

# 437. TERTIARY BUTTON

Text action:

```text
REMOVE
SIZE GUIDE
VIEW DETAILS
```

dapat berupa:

```text
Text
+
Underline on hover
```

---

# 438. DESTRUCTIVE BUTTON

Destructive actions:

```text
Cancel Order
Delete Address
Archive Product
```

tidak perlu bright red filled button secara default.

Gunakan red text atau subtle destructive treatment.

Confirmation tetap wajib jika action irreversible.

---

# 439. ICON STYLE

Gunakan icon:

```text
Outline
Simple
Consistent stroke
```

Contoh:

* search;
* account;
* heart;
* bag/cart;
* arrow;
* plus;
* minus;
* chevron.

Avoid:

```text
3D icons
Colored icons
Mixed icon styles
Emoji icons
```

---

# 440. ICON SIZE

Header:

```text
20–24px
```

Internal compact UI:

```text
16–20px
```

Stroke sebaiknya konsisten.

---

# 441. HEADER DESIGN

Desktop:

```text
┌────────────────────────────────────────────────────┐
│ 19HOUSE     SHOP   NEW   COLLECTIONS       ⌕ ♡ □ │
└────────────────────────────────────────────────────┘
```

Header should feel light.

Recommended:

```text
Height
64–80px

Background
transparent / off-white

Border bottom
none or subtle
```

---

# 442. HEADER POSITION

Home dapat menggunakan:

```text
absolute / overlay header
```

jika hero photography memungkinkan readability.

Setelah scroll:

```text
sticky header
```

dengan solid surface dapat digunakan.

Namun jangan menambahkan strong glassmorphism.

---

# 443. ANNOUNCEMENT BAR

Jika digunakan:

```text
FREE SHIPPING ...
```

buat sangat minimal.

Height:

```text
28–36px
```

Small typography.

Tidak menggunakan marquee cepat atau flashing promotion.

Announcement bar optional.

---

# 444. MOBILE HEADER

```text
┌───────────────────────────────┐
│ ☰       19HOUSE       ⌕   BAG│
└───────────────────────────────┘
```

Account dan wishlist dapat berada di mobile menu.

---

# 445. MOBILE NAVIGATION DRAWER

Recommended:

```text
SHOP
NEW ARRIVALS
COLLECTIONS
ABOUT

────────────

ACCOUNT
WISHLIST
```

Large menu typography dan whitespace.

Drawer sebaiknya terlihat editorial, bukan penuh dengan icon list.

---

# 446. FOOTER

Footer dapat menggunakan dark surface.

Example:

```text
┌─────────────────────────────────────────────┐
│                                             │
│ 19HOUSE                                     │
│                                             │
│ SHOP        HELP       SOCIAL               │
│                                             │
│ New         Shipping   Instagram            │
│ Collection  Returns    TikTok               │
│                                             │
│ © 19HOUSE                                   │
│                                             │
└─────────────────────────────────────────────┘
```

Large whitespace tetap dipertahankan.

---

# 447. FORM DESIGN

Form harus minimal.

Recommended:

```text
Label

Input
────────────────────────
```

atau subtle box:

```text
┌──────────────────────────────┐
│ Input                        │
└──────────────────────────────┘
```

Border:

```text
1px #D8D8D5
```

---

# 448. INPUT HEIGHT

Mobile-friendly:

```text
48–52px
```

Textarea menyesuaikan kebutuhan.

---

# 449. INPUT RADIUS

Recommended:

```text
0–4px
```

Tidak menggunakan highly rounded pill input kecuali Search tertentu.

---

# 450. INPUT FOCUS

Focus state:

```text
Border #111111
```

atau:

```text
2px accessible outline
```

Tanpa glow besar.

---

# 451. FORM LABEL

Label selalu visible.

Placeholder tidak boleh menjadi satu-satunya label.

Example:

```text
EMAIL ADDRESS
[                    ]
```

---

# 452. FORM ERROR

Error ditempatkan dekat field.

Example:

```text
EMAIL ADDRESS
[ invalid email      ]

Please enter a valid email address.
```

Gunakan semantic red secara terbatas.

---

# 453. CHECKOUT UI PRINCIPLE

Checkout harus lebih functional daripada Home tetapi masih minimal.

Primary objective:

```text
Complete Purchase
```

Kurangi distraction.

Checkout header dapat disederhanakan:

```text
19HOUSE
SECURE CHECKOUT
```

dan tidak memerlukan full navigation.

---

# 454. CHECKOUT DESKTOP

```text
┌────────────────────────────┬──────────────────────┐
│                            │                      │
│ Contact                    │ ORDER SUMMARY        │
│                            │                      │
│ Shipping Address           │ Product              │
│                            │ Product              │
│ Shipping                   │                      │
│                            │ Subtotal             │
│ Payment                    │ Shipping             │
│                            │ Discount             │
│                            │                      │
│                            │ TOTAL                │
│                            │                      │
│                            │ [ PLACE ORDER ]      │
└────────────────────────────┴──────────────────────┘
```

---

# 455. CART DRAWER

Cart drawer direction:

```text
Clean
Large images
Minimal text
No unnecessary decoration
```

Width desktop:

```text
380–480px
```

Mobile:

```text
Full width
```

---

# 456. MODAL VS DRAWER

Use Modal for:

```text
Confirmation
Small focused interaction
```

Use Drawer for:

```text
Cart
Filter
Mobile Navigation
Size Guide on mobile
```

Avoid stacking:

```text
Drawer inside modal inside popup
```

---

# 457. MODAL DESIGN

Modal:

```text
White / Off-white
Minimal border
Minimal radius
No heavy shadow
```

Overlay:

```text
rgba(0,0,0,0.25–0.4)
```

---

# 458. BORDER SYSTEM

Default:

```text
1px solid #E5E5E2
```

Strong:

```text
1px solid #B8B8B5
```

Borders digunakan untuk:

* inputs;
* tables;
* separators;
* selectors.

Bukan untuk setiap container.

---

# 459. SHADOW SYSTEM

Storefront:

```text
Almost no shadow
```

Internal dashboard dapat menggunakan subtle elevation apabila diperlukan.

Example:

```text
0 4px 20px rgba(0,0,0,0.04)
```

Namun default tetap flat.

---

# 460. BORDER RADIUS SYSTEM

Recommended:

```text
None        0px
Small       2px
Standard    4px
Medium      8px
```

Storefront utama:

```text
0–4px
```

Internal dashboard:

```text
4–8px
```

Avoid excessive:

```text
20–32px
```

untuk seluruh komponen.

---

# 461. BADGE DESIGN

Badge hanya digunakan jika memiliki makna.

Examples:

```text
NEW
SOLD OUT
LOW STOCK
```

Style:

```text
Small typography
Minimal background
No rounded bubble style
```

Example:

```text
NEW
```

langsung sebagai small text overlay.

---

# 462. SALE PRESENTATION

Jika ada sale:

```text
Rp299.000
Rp249.000
```

Old price dapat:

```text
line-through
```

Jangan menampilkan:

```text
🔥 50% SUPER DISCOUNT 🔥
```

karena tidak sesuai visual direction.

---

# 463. TABLE DESIGN — INTERNAL

Admin dan Management table:

```text
Minimal horizontal separators
No heavy card wrapper
Readable spacing
Sticky header if needed
```

Header:

```text
12–13px
Medium
Muted
```

Rows:

```text
14px
```

---

# 464. DASHBOARD CARD STYLE

Dashboard card lebih functional tetapi tetap minimal.

Recommended:

```text
White surface
1px border
4–8px radius
No or very subtle shadow
```

Metric example:

```text
Revenue

Rp24.800.000

+12.4% vs previous period
```

---

# 465. DASHBOARD VISUAL DENSITY

Admin:

```text
Medium density
```

Cashier:

```text
High usability
Large interaction targets
```

Management:

```text
Low–medium density
Large KPI numbers
Charts
```

---

# 466. POS UI DIRECTION

POS tidak harus mengikuti large-whitespace storefront secara ekstrem.

Prioritas:

```text
Speed
Clarity
Stock visibility
Fast product search
Large payment action
```

Style tetap:

```text
Neutral
Clean
Minimal
```

---

# 467. POS PRODUCT CARD

```text
┌──────────────┐
│ Product      │
│ Image        │
│              │
└──────────────┘
Name
Rp199.000
```

Stock/variant dapat muncul setelah product dipilih.

Jangan menampilkan terlalu banyak text pada grid.

---

# 468. POS PAYMENT BUTTON

Payment CTA harus jauh lebih jelas daripada secondary action.

Example:

```text
[ PAYMENT — Rp499.000 ]
```

Full width di current order panel.

---

# 469. MANAGEMENT CHART STYLE

Charts harus:

```text
Simple
Low decoration
Clear labels
Minimal grid line
```

Avoid:

* 3D chart;
* gradients;
* excessive pie charts;
* terlalu banyak warna.

Gunakan satu main neutral/brand dark series dan semantic differentiation bila diperlukan.

---

# 470. EMPTY STATE DESIGN

Empty states tetap minimal.

Example Wishlist:

```text
YOUR WISHLIST IS EMPTY

Save pieces you want to return to.

[ EXPLORE COLLECTION ]
```

Tidak wajib menggunakan large illustration.

---

# 471. LOADING STATE

Recommended:

```text
Skeleton
```

untuk:

* product grid;
* product detail;
* cart;
* dashboard.

Skeleton menggunakan neutral tone.

Avoid full-page spinner jika tidak diperlukan.

---

# 472. BUTTON LOADING

Example:

```text
ADD TO CART
```

menjadi:

```text
ADDING...
```

atau small spinner + label.

Button disabled selama request kritis.

---

# 473. SUCCESS FEEDBACK

Storefront:

```text
Added to cart
```

dapat menggunakan subtle toast atau cart drawer.

Internal:

```text
Product successfully updated.
```

menggunakan toast/banner.

Feedback harus terlihat tetapi tidak mengambil alih layar.

---

# 474. ERROR FEEDBACK

Error harus spesifik.

Bad:

```text
Something went wrong.
```

Better:

```text
Size M is no longer available.
Please select another size.
```

Technical errors tidak ditampilkan.

---

# 475. MICROINTERACTION PRINCIPLES

Motion 19HOUSE:

```text
Quiet
Fast
Natural
Purposeful
```

Motion digunakan untuk menjelaskan perubahan state, bukan hanya dekorasi.

---

# 476. DEFAULT MOTION DURATION

Recommended:

```text
Fast
120–180ms

Normal
200–300ms

Large transition
300–450ms
```

Lebih dari:

```text
500ms
```

jarang diperlukan.

---

# 477. EASING

Recommended:

```text
ease-out
```

untuk entering element.

```text
ease-in-out
```

untuk state transition.

Avoid elastic/bounce pada storefront.

---

# 478. HEADER MICROINTERACTION

Scroll:

```text
Transparent
↓
Solid background
```

Transition:

```text
200–300ms
```

Logo/menu tidak perlu animation kompleks.

---

# 479. PRODUCT HOVER MICROINTERACTION

Desktop:

```text
Image 1
↓ Hover
Image 2
```

Transition:

```text
opacity 300ms
```

atau subtle image scale.

---

# 480. VARIANT MICROINTERACTION

User memilih White:

```text
BLACK selected
      ↓
WHITE selected
      ↓
Gallery crossfade
```

Duration:

```text
200–300ms
```

Tidak perlu slide animation besar.

---

# 481. ADD TO CART MICROINTERACTION

```text
Add to Cart
↓
Button loading
↓
Success
↓
Cart count updates
↓
Cart drawer opens
```

Flow terasa cepat.

---

# 482. CART DRAWER MOTION

Desktop:

```text
Slide from right
```

Duration:

```text
250–350ms
```

Background overlay fade.

---

# 483. FILTER DRAWER MOTION

Mobile:

```text
Slide up / right
```

Gunakan satu pattern secara konsisten.

---

# 484. PAGE TRANSITION

Full custom page transition tidak wajib.

Jika diterapkan:

```text
subtle fade
```

maksimal.

Performance lebih penting daripada decorative transition.

---

# 485. SCROLL REVEAL

Home/editorial section dapat menggunakan subtle reveal:

```text
opacity 0 → 1
translateY 12–20px → 0
```

Durasi sekitar:

```text
400–600ms
```

hanya untuk selected content.

Jangan semua text dan card muncul satu per satu secara berlebihan.

---

# 486. REDUCED MOTION

Jika user/device menggunakan:

```text
prefers-reduced-motion
```

animation non-essential harus dikurangi atau dinonaktifkan.

---

# 487. MOBILE-FIRST PRINCIPLE

UI pertama kali dipastikan bekerja baik pada smartphone.

Order design priority:

```text
Mobile
↓
Tablet
↓
Desktop
```

Bukan membuat desktop dulu lalu mengecilkannya.

---

# 488. RECOMMENDED BREAKPOINTS

Mengikuti Tailwind baseline:

```text
sm   640px
md   768px
lg   1024px
xl   1280px
2xl  1536px
```

Tidak harus menggunakan setiap breakpoint.

Gunakan hanya jika layout membutuhkan.

---

# 489. MOBILE PRODUCT GRID

Default:

```text
2 columns
```

Untuk editorial campaign tertentu:

```text
1 large column
```

diperbolehkan.

---

# 490. MOBILE PRODUCT DETAIL

```text
PRODUCT IMAGES
      ↓
PRODUCT NAME
      ↓
PRICE
      ↓
VARIANT
      ↓
SIZE
      ↓
ADD TO CART
      ↓
DESCRIPTION
      ↓
DETAIL
```

Tidak menggunakan desktop 2-column yang dipaksa mengecil.

---

# 491. MOBILE STICKY CTA

Product Detail dapat menggunakan bottom sticky action secara hati-hati:

```text
Rp199.000
[ ADD TO CART ]
```

terutama setelah user memilih SKU.

Namun jangan menutupi terlalu banyak screen.

---

# 492. MOBILE CHECKOUT

Checkout menjadi vertical:

```text
Contact
↓
Address
↓
Shipping
↓
Payment
↓
Order Summary
↓
PLACE ORDER
```

Order summary dapat collapse jika terlalu panjang.

---

# 493. TABLET EXPERIENCE

Tablet storefront dapat menggunakan:

```text
2–3 product columns
```

POS sangat cocok dioptimalkan pada tablet landscape.

---

# 494. DESKTOP EXPERIENCE

Desktop memberikan:

* larger photography;
* stronger editorial grid;
* more whitespace;
* hover interaction;
* sticky product info;
* multi-column checkout.

Tidak berarti menambahkan lebih banyak decoration.

---

# 495. RESPONSIVE IMAGE PRINCIPLE

Jangan selalu mengirim gambar desktop resolusi penuh ke smartphone.

Gunakan:

```text
srcset
sizes
responsive image
```

atau image optimization strategy yang sesuai implementasi.

---

# 496. ACCESSIBILITY DESIGN RULES

Minimal visual tidak boleh mengurangi usability.

---

# 497. COLOR CONTRAST

Text harus memiliki contrast yang cukup.

Muted text tidak boleh terlalu terang hingga sulit dibaca.

---

# 498. KEYBOARD NAVIGATION

Desktop user harus dapat menggunakan:

```text
Tab
Shift + Tab
Enter
Escape
```

untuk interactive component.

---

# 499. FOCUS STATE

Semua:

```text
Button
Link
Input
Selector
```

mempunyai visible focus indicator.

Jangan menggunakan:

```text
outline: none;
```

tanpa pengganti.

---

# 500. TOUCH TARGET

Interactive element minimal:

```text
44 × 44px
```

terutama:

* variant;
* size;
* close;
* icon actions;
* quantity.

---

# 501. IMAGE ALT TEXT

Product image harus mempunyai descriptive alt.

Example:

```text
19HOUSE Essential Oversized Tee in Black – front view
```

Bukan:

```text
image1.jpg
```

---

# 502. COLOR SWATCH ACCESSIBILITY

Color swatch harus memiliki text/accessible name.

Contoh:

```text
● Black
○ White
○ Navy
```

Bukan hanya warna.

---

# 503. FORM ACCESSIBILITY

Form harus mempunyai:

```text
Label
Error message
Required indication
Focus state
```

---

# 504. IMAGE PERFORMANCE

Karena photography adalah aset terbesar, image performance menjadi bagian langsung dari UI quality.

Recommended:

```text
WebP / AVIF where supported
Lazy Loading
Responsive Sizes
Compressed Source
```

Hero pertama sebaiknya tidak lazy loaded jika menjadi LCP element.

---

# 505. DESIGN TOKEN DIRECTION

Implementasi Tailwind sebaiknya mempunyai custom design tokens.

Concept:

```text
colors
spacing
typography
radius
motion
container
```

---

# 506. COLOR TOKENS

Example:

```text
--color-bg
#F7F7F5

--color-surface
#FFFFFF

--color-ink
#111111

--color-muted
#6D6D6D

--color-subtle
#999999

--color-border
#E5E5E2
```

---

# 507. RADIUS TOKENS

```text
--radius-none: 0
--radius-sm: 2px
--radius-md: 4px
--radius-lg: 8px
```

---

# 508. MOTION TOKENS

```text
--duration-fast: 150ms
--duration-normal: 250ms
--duration-slow: 400ms
```

---

# 509. TYPOGRAPHY TOKENS

Concept:

```text
display-xl
display
heading-1
heading-2
heading-3
body
small
label
micro
```

Jangan mengatur font size secara random pada setiap Blade file.

---

# 510. SPACING TOKENS

Gunakan Tailwind spacing system secara konsisten.

Avoid:

```text
mt-[37px]
px-[23px]
gap-[19px]
```

kecuali kebutuhan layout benar-benar spesifik.

---

# 511. COMPONENT SYSTEM

Reusable storefront components:

```text
Header
Mobile Navigation
Footer

Product Card
Product Grid
Product Gallery

Variant Selector
Size Selector
Quantity Selector

Button
Input
Select
Checkbox

Cart Drawer
Filter Drawer

Accordion
Modal
Toast

Empty State
Skeleton
Pagination
```

---

# 512. CUSTOMER COMPONENTS

```text
Order Card
Order Timeline
Address Card
Wishlist Item
Notification Item
Account Navigation
```

---

# 513. ADMIN COMPONENTS

```text
Sidebar
Topbar
Metric Card
Data Table
Filter Bar
Status Badge
Search Input
Pagination
Form Section
Confirmation Modal
Empty State
```

---

# 514. POS COMPONENTS

```text
Product Search
Product Tile
SKU Selector
Current Order Item
Order Summary
Payment Modal
Receipt
```

---

# 515. MANAGEMENT COMPONENTS

```text
KPI Card
Date Filter
Sales Trend Chart
Channel Comparison
Best Seller Table
Inventory Alert
Report Table
```

---

# 516. STATUS STYLE SYSTEM

Order statuses harus menggunakan style konsisten.

Example:

```text
Pending Payment
Neutral / Warning

Paid
Success

Processing
Info

Shipped
Info

Completed
Success

Cancelled
Muted / Error
```

Gunakan subtle background daripada saturated badge.

---

# 517. STOREFRONT VS DASHBOARD STATUS

Storefront:

```text
PROCESSING
```

dapat berupa text sederhana.

Admin:

```text
Processing
```

dapat menggunakan subtle status badge agar cepat discan.

---

# 518. HOMEPAGE VISUAL COMPOSITION

Recommended page rhythm:

```text
HEADER

↓ small gap

HERO
Large editorial photography

↓ large whitespace

NEW ARRIVALS
Product grid

↓ very large whitespace

EDITORIAL CAMPAIGN
Full width / split layout

↓ large whitespace

FEATURED COLLECTION
Large imagery

↓ large whitespace

SELECTED PRODUCTS

↓ large whitespace

BRAND MESSAGE

FOOTER
```

---

# 519. HERO DIRECTION

Hero sebaiknya menggunakan:

```text
Large Photography
+
Very little copy
```

Example:

```text
19HOUSE
NEW COLLECTION

SHOP NOW
```

atau hanya:

```text
SUMMER 26

DISCOVER
```

Photography harus berbicara lebih banyak daripada text.

---

# 520. HERO HEIGHT

Desktop:

```text
80–100vh
```

tergantung photography.

Mobile:

```text
70–90vh
```

Tidak wajib selalu full screen.

---

# 521. HERO TEXT POSITION

Dapat menggunakan:

```text
Bottom Left
Centered
Bottom Center
```

pilihan ditentukan oleh negative space pada photography.

Jangan menutupi wajah/model atau product utama.

---

# 522. COLLECTION HEADER

Collection page:

```text
COLLECTION NAME

Short optional description

48 PRODUCTS
```

Gunakan large heading + whitespace.

Tidak perlu banner pada setiap collection.

---

# 523. FILTER UI DESKTOP

Recommended:

```text
FILTER +
SORT
```

simple text actions di atas grid.

Alternative:

```text
Category
Size
Color
Price
```

sebagai compact controls.

Jangan membuat large marketplace filter sidebar secara default.

---

# 524. FILTER UI MOBILE

Gunakan:

```text
[ FILTER ] [ SORT ]
```

kemudian drawer.

---

# 525. PAGINATION / LOAD MORE

Untuk fashion browsing, pilihan:

```text
LOAD MORE
```

dapat terasa lebih natural daripada pagination number yang padat.

Infinite scrolling boleh dipertimbangkan tetapi mempunyai implikasi UX/SEO.

Rekomendasi awal:

```text
LOAD MORE
```

atau pagination sederhana.

---

# 526. SEARCH EXPERIENCE

Search dapat menggunakan overlay/full screen minimal.

Example:

```text
SEARCH

________________________________

Popular
Oversized Tee
Outerwear
New Arrivals
```

Setelah mengetik:

```text
Product suggestions
```

dapat tampil.

---

# 527. LOGIN / REGISTER VISUAL

Login tidak harus terlihat seperti admin login.

Recommended:

```text
Large whitespace
Centered / split composition
Minimal form
Optional editorial image
```

Desktop:

```text
┌──────────────────────┬──────────────────────┐
│                      │                      │
│ Editorial Image      │ LOGIN                │
│                      │                      │
│                      │ Email                │
│                      │ Password             │
│                      │                      │
│                      │ [ SIGN IN ]          │
│                      │                      │
└──────────────────────┴──────────────────────┘
```

Mobile hanya form + branding sudah cukup.

---

# 528. CUSTOMER ACCOUNT VISUAL

Customer Account tidak menggunakan admin sidebar besar.

Desktop:

```text
ACCOUNT
│
├── Overview
├── Orders
├── Wishlist
├── Addresses
└── Profile
```

dengan content luas dan minimal.

---

# 529. ORDER TIMELINE

Recommended:

```text
ORDER PLACED
12 Sep

● Payment confirmed

│

● Processing

│

○ Shipped

│

○ Delivered
```

Simple line/timeline.

Tidak perlu elaborate illustration.

---

# 530. NOTIFICATION UI

Header notification dapat menggunakan simple icon dengan small count.

Panel:

```text
Notifications

Order #...
Your order has shipped.
2h

Payment successful.
Yesterday
```

Unread ditandai menggunakan:

* font weight;
* subtle dot;
* subtle background.

---

# 531. ADMIN VISUAL LANGUAGE

Admin tidak menggunakan theme yang berbeda total.

Gunakan:

```text
Background
#F7F7F5

Surface
#FFFFFF

Text
#111111

Border
#E5E5E2
```

Dengan data density lebih tinggi.

---

# 532. ADMIN SIDEBAR

Sidebar:

```text
240–260px
```

Background:

```text
#111111
```

dengan light content dapat digunakan,

atau white minimalist sidebar.

Rekomendasi 19HOUSE:

**Dark sidebar + light workspace** dapat menjadi pembeda jelas antara internal tool dan storefront tanpa kehilangan branding.

Example:

```text
19HOUSE

OVERVIEW
Dashboard

COMMERCE
Orders
Payments

CATALOG
Products
Categories

INVENTORY
Stock
Movements
```

---

# 533. ADMIN ACTIVE NAVIGATION

Active state cukup:

```text
lighter background
```

atau:

```text
left indicator
```

Tidak membutuhkan glowing effects.

---

# 534. ADMIN TOPBAR

```text
Page Title

Search (optional)

Notification
Profile
```

Nama/jabatan tidak perlu selalu memenuhi topbar.

Profile dropdown menampilkan:

```text
Profile
Account
Logout
```

---

# 535. MANAGEMENT VISUAL LANGUAGE

Management Dashboard lebih presentation-oriented.

KPI dapat dibuat lebih besar dengan banyak breathing space.

Example:

```text
REVENUE

Rp124.800.000

+8.4%
vs previous month
```

Charts diberi lebih banyak ruang.

---

# 536. DESIGN CONSISTENCY RULES

Tidak diperbolehkan setiap halaman menggunakan style berbeda.

Harus konsisten untuk:

```text
Buttons
Inputs
Labels
Spacing
Heading
Tables
Status
Modal
Drawer
Toast
Icons
```

---

# 537. WHAT 19HOUSE SHOULD AVOID

Storefront tidak boleh berubah menjadi:

### Marketplace UI

```text
Too many badges
Too many promos
Too many bright colors
Too many cards
```

### SaaS UI

```text
Everything inside rounded card
Gradient buttons
Floating containers
Excessive dashboard aesthetic
```

### Gaming UI

```text
Glow
Neon
Heavy animation
```

### Over-Luxury UI

```text
Excessive serif
Gold everywhere
Decorative ornament
```

---

# 538. DESIGN DO / DON'T

## DO

```text
Use large photography
Use whitespace
Keep product cards clean
Use neutral palette
Use subtle motion
Keep navigation simple
Make mobile interaction easy
Use consistent image ratio
```

## DON'T

```text
Add excessive shadows
Add gradients without purpose
Overuse rounded cards
Add too many badges
Use too many fonts
Use too many colors
Use excessive animation
Crowd the homepage
```

---

# 539. STOREFRONT VISUAL PRIORITY

When deciding between two UI solutions, priority:

```text
1. Product visibility
2. Clarity
3. Usability
4. Brand character
5. Decoration
```

---

# 540. PRODUCT PHOTOGRAPHY PRIORITY

Because design relies heavily on product photography:

```text
Bad photos
+
Good UI
=
Still weak storefront
```

Therefore photography is considered part of the design system.

Every upload should ideally follow a photography guideline.

---

# 541. PRODUCT IMAGE UPLOAD RECOMMENDATION

Admin upload target:

```text
Minimum
1200 × 1600px

Recommended
1500 × 2000px or higher

Ratio
3:4
```

Image processor dapat membuat multiple responsive derivatives.

---

# 542. HOMEPAGE EDITORIAL IMAGE QUALITY

Campaign image should be higher resolution because it may occupy nearly full viewport.

Crop should be tested at:

```text
Desktop
Tablet
Mobile
```

Separate mobile hero image may be supported later if necessary.

---

# 543. RESPONSIVE CONTENT CROPPING

Gunakan:

```text
object-fit: cover
```

untuk editorial/campaign imagery.

Product catalog photography:

```text
object-fit: cover / controlled ratio
```

dengan crop konsisten.

Jika full garment details penting, gunakan framing photography yang menyediakan crop safety.

---

# 544. LIVEWIRE UX GUIDELINES

Karena 19HOUSE menggunakan Livewire, interaction harus tetap terasa natural.

Use Livewire for:

```text
Variant switching
Size availability
Cart
Wishlist
Filter
Checkout
Notification
POS
```

Setiap request harus mempunyai:

```text
Loading state
Disabled state where necessary
Error recovery
```

---

# 545. LIVEWIRE FLICKER PREVENTION

Gunakan:

```text
wire:loading
wire:target
wire:key
```

dan structure yang benar untuk menghindari:

* duplicate UI;
* flicker;
* incorrect gallery state.

---

# 546. LIVEWIRE IMAGE TRANSITION

Variant switch:

```text
Select White
↓
Update selected variant
↓
Update gallery
↓
Subtle fade
```

Jangan membuat server round-trip terasa seperti full page refresh.

---

# 547. CART COUNT

Cart count:

```text
BAG (2)
```

lebih sesuai editorial style dibanding bright circular badge.

Alternative icon count kecil diperbolehkan.

---

# 548. WISHLIST ICON

Heart outline:

```text
♡
```

selected:

```text
♥
```

atau consistent filled icon.

Tidak perlu animation berlebihan.

---

# 549. COPYWRITING STYLE

UI copy 19HOUSE:

```text
Short
Clear
Calm
Direct
```

Example:

```text
ADD TO CART
SELECT SIZE
VIEW COLLECTION
CONTINUE SHOPPING
```

Avoid:

```text
Grab yours now before it's gone!!!
```

kecuali campaign marketing memang sengaja menggunakan tone tersebut.

---

# 550. LANGUAGE CONSISTENCY

Jika storefront menggunakan Bahasa Indonesia:

```text
TAMBAH KE KERANJANG
PILIH UKURAN
LIHAT KOLEKSI
```

Jika menggunakan English fashion-label style:

```text
ADD TO CART
SELECT SIZE
VIEW COLLECTION
```

Jangan campur secara random.

Rekomendasi 19HOUSE berdasarkan visual direction:

**Gunakan English untuk navigation/short commerce labels apabila brand memang ingin terasa seperti independent fashion label, sementara informasi operasional dan pesan kompleks dapat menggunakan Bahasa Indonesia.**

Namun keputusan bahasa final harus konsisten.

---

# 551. UI STATE REQUIREMENT

Setiap component harus didesain minimal untuk state:

```text
Default
Hover
Focus
Active
Disabled
Loading
Error
Success
```

Jika relevan:

```text
Empty
Out of Stock
Selected
```

---

# 552. PRODUCT CARD STATES

```text
Default
Hover
Wishlist Selected
Sold Out
Loading
```

---

# 553. SIZE SELECTOR STATES

```text
Default
Hover
Selected
Unavailable
Disabled
Focus
```

---

# 554. BUTTON STATES

```text
Default
Hover
Focus
Pressed
Loading
Disabled
```

---

# 555. INPUT STATES

```text
Default
Focus
Filled
Error
Disabled
```

---

# 556. DESIGN QA CHECKLIST — STOREFRONT

Sebelum halaman dianggap sesuai visual direction:

```text
✓ Product imagery menjadi fokus
✓ Tidak terlalu banyak card
✓ Whitespace cukup
✓ Typography konsisten
✓ Tidak ada unnecessary gradient
✓ Tidak ada excessive shadow
✓ Border digunakan secara minimal
✓ Image ratio konsisten
✓ CTA jelas
✓ Mobile usable
✓ Loading state tersedia
✓ Empty/error state tersedia
✓ Animation subtle
✓ Product gallery responsive
✓ Touch target memadai
```

---

# 557. DESIGN QA CHECKLIST — PRODUCT DETAIL

```text
✓ Foto variant mengikuti selected color
✓ Gallery dominan
✓ Product name mudah ditemukan
✓ Price jelas
✓ Variant jelas
✓ Size state jelas
✓ Sold-out size disabled
✓ Size guide accessible
✓ Add to Cart dominant
✓ Description tidak memenuhi screen
✓ Sticky information bekerja di desktop
✓ Mobile flow linear
```

---

# 558. DESIGN QA CHECKLIST — CHECKOUT

```text
✓ Customer tahu apa yang harus dilakukan
✓ Tidak ada distraction
✓ Address jelas
✓ Shipping jelas
✓ Payment jelas
✓ Voucher tidak mendominasi
✓ Order summary visible
✓ Total sangat jelas
✓ Place Order CTA jelas
✓ Validation dekat dengan field
✓ Mobile checkout mudah
```

---

# 559. DESIGN QA CHECKLIST — ADMIN

```text
✓ Navigation mudah dipindai
✓ Data table readable
✓ Filter mudah ditemukan
✓ Action jelas
✓ Status konsisten
✓ Tidak terlalu banyak card
✓ Empty state tersedia
✓ Confirmation destructive action tersedia
✓ Responsive untuk target viewport
```

---

# 560. DESIGN QA CHECKLIST — POS

```text
✓ Search selalu mudah diakses
✓ Product selection cepat
✓ Variant/size mudah dipilih
✓ Stock terlihat
✓ Current order selalu jelas
✓ Total sangat terlihat
✓ Payment CTA besar
✓ Touch target sesuai
✓ Complete transaction memerlukan confirmation yang tepat
```

---

# 561. VISUAL DIRECTION SUMMARY

19HOUSE secara visual harus terasa seperti:

```text
Fashion brand website
+
Editorial product catalogue
+
Modern commerce experience
```

dan bukan:

```text
Generic marketplace
+
Template e-commerce
```

Signature visual 19HOUSE berasal dari:

```text
Large Photography
Large Whitespace
Neutral Color
Simple Typography
Strong Composition
Subtle Interaction
Consistent Product Imagery
```

---

# 562. FINAL UI DIRECTION

## Storefront

```text
Minimal
Editorial
White / Off-white
Image Dominant
Clean Typography
Almost No Shadow
Minimal Border
Square / Small Radius
Large Whitespace
Subtle Motion
```

## Customer Account

```text
Storefront visual language
+
More functional structure
```

## Admin

```text
Minimal operational dashboard
+
Medium density
+
Neutral UI
```

## Cashier

```text
Fast
Clear
Large interaction target
Minimal distraction
```

## Management

```text
Clean analytics
Large KPI
Simple charts
Read-only visual language
```

---

# 563. 19HOUSE DESIGN MANTRA

Seluruh keputusan visual dapat diuji dengan satu pertanyaan:

> **Apakah UI ini membantu produk terlihat lebih kuat, atau justru UI-nya yang mengambil perhatian?**

Jika interface menjadi lebih dominan daripada product photography, desain harus disederhanakan.

Dengan demikian visual language 19HOUSE tetap sesuai referensi utama: **fashion editorial, minimal, photography-led, clean, modern, dan purposeful.**
