<?php
require __DIR__ . '/../includes/layout.php';
$cat = catalog();
site_head(['title' => 'Dresses & Gowns', 'active' => 'collections', 'base' => '../', 'crumbs' => [['Home', '../index.php'], ['Collections', 'index.php'], ['Dresses & Gowns', null]]]);
?>
<main>
<?php if (!$cat['ok']) { catalog_error(); } else { ?>
    <section class="section" id="dresses" data-catalog>
        <div class="container">
            <h1 class="page-title">Dresses &amp; Gowns</h1>
            
            <div class="grid"><?php foreach (by_section('dresses') as $p) render_card($p, '../'); ?></div>
        </div>
    </section>
    <?php no_results(); ?>
<?php } ?>
</main>
<?php site_foot('../'); ?>
