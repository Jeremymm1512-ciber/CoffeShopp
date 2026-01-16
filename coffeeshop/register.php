<?php require __DIR__ . "/inc/header.php"; ?>

<h2 class="h-title">Registro</h2>

<form class="form" method="post" action="/coffeeshop/register.php">
  <input class="field" name="name" placeholder="Nombre" required>
  <input class="field" type="email" name="email" placeholder="Correo" required>
  <input class="field" type="password" name="password" placeholder="Contraseña" required>
  <button class="btn" type="submit">Crear cuenta</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $pass = $_POST['password'] ?? '';

  if ($name && $email && $pass) {
    $hash = password_hash($pass, PASSWORD_DEFAULT);
    try {
      $stmt = $pdo->prepare("INSERT INTO users (name,email,password) VALUES (?,?,?)");
      $stmt->execute([$name,$email,$hash]);
      echo "<p class=\"h-sub\">Registro exitoso. <a href=\"/coffeeshop/login.php\">Inicia sesión</a>.</p>";
    } catch (PDOException $e) {
      echo "<p class=\"h-sub\">Este correo ya está registrado o hubo un error.</p>";
    }
  }
}
?>

<?php require __DIR__ . "/inc/footer.php"; ?>
