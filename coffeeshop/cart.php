<?php
require __DIR__ . "/inc/header.php";

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

// acciones: add / remove / clear
$action = $_POST['action'] ?? $_GET['action'] ?? null;

if ($action === 'add') {
  $pid = (int)($_POST['product_id'] ?? 0);
  $qty = max(1, (int)($_POST['qty'] ?? 1));
  if ($pid > 0) {
    $_SESSION['cart'][$pid] = ($_SESSION['cart'][$pid] ?? 0) + $qty;
  }
  header("Location: /coffeeshop/cart.php"); exit;
}

if ($action === 'remove') {
  $pid = (int)($_GET['id'] ?? 0);
  unset($_SESSION['cart'][$pid]);
  header("Location: /coffeeshop/cart.php"); exit;
}

if ($action === 'clear') {
  $_SESSION['cart'] = [];
  header("Location: /coffeeshop/cart.php"); exit;
}

$items = [];
$total = 0;

if (!empty($_SESSION['cart'])) {
  $ids = array_keys($_SESSION['cart']);
  $in  = implode(',', array_fill(0, count($ids), '?'));
  $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($in)");
  $stmt->execute($ids);
  $prods = $stmt->fetchAll();

  foreach ($prods as $p) {
    $qty = $_SESSION['cart'][$p['id']];
    $sub = $p['price'] * $qty;
    $total += $sub;
    $items[] = ['p'=>$p,'qty'=>$qty,'sub'=>$sub];
  }
}
?>

<h2 class="h-title">Carrito</h2>

<?php if (empty($items)): ?>
  <p>Tu carrito está vacío. Agrega bebidas desde <a href="/coffeeshop/products.php">Bebidas</a>.</p>
<?php else: ?>
  <table class="table">
    <thead>
      <tr><th>Producto</th><th>Cantidad</th><th>Subtotal</th><th></th></tr>
    </thead>
    <tbody>
      <?php foreach ($items as $it): ?>
        <tr>
          <td><?php echo htmlspecialchars($it['p']['name']); ?></td>
          <td><?php echo $it['qty']; ?></td>
          <td>$<?php echo number_format($it['sub'],2); ?></td>
          <td><a class="btn" href="cart.php?action=remove&id=<?php echo $it['p']['id']; ?>">Quitar</a></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <p><strong>Total: $<?php echo number_format($total, 2); ?></strong></p>
  <a class="btn" href="/coffeeshop/checkout.php">Ir a pagar</a>
  <a class="btn" href="cart.php?action=clear">Vaciar</a>
<?php endif; ?>

<?php require __DIR__ . "/inc/footer.php"; ?>
