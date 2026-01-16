<?php
require __DIR__ . "/inc/header.php";

if (empty($_SESSION['cart'])) {
  echo "<p>Tu carrito está vacío. Ve a <a href=\"/coffeeshop/products.php\">Bebidas</a>.</p>";
  require __DIR__ . "/inc/footer.php";
  exit;
}

if (empty($_SESSION['user_id'])) {
  echo "<p>Para finalizar la compra debes <a href=\"/coffeeshop/login.php\">iniciar sesión</a>.</p>";
  require __DIR__ . "/inc/footer.php";
  exit;
}

$ids = array_keys($_SESSION['cart']);
$in  = implode(',', array_fill(0, count($ids), '?'));
$stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($in)");
$stmt->execute($ids);
$prods = $stmt->fetchAll();

$total = 0;
foreach ($prods as $p) { $total += $p['price'] * $_SESSION['cart'][$p['id']]; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $payment_method = $_POST['payment_method'];
  $customer_name = $_POST['customer_name'];
$table_number  = $_POST['table_number'];

  $method = $_POST['payment_method'] ?? 'tarjeta';

  $pdo->beginTransaction();
  try {
   $stmt = $pdo->prepare("
  INSERT INTO orders 
  (user_id, total, payment_method, status, customer_name, table_number)
  VALUES (?, ?, ?, 'pagado', ?, ?)
");

$stmt->execute([
  $_SESSION['user_id'],
  $total,
  $payment_method,
  $customer_name,
  $table_number
]);

$order_id = $pdo->lastInsertId();

/* AQUÍ VA EL echo */
echo "
<div class='card'>
  <h2 class='h-title'>☕ Pedido confirmado</h2>
  <p><strong>Número de pedido:</strong> #$order_id</p>
  <p><strong>Cliente:</strong> $customer_name</p>
  <p><strong>Mesa:</strong> $table_number</p>
  <p><strong>Total:</strong> $" . number_format($total,2) . "</p>
</div>
";


    $ins = $pdo->prepare("INSERT INTO order_items (order_id,product_id,qty,price) VALUES (?,?,?,?)");
    foreach ($prods as $p) {
      $qty = $_SESSION['cart'][$p['id']];
      $ins->execute([$order_id, $p['id'], $qty, $p['price']]);
    }

    $pdo->commit();
    $_SESSION['cart'] = [];
    echo "<p class=\"h-title\">¡Pago registrado! Orden #$order_id — Total: $" . number_format($total,2) . "</p>";
    echo "<p><a class=\"btn\" href=\"/coffeeshop/index.php\">Volver al inicio</a></p>";
    require __DIR__ . "/inc/footer.php";
    exit;
  } catch (Exception $e) {
    $pdo->rollBack();
    echo "<p>Hubo un problema al registrar tu pedido.</p>";
  }
}
?>

<h2 class="h-title">Checkout (pago simulado)</h2>
<p><strong>Total:</strong> $<?php echo number_format($total,2); ?></p>

<form class="form" method="post">
  <label>Método de pago</label>
  <select class="field" name="payment_method" required>
    <option value="tarjeta">Tarjeta (simulado)</option>
    <option value="efectivo">Efectivo</option>
    <option value="transferencia">Transferencia</option>
  </select>
    <label>Nombre del cliente</label>
<input class="field" type="text" name="customer_name" placeholder="Ej. Ana López" required>

<label>Número de mesa</label>
<input class="field" type="number" name="table_number" min="1" required>
  <button class="btn" type="submit">Confirmar compra</button>
</form>

<?php require __DIR__ . "/inc/footer.php"; ?>
