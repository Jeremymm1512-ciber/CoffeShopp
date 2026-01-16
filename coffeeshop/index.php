<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<?php require __DIR__ . "/inc/header.php"; ?>

<section class="hero">
  <h1 class="h-title">Coffee Shop</h1>
  <p class="h-sub">Descubre bebidas calientes y frías con un estilo café-crema: suaves, aromáticas y listas para ti.</p>
</section>

<section>
  <h2 class="h-title">Bebidas destacadas</h2>
  <?php
    $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC LIMIT 4");
    $items = $stmt->fetchAll();
  ?>
  <div class="grid">
    <?php foreach ($items as $it): ?>
      <div class="card">
        <img class="product-img" src="/coffeeshop/assets/images/<?php echo htmlspecialchars($it['image'] ?: 'placeholder.jpg'); ?>" alt="">
        <h3><?php echo htmlspecialchars($it['name']); ?></h3>
        <p><?php echo htmlspecialchars($it['description'] ?? ''); ?></p>
        <p><strong>$<?php echo number_format($it['price'], 2); ?></strong></p>
        <a class="btn" href="/coffeeshop/product.php?id=<?php echo $it['id']; ?>">Ver</a>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php require __DIR__ . "/inc/footer.php"; ?>
