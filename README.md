# Saree Aura — Bespoke Saree & Ethnic Wear E-Commerce Storefront

A responsive, full-stack **PHP 8.0+ and MySQL** e-commerce web application developed as a **5-member collaborative software engineering project** for **Saree Aura**, showcasing 68 curated sarees, bridal lehengas, evening gowns, and accessories with islandwide delivery across Sri Lanka.

## Project Team (5 Members)

| # | Member Name | GitHub Username | Key Responsibilities & Modules |
| :--- | :--- | :--- | :--- |
| 1 | **Savindu Gihan Perera** | `@SavinduGihan` | Project Architecture, PDO Database Integration (`config/db.php`), Main Storefront (`index.php`), & GitHub Repository Management |
| 2 | **Senuri Rubasinghege** | `@senurirubasinghege` | MySQL Schema Design (`database.sql`), 68-Product LKR Pricing & Seeding, & `.webp` Asset Standardization |
| 3 | **Tharushi Amasha Devindi** | `@TharushiAmasha` | Responsive UI/UX Styling (`styles.css`), Ivory/Burgundy/Gold Theme System, & Shared Layout (`includes/layout.php`) |
| 4 | **Janadi Vidanagamage** | `@Janadividanagamage` | Live Auto-Suggest Search, Occasion Filtering, & Selection Bag Cart + WhatsApp Checkout (`script.js`) |
| 5 | **Denuwan Amarathunga** | `@DenuwanCA` | Dedicated Collection Views (`collections/*.php`), Custom Contact Page (`contact.php`), & QA Testing |

## Key Features

- **Curated 68-Product Catalog with LKR Pricing:** Organized into Sarees (*15 Casual, 18 Party, 3 Homecoming, 2 Bridal*), Accessories & Lehengas (*18 items*), and Dresses & Gowns (*12 items*), backed by a normalized 4-table MySQL schema (`categories`, `products`, `tags`, `product_tags`).
- **Live Search with Auto-Suggest Hints:** Real-time filtering across product names, categories, and fabric/occasion tags with clickable quick-filter chips and product preview suggestions.
- **Interactive Selection Bag (Cart):** Client-side `localStorage` cart drawer supporting quantity adjustments (`+` / `-`), item removal, clear bag, real-time LKR subtotal calculation, and one-click prefilled **Order via WhatsApp** checkout (`+94 76 986 4511`).
- **Multi-Page Navigation & Breadcrumbs:** Dedicated `collections/` views (`index.php`, `sarees.php`, `accessories-lehengas.php`, `dresses-gowns.php`) and `contact.php` powered by `includes/layout.php` with active navigation highlighting and breadcrumb trail indicators.
- **Custom Contact Page:** Includes boutique location details (`301/4 Dehiwala Road, Bellanwila, Boralesgamuwa`), clickable phone/WhatsApp links, and a validated PHP/HTML contact inquiry form.

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
├── includes/
│   └── layout.php               # Shared navbar, breadcrumb bar, footer & cart drawer
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
├── contact.php                  # Contact Us page & custom inquiry form
├── styles.css                   # Responsive Ivory/Burgundy/Charcoal/Gold stylesheet
├── script.js                    # Live search, filters & WhatsApp cart logic
└── database.sql                 # MySQL schema & 68-product seed data
