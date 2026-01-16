<?php
require __DIR__ . "/inc/header.php";

if (empty($_SESSION['user_id'])) {
  echo "<p>Para ver tu perfil debes <a href=\"/coffeeshop/login.php\">iniciar sesión</a>.</p>";
  require __DIR__ . "/inc/footer.php";
  exit;
}

$stmt = $pdo->prepare("SELECT id,name,email,created_at FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

$stmt = $pdo->prepare("SELECT id,total,payment_method,status,created_at FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll();
?>

<h2 class="h-title">Mi perfil</h2>
<div class="card">
  <p><strong>Nombre:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
  <p><strong>Correo:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
  <p><small>Miembro desde: <?php echo $user['created_at']; ?></small></p>
</div>

<h3 class="h-title">Mis compras recientes</h3>
<?php if ($orders): ?>
  <?php foreach ($orders as $o): ?>
    <div class="card">
      <p>Pedido #<?php echo $o['id']; ?> — $<?php echo number_format($o['total'],2); ?> — <?php echo htmlspecialchars($o['payment_method']); ?></p>
      <p><small><?php echo $o['created_at']; ?></small></p>
    </div>
  <?php endforeach; ?>
<?php else: ?>
  <p>No hay compras registradas aún.</p>
<?php endif; ?>

<?php require __DIR__ . "/inc/footer.php"; ?>
