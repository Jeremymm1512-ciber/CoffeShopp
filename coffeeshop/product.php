<?php
require __DIR__ . "/inc/header.php";
$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
  echo "<p>Producto no encontrado.</p>";
  require __DIR__ . "/inc/footer.php";
  exit;
}
?>

<div class="grid" style="grid-template-columns: 1fr; max-width: 760px; margin: 0 auto;">
  <div class="card">
    <img class="product-img" src="/coffeeshop/assets/images/<?php echo htmlspecialchars($product['image'] ?: 'placeholder.jpg'); ?>" alt="">
    <h2 class="h-title"><?php echo htmlspecialchars($product['name']); ?></h2>
    <p class="h-sub"><?php echo htmlspecialchars($product['category'] ?? 'Bebida'); ?></p>
    <p><?php echo htmlspecialchars($product['description'] ?? ''); ?></p>
    <p><strong>$<?php echo number_format($product['price'], 2); ?></strong></p>

    <form method="post" action="/coffeeshop/cart.php" class="form">
      <input type="hidden" name="action" value="add">
      <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
      <label>Cantidad</label>
      <input class="field" type="number" name="qty" value="1" min="1">
      <button class="btn" type="submit">Agregar al carrito</button>
    </form>
  </div>
</div>

<?php require __DIR__ . "/inc/footer.php"; ?>
