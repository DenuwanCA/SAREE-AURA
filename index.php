<?php
require __DIR__ . '/includes/layout.php';
$cat = catalog();
site_head(['title' => 'Sarees, Dresses & Accessories in Sri Lanka', 'active' => 'home', 'base' => '', 'crumbs' => [['Home', null]]]);
?>
<main id="top">
    <section class="hero">
        <div class="container hero-grid">
            <div class="hero-copy">
                <h1>Drape Yourself in Timeless Elegance</h1>
                <p>Bridal, party, homecoming and casual sarees, with dresses, gowns and accessories to complete the look. Delivered islandwide across Sri Lanka.</p>
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
            <p>Saree Aura is a saree boutique for women in Sri Lanka. We bring together handloom and silk sarees for everyday wear, embroidered and sequin sarees for parties, and statement pieces for homecomings and weddings. We also offer dresses and gowns, plus accessories from handbags to brooches. Every order is delivered islandwide, and we are always a message away on WhatsApp.</p>
        </div>
    </section>

<?php if (!$cat['ok']) { catalog_error(); } else { ?>
    <section class="section" id="sarees" data-catalog>
        <div class="container">
            <h2>Sarees</h2>
            <?php saree_tabs(); ?>
            <div class="grid"><?php foreach (by_section('sarees') as $p) render_card($p, ''); ?></div>
        </div>
    </section>

    <section class="section alt" id="accessories" data-catalog>
        <div class="container">
            <h2>Accessories &amp; Lehengas</h2>
            <div class="grid"><?php foreach (by_section('accessories') as $p) render_card($p, ''); ?></div>
        </div>
    </section>

    <section class="section" id="dresses" data-catalog>
        <div class="container">
            <h2>Dresses &amp; Gowns</h2>
            <div class="grid"><?php foreach (by_section('dresses') as $p) render_card($p, ''); ?></div>
        </div>
    </section>
    <?php no_results(); ?>
<?php } ?>

    <section class="section alt" id="why">
        <div class="container">
            <h2>Why Choose Saree Aura</h2>
            <div class="why-grid">
                <div><h3>Quality You Can See</h3><p>Sarees in handloom, silk, net and organza, chosen for finish and drape.</p></div>
                <div><h3>Personal Guidance</h3><p>Message us on WhatsApp and we will help you find the right saree for your occasion.</p></div>
                <div><h3>Fair Prices</h3><p>Everyday handlooms to bridal pieces, with clear LKR pricing on every item.</p></div>
                <div><h3>Delivered Islandwide</h3><p>Orders are delivered to your door anywhere in Sri Lanka.</p></div>
            </div>
        </div>
    </section>
</main>
<?php site_foot(''); ?>
