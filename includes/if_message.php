<?php if (isset($_GET['error']) && !empty($_GET['error'])): ?>
    <div class="alert alert-danger" role="alert"><?= htmlspecialchars($_GET['error']) ?></div>
<?php endif; ?>
<?php if (isset($_GET['success']) && !empty($_GET['success'])): ?>
    <div class="alert alert-success" role="alert"><?= htmlspecialchars($_GET['success']) ?></div>
<?php endif; ?>