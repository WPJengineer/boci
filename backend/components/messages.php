<?php if (!empty($_SESSION['success'])): ?>
  <div class="message success">
    <?= htmlspecialchars($_SESSION['success']) ?>
  </div>
  <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['error'])): ?>
  <div class="message error">
    <?= htmlspecialchars($_SESSION['error']) ?>
  </div>
  <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['warning'])): ?>
  <div class="message warning">
    <?= htmlspecialchars($_SESSION['warning']) ?>
  </div>
  <?php unset($_SESSION['warning']); ?>
<?php endif; ?>