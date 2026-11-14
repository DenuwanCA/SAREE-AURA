<?php
require __DIR__ . '/config/db.php';

const WA = '94769864511';
$h = fn($s) => htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');

$sections = ['sarees' => [], 'dresses' => [], 'accessories' => []];
$categories = [];
$error = false;
try {
    $categories = db()->query("SELECT * FROM categories WHERE section='sarees' ORDER BY sort_order")->fetchAll();
    $rows = db()->query(
        "SELECT p.*, c.slug AS cat_slug, c.name AS cat_name, c.section,
                GROUP_CONCAT(t.name ORDER BY t.type, t.name SEPARATOR '|') AS tags
         FROM products p
         JOIN categories c ON c.id = p.category_id
         LEFT JOIN product_tags pt ON pt.product_id = p.id
         LEFT JOIN tags t ON t.id = pt.tag_id
         GROUP BY p.id ORDER BY c.sort_order, p.sort_order"
    )->fetchAll();
    foreach ($rows as $r) { $sections[$r['section']][] = $r; }
} catch (PDOException $e) {
    $error = true;
}

function card(array $p, callable $h): void {
    $tags = $p['tags'] !== null && $p['tags'] !== '' ? explode('|', $p['tags']) : [];
    $price = $p['price'] !== null ? (float)$p['price'] : null;
    $inquiry = 'https://wa.me/' . WA . '?text=' . rawurlencode('Hello Saree Aura, I would like to know the price of: ' . $p['name']);
    ?>
    <article class="card" data-id="<?= (int)$p['id'] ?>" data-name="<?= $h($p['name']) ?>"
             data-price="<?= $price !== null ? $h($price) : '' ?>" data-cat="<?= $h($p['cat_slug']) ?>"
             data-search="<?= $h(strtolower($p['name'] . ' ' . implode(' ', $tags) . ' ' . $p['cat_name'])) ?>">
        <div class="card-img">
            <img src="./<?= $h($p['image_path']) ?>" alt="<?= $h($p['name']) ?>" loading="lazy">
            <span class="badge"><?= $h($p['cat_name']) ?></span>
        </div>
        <div class="card-body">
            <h3><?= $h($p['name']) ?></h3>
            <?php if ($tags): ?>
            <ul class="tags"><?php foreach ($tags as $t): ?><li><?= $h($t) ?></li><?php endforeach; ?></ul>
            <?php endif; ?>
            <p class="price<?= $price === null ? ' request' : '' ?>">
                <?= $price !== null ? 'LKR ' . number_format($price, 2) : 'Price on Request' ?>
            </p>
            <div class="card-actions">
                <button class="btn btn-primary" type="button" data-add>Add to Bag</button>
                <?php if ($price === null): ?>
                <a class="btn btn-outline" href="<?= $h($inquiry) ?>" target="_blank" rel="noopener">Inquire on WhatsApp</a>
                <?php endif; ?>
            </div>
        </div>
    </article>
    <?php
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Saree Aura | Sarees &amp; Accessories in Sri Lanka</title>
<meta name="description" content="Saree Aura offers casual, party, homecoming and bridal sarees with accessories. Islandwide delivery across Sri Lanka.">
<link rel="icon" href="./images/logo.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="./styles.css">
</head>
<body>

<div class="announce">
    <p>Islandwide Delivery across Sri Lanka</p>
    <p class="announce-tel"><a href="tel:0769864511">076 986 4511</a> <a href="tel:0705921490">070 592 1490</a></p>
</div>

<header class="site-header">
    <div class="container header-row">
        <a class="brand" href="#top" aria-label="Saree Aura home"><img src="./images/logo.png" alt="Saree Aura"></a>
        <nav id="nav" class="nav" aria-label="Main">
            <a href="#sarees">Sarees</a>
            <a href="#collections">Collections</a>
            <a href="#accessories">Accessories</a>
            <a href="#dresses">Dresses &amp; Gowns</a>
            <a href="#about">About</a>
            <a href="#contact">Contact</a>
        </nav>
        <div class="header-search">
            <input type="search" id="searchInput" placeholder="Search sarees, dresses and accessories" aria-label="Search products" autocomplete="off">
        </div>
        <div class="header-actions">
            <button class="icon-btn" id="cartToggle" type="button" aria-label="Open selection bag">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 4h2l2.4 11h10.2L20 7H6"/><circle cx="9" cy="19.5" r="1.3"/><circle cx="17" cy="19.5" r="1.3"/></svg>
                <span class="cart-count" id="cartCount">0</span>
            </button>
            <a class="btn btn-gold header-wa" href="https://wa.me/<?= WA ?>" target="_blank" rel="noopener">WhatsApp</a>
            <button class="icon-btn menu-btn" id="menuToggle" type="button" aria-label="Menu" aria-expanded="false" aria-controls="nav">
                <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
            </button>
        </div>
    </div>
</header>

<main id="top">
    <section class="hero">
        <div class="container hero-grid">
            <div class="hero-copy">
                <h1>Drape Yourself in Timeless Elegance</h1>
                <p>Bridal, party, homecoming and casual sarees, with accessories to complete the look. Delivered islandwide across Sri Lanka.</p>
                <div class="hero-cta">
                    <a class="btn btn-primary" href="#sarees">Shop Sarees</a>
                    <a class="btn btn-outline" href="#accessories">View Accessories</a>
                </div>
            </div>
            <div class="hero-images">
                <img src="./images/sarees/bridal/bridal-elegant-white-and-gold-saree.webp" alt="Elegant white and gold bridal saree">
                <img src="./images/sarees/party/party-elegant-black-and-gold-saree.webp" alt="Elegant black and gold saree">
            </div>
        </div>
    </section>

    <section class="section alt" id="about">
        <div class="container narrow">
            <h2>About Saree Aura</h2>
            <p>Saree Aura is an online saree boutique for women in Sri Lanka. We bring together handloom and silk sarees for everyday wear, embroidered and sequin sarees for parties, and statement pieces for homecomings and weddings. We also offer dresses and gowns, plus accessories from handbags to brooches. Every order is delivered islandwide, and we are always a message away on WhatsApp.</p>
        </div>
    </section>

    <?php if ($error): ?>
    <section class="section"><div class="container narrow"><p class="notice">The catalogue could not be loaded. Check the database settings in config/db.php and import database.sql.</p></div></section>
    <?php else: ?>

    <section class="section" id="sarees">
        <div class="container">
            <h2>Sarees</h2>
            <div class="tabs" id="collections" role="tablist" aria-label="Saree collections">
                <button type="button" role="tab" class="tab active" data-tab="all" aria-selected="true">All</button>
                <?php foreach ($categories as $c): ?>
                <button type="button" role="tab" class="tab" data-tab="<?= $h($c['slug']) ?>" aria-selected="false"><?= $h($c['name']) ?></button>
                <?php endforeach; ?>
            </div>
            <div class="grid" id="sareeGrid"><?php foreach ($sections['sarees'] as $p) card($p, $h); ?></div>
            <p class="empty" hidden>No sarees match your search.</p>
        </div>
    </section>

    <section class="section alt" id="accessories">
        <div class="container">
            <h2>Accessories &amp; Lehengas</h2>
            <div class="grid" id="accessoryGrid"><?php foreach ($sections['accessories'] as $p) card($p, $h); ?></div>
            <p class="empty" hidden>No accessories or lehengas match your search.</p>
        </div>
    </section>

    <section class="section" id="dresses">
        <div class="container">
            <h2>Dresses &amp; Gowns</h2>
            <div class="grid" id="dressGrid"><?php foreach ($sections['dresses'] as $p) card($p, $h); ?></div>
            <p class="empty" hidden>No dresses or gowns match your search.</p>
        </div>
    </section>
    <?php endif; ?>

    <section class="section alt" id="why">
        <div class="container">
            <h2>Why Choose Saree Aura</h2>
            <div class="why-grid">
                <div><h3>Quality You Can See</h3><p>Sarees in handloom, silk, net and organza, chosen for finish and drape.</p></div>
                <div><h3>Personal Guidance</h3><p>Message us on WhatsApp and we will help you find the right saree for your occasion.</p></div>
                <div><h3>Fair Prices</h3><p>Everyday handlooms to bridal pieces, with clear pricing or a quote on request.</p></div>
                <div><h3>Delivered Islandwide</h3><p>Orders are delivered to your door anywhere in Sri Lanka.</p></div>
            </div>
        </div>
    </section>
</main>

<footer class="site-footer" id="contact">
    <div class="container footer-grid">
        <div>
            <img class="footer-logo" src="./images/logo.png" alt="Saree Aura">
            <p>Sarees and accessories for every occasion. Islandwide Delivery across Sri Lanka.</p>
        </div>
        <div>
            <h4>Quick Links</h4>
            <ul>
                <li><a href="#sarees">Sarees</a></li>
                <li><a href="#collections">Collections</a></li>
                <li><a href="#accessories">Accessories</a></li>
                <li><a href="#dresses">Dresses &amp; Gowns</a></li>
                <li><a href="#about">About</a></li>
            </ul>
        </div>
        <div>
            <h4>Contact</h4>
            <ul>
                <li><a href="tel:0769864511">076 986 4511</a></li>
                <li><a href="tel:0705921490">070 592 1490</a></li>
                <li><a class="btn btn-gold" href="https://wa.me/<?= WA ?>" target="_blank" rel="noopener">Chat on WhatsApp</a></li>
            </ul>
            <div class="social">
                <a href="#" aria-label="Facebook"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 8h2V5h-2.5C11.6 5 10 6.5 10 8.6V11H8v3h2v6h3v-6h2.2l.5-3H13V8.8c0-.5.2-.8 1-.8z"/></svg></a>
                <a href="#" aria-label="Instagram"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4" y="4" width="16" height="16" rx="4.5"/><circle cx="12" cy="12" r="3.6"/><circle cx="17" cy="7" r=".6"/></svg></a>
                <a href="#" aria-label="TikTok"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 4v10.2a3.2 3.2 0 1 1-3.2-3.2M14 4c.3 2.2 1.8 3.8 4 4"/></svg></a>
                <a href="#" aria-label="YouTube"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="6" width="18" height="12" rx="3.5"/><path d="M10.5 9.5v5l4-2.5z"/></svg></a>
            </div>
        </div>
    </div>
    <p class="copyright">&copy; <?= date('Y') ?> Saree Aura. All rights reserved.</p>
</footer>

<div class="overlay" id="overlay" hidden></div>
<aside class="drawer" id="drawer" aria-label="Selection bag" aria-hidden="true">
    <div class="drawer-head"><h2>Your Selection Bag</h2><button class="icon-btn" id="cartClose" type="button" aria-label="Close selection bag">&times;</button></div>
    <ul class="cart-items" id="cartItems"></ul>
    <p class="cart-empty" id="cartEmpty">Your selection bag is empty.</p>
    <div class="drawer-foot">
        <p class="subtotal"><span>Subtotal</span><strong id="cartSubtotal">LKR 0.00</strong></p>
        <p class="note" id="cartNote" hidden>Items marked Price on Request are not included in the subtotal. We will confirm their prices on WhatsApp.</p>
        <a class="btn btn-primary btn-block" id="orderBtn" href="#" target="_blank" rel="noopener">Order via WhatsApp</a>
    </div>
</aside>

<script src="./script.js"></script>
</body>
</html>
