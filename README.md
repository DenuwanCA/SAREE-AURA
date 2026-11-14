# Saree Aura — Bespoke Saree & Ethnic Wear E-Commerce Storefront

A responsive, full-stack **PHP 8.0+ and MySQL** e-commerce web application developed as a **5-member collaborative software engineering project** for **Saree Aura**, showcasing 68 curated sarees, bridal lehengas, evening gowns, and accessories with islandwide delivery across Sri Lanka.

## Project Team (5 Members)

| # | Member Name | Student ID / GitHub | Key Responsibilities & Modules |
| :--- | :--- | :--- | :--- |
| 1 | **Savindu Perera** | `@<your-github>` | Project Architecture, PDO Database Integration (`config/db.php`), Main Storefront (`index.php`), & GitHub Repository Management |
| 2 | **Member 2 Name** | `@<member2-github>` | MySQL Schema Design (`database.sql`), 68-Product Data Seeding, & `.webp` Asset Standardization |
| 3 | **Member 3 Name** | `@<member3-github>` | Responsive UI/UX Styling (`styles.css`), Ivory/Burgundy/Gold Theme System, & Breadcrumb Navigation |
| 4 | **Member 4 Name** | `@<member4-github>` | Live Auto-Suggest Search, Category Filtering, & Selection Bag Cart + WhatsApp Checkout (`script.js`) |
| 5 | **Member 5 Name** | `@<member5-github>` | Dedicated Collection Views (`collections/*.php`), Custom Contact Page (`contact.php`), & QA Testing |

## Key Features

- **Curated 68-Product Catalog:** Organized into dedicated collections for Sarees (*Casual, Party, Homecoming, Bridal*), Accessories & Lehengas, and Dresses & Gowns, backed by a normalized 4-table MySQL schema (`categories`, `products`, `tags`, `product_tags`).
- **Live Search with Auto-Suggest Hints:** Real-time filtering across product names, categories, and fabric/occasion tags with clickable quick-filter chips and product preview suggestions.
- **Interactive Selection Bag (Cart):** Client-side `localStorage` cart drawer supporting quantity adjustments (`+` / `-`), item removal, clear bag, real-time LKR subtotal calculation, and one-click prefilled **Order via WhatsApp** checkout.
- **Multi-Page Navigation & Breadcrumbs:** Dedicated `collections/` views (`index.php`, `sarees.php`, `accessories-lehengas.php`, `dresses-gowns.php`) and `contact.php` with active navigation highlighting and breadcrumb trail indicators.
- **Custom Contact Page:** Includes boutique location details (`301/4 Dehiwala Road, Bellanwila, Boralesgamuwa`), clickable phone/WhatsApp links, and a validated PHP/HTML contact inquiry form.
- **Optimized Visual Assets:** All 68 product images standardized to lightweight `.webp` format paired with a custom Ivory (`#F7F2EA`), Burgundy (`#401321`), Charcoal (`#292321`), and Gold (`#B69A68`) theme using **Playfair Display** and **DM Sans**.

## Tech Stack

- **Frontend:** Semantic HTML5, CSS3 (Flexbox & CSS Grid), Vanilla JavaScript (ES6+)
- **Backend:** PHP 8.0+ (PDO prepared statements)
- **Database:** MySQL 5.7+ / MariaDB
- **Local Server:** XAMPP (Apache + MySQL)

## Project Structure

```text
saree-aura/
├── config/
│   └── db.php                   # PDO MySQL database connection
├── collections/
│   ├── index.php                # Collections overview
│   ├── sarees.php               # Sarees collection with occasion tabs
│   ├── accessories-lehengas.php # Accessories & bridal lehengas
│   └── dresses-gowns.php        # Evening dresses & gowns
├── images/
│   ├── logo.png                 # Brand emblem
│   ├── sarees/                  # Casual, party, homecoming & bridal .webp assets
│   ├── accessories/             # Handbags, brooches & lehenga skirts (.webp)
│   └── dresses/                 # Satin dresses & sequin gowns (.webp)
├── index.php                    # Main storefront landing page
├── contact.php                  # Contact Us page & inquiry form
├── styles.css                   # Responsive theme stylesheet
├── script.js                    # Live search, filters & WhatsApp cart logic
└── database.sql                 # MySQL schema & 68-product seed data
