<?php require __DIR__ . "/inc/header.php"; ?>

<h2 class="h-title">Iniciar sesión</h2>

<form class="form" method="post" action="/coffeeshop/login.php">
  <input class="field" type="email" name="email" placeholder="Correo" required>
  <input class="field" type="password" name="password" placeholder="Contraseña" required>
  <button class="btn" type="submit">Entrar</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $pass  = $_POST['password'] ?? '';

  $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch();

  if ($user && password_verify($pass, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    header("Location: /coffeeshop/index.php"); exit;
  } else {
    echo "<p class=\"h-sub\">Datos incorrectos. Intenta otra vez.</p>";
  }
}
?>

<?php require __DIR__ . "/inc/footer.php"; ?>
