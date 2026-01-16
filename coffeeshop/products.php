<?php
require __DIR__ . "/inc/header.php";

$q = trim($_GET['q'] ?? '');
if ($q !== '') {
  $like = "%$q%";
  $stmt = $pdo->prepare("SELECT * FROM products
                         WHERE name LIKE ? OR description LIKE ? OR category LIKE ?
                         ORDER BY created_at DESC");
  $stmt->execute([$like,$like,$like]);
} else {
  $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
}
$products = $stmt->fetchAll();
?>

<h2 class="h-title"><?php echo $q ? "Resultados para: ".htmlspecialchars($q) : "Todas las bebidas"; ?></h2>

<div class="grid">
  <?php foreach ($products as $p): ?>
    <div class="card">
      <img class="product-img" src="/coffeeshop/assets/images/<?php echo htmlspecialchars($p['image'] ?: 'placeholder.jpg'); ?>" alt="">
      <h3><?php echo htmlspecialchars($p['name']); ?></h3>
      <p><?php echo htmlspecialchars($p['category'] ?? 'Bebida'); ?></p>
      <p><strong>$<?php echo number_format($p['price'],2); ?></strong></p>
      <a class="btn" href="/coffeeshop/product.php?id=<?php echo $p['id']; ?>">Ver</a>
    </div>
  <?php endforeach; ?>
</div>

<?php require __DIR__ . "/inc/footer.php"; ?>
