<?php
require __DIR__ . '/../includes/layout.php';
$cat = catalog();
site_head(['title' => 'Sarees', 'active' => 'collections', 'base' => '../', 'crumbs' => [['Home', '../index.php'], ['Collections', 'index.php'], ['Sarees', null]]]);
?>
<main>
<?php if (!$cat['ok']) { catalog_error(); } else { ?>
    <section class="section" id="sarees" data-catalog>
        <div class="container">
            <h1 class="page-title">Sarees</h1>
            <?php saree_tabs(); ?>
            <div class="grid"><?php foreach (by_section('sarees') as $p) render_card($p, '../'); ?></div>
        </div>
    </section>
    <?php no_results(); ?>
<?php } ?>
</main>
<?php site_foot('../'); ?>
