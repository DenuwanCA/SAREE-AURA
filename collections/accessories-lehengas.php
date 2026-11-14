<?php
require __DIR__ . '/../includes/layout.php';
$cat = catalog();
site_head(['title' => 'Accessories & Lehengas', 'active' => 'collections', 'base' => '../', 'crumbs' => [['Home', '../index.php'], ['Collections', 'index.php'], ['Accessories & Lehengas', null]]]);
?>
<main>
<?php if (!$cat['ok']) { catalog_error(); } else { ?>
    <section class="section" id="accessories" data-catalog>
        <div class="container">
            <h1 class="page-title">Accessories &amp; Lehengas</h1>
            
            <div class="grid"><?php foreach (by_section('accessories') as $p) render_card($p, '../'); ?></div>
        </div>
    </section>
    <?php no_results(); ?>
<?php } ?>
</main>
<?php site_foot('../'); ?>
