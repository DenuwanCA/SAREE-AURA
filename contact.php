<?php
session_start();
require __DIR__ . '/includes/layout.php';

$types = ['Saree Inquiry', 'Bridal / Homecoming Consultation', 'Custom Order', 'Delivery / General'];
$f = ['name' => '', 'phone' => '', 'email' => '', 'type' => '', 'message' => ''];
$errors = [];
$sent = false;
$thanks = '';
$waUrl = '';
if (empty($_SESSION['csrf'])) { $_SESSION['csrf'] = bin2hex(random_bytes(16)); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clean = function (string $k, int $max, bool $multiline = false) {
        $v = trim(strip_tags((string)($_POST[$k] ?? '')));
        if (!$multiline) { $v = preg_replace('/\s+/', ' ', $v); }
        return mb_substr($v, 0, $max);
    };
    $f = ['name' => $clean('name', 100), 'phone' => $clean('phone', 20), 'email' => $clean('email', 120),
          'type' => $clean('type', 60), 'message' => $clean('message', 1000, true)];
    if (!hash_equals($_SESSION['csrf'], (string)($_POST['csrf'] ?? '')) || !empty($_POST['website'])) {
        $errors['form'] = 'Your inquiry could not be sent. Please reload the page and try again.';
    }
    if (mb_strlen($f['name']) < 2) $errors['name'] = 'Please enter your full name.';
    if (!preg_match('/^\+?[0-9][0-9\s\-()]{7,17}$/', $f['phone'])) $errors['phone'] = 'Please enter a valid phone or WhatsApp number.';
    if ($f['email'] !== '' && !filter_var($f['email'], FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Please enter a valid email address.';
    if (!in_array($f['type'], $types, true)) $errors['type'] = 'Please choose an inquiry type.';
    if (mb_strlen($f['message']) < 10) $errors['message'] = 'Please write at least 10 characters.';

    if (!$errors) {
        $text = "Hello Saree Aura,\nName: {$f['name']}\nPhone: {$f['phone']}\n" . ($f['email'] !== '' ? "Email: {$f['email']}\n" : '') .
                "Inquiry type: {$f['type']}\n\n{$f['message']}";
        $waUrl = 'https://wa.me/' . WA . '?text=' . rawurlencode($text);
        if (($_POST['action'] ?? '') === 'whatsapp') { header('Location: ' . $waUrl); exit; }
        if (CONTACT_EMAIL !== '') {
            @mail(CONTACT_EMAIL, 'Saree Aura inquiry: ' . $f['type'], $text, 'Reply-To: ' . ($f['email'] !== '' ? $f['email'] : CONTACT_EMAIL));
        }
        $sent = true;
        $thanks = $f['name'];
        $f = ['name' => '', 'phone' => '', 'email' => '', 'type' => '', 'message' => ''];
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
}
$err = fn($k) => isset($errors[$k]) ? '<p class="field-error" id="err-' . $k . '">' . h($errors[$k]) . '</p>' : '';
$inv = fn($k) => isset($errors[$k]) ? ' aria-invalid="true" aria-describedby="err-' . $k . '"' : '';

site_head(['title' => 'Contact Us', 'active' => 'contact', 'base' => '', 'crumbs' => [['Home', 'index.php'], ['Contact Us', null]]]);
?>
<main>
    <section class="section">
        <div class="container">
            <h1 class="page-title">Contact Us</h1>
            <div class="contact-grid">
                <div class="contact-card">
                    <h2>Get in Touch</h2>
                    <p><strong>Address</strong><br><?= h(STORE_ADDRESS) ?></p>
                    <p><strong>Delivery</strong><br>Islandwide Delivery across Sri Lanka</p>
                    <p><strong>Phone</strong><br><a href="tel:0769864511">076 986 4511</a><br><a href="tel:0705921490">070 592 1490</a></p>
                    <a class="btn btn-primary" href="https://wa.me/<?= WA ?>" target="_blank" rel="noopener">Chat on WhatsApp</a>
                </div>
                <div class="contact-card">
                    <h2>Send an Inquiry</h2>
                    <?php if ($sent): ?>
                    <div class="alert" role="status">
                        <p><strong>Thank you, <?= h($thanks) ?>.</strong> <?= CONTACT_EMAIL !== '' ? 'Your inquiry has been sent.' : 'Your inquiry is ready.' ?> To reach us fastest, send it on WhatsApp.</p>
                        <a class="btn btn-primary" href="<?= h($waUrl) ?>" target="_blank" rel="noopener">Send via WhatsApp</a>
                    </div>
                    <?php endif; ?>
                    <?php if (isset($errors['form'])): ?><div class="alert alert-error" role="alert"><p><?= h($errors['form']) ?></p></div><?php endif; ?>
                    <form method="post" action="contact.php" novalidate>
                        <input type="hidden" name="csrf" value="<?= h($_SESSION['csrf']) ?>">
                        <div class="hp" aria-hidden="true"><label>Leave this empty <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
                        <div class="field"><label for="name">Full Name</label><input id="name" name="name" type="text" required maxlength="100" value="<?= h($f['name']) ?>"<?= $inv('name') ?>><?= $err('name') ?></div>
                        <div class="field"><label for="phone">Phone / WhatsApp Number</label><input id="phone" name="phone" type="tel" required maxlength="20" value="<?= h($f['phone']) ?>"<?= $inv('phone') ?>><?= $err('phone') ?></div>
                        <div class="field"><label for="email">Email <span class="opt">(optional)</span></label><input id="email" name="email" type="email" maxlength="120" value="<?= h($f['email']) ?>"<?= $inv('email') ?>><?= $err('email') ?></div>
                        <div class="field"><label for="type">Inquiry Type</label>
                            <select id="type" name="type" required<?= $inv('type') ?>>
                                <option value="">Select an inquiry type</option>
                                <?php foreach ($types as $t): ?><option<?= $f['type'] === $t ? ' selected' : '' ?>><?= h($t) ?></option><?php endforeach; ?>
                            </select><?= $err('type') ?></div>
                        <div class="field"><label for="message">Message</label><textarea id="message" name="message" rows="5" required maxlength="1000"<?= $inv('message') ?>><?= h($f['message']) ?></textarea><?= $err('message') ?></div>
                        <div class="form-actions">
                            <button class="btn btn-primary" type="submit" name="action" value="send">Send Inquiry</button>
                            <button class="btn btn-outline" type="submit" name="action" value="whatsapp">Send via WhatsApp</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>
<?php site_foot(''); ?>
