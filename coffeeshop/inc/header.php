<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . "/db.php";
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Coffee Shop</title>
  <link rel="stylesheet" href="/coffeeshop/assets/style.css">
</head>
<body>
<header class="topbar">
  <div class="wrap">
    <a class="brand" href="/coffeeshop/index.php">Coffee Shop</a>

    <form class="search" action="/coffeeshop/products.php" method="get">
      <input type="text" name="q" placeholder="Buscar bebidas..." value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
      <button type="submit">Buscar</button>
    </form>

    <nav class="nav">
      <a href="/coffeeshop/index.php">Inicio</a>
      <a href="/coffeeshop/products.php">Bebidas</a>
      <a href="/coffeeshop/cart.php">Carrito (<?php echo isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; ?>)</a>

      <?php if (!empty($_SESSION['user_id'])): ?>
        <a href="/coffeeshop/profile.php">Perfil</a>
        <a href="/coffeeshop/logout.php">Salir</a>
      <?php else: ?>
        <a href="/coffeeshop/login.php">Login</a>
        <a href="/coffeeshop/register.php">Registro</a>
      <?php endif; ?>
    </nav>
  </div>
</header>

<main class="container">
