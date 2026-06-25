<?php
session_start();

$listUsersPath = __DIR__ . '/users.txt';
$listUsers = explode("\n", file_get_contents($listUsersPath));
$listUsers = array_map('trim', $listUsers);

$listPasswordsPath = __DIR__ . '/passwords.txt';
$listPasswords = explode("\n", file_get_contents($listPasswordsPath));
$listPasswords = array_map('trim', $listPasswords);

$flagError = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $input_username = $_POST["username"];
  $input_password = $_POST["password"];
  if (in_array($input_username, $listUsers)) {
    echo "hello";
    $userIndex = array_search($input_username, $listUsers);
    if (password_verify($input_password, $listPasswords[$userIndex])) {
      $_SESSION["username"] = $_POST['username'];
      $flagError = 0;
      header("refresh:0;url=dashboard.php");
    }
  } else {
    $flagError = 1;
  }
}

?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <?php
  if ($flagError == 1) {
    echo "<div class='error'>
            Usuário ou senha inválidos!
          </div>";
  }
  ?>

  <div class="container">
    <form method="post" action="index.php">
      <div style="width: 100%;">
        <label for="InputUsername" class="form-label">Username</label>
        <input type="text" placeholder="Digite seu nome de usuário" id="InputUsername" name="username" required>
      </div>
      <div style="width: 100%;">
        <label for="InputPassword1" class="form-label">Password</label>
        <input type="password" placeholder="Digite sua senha" id="InputPassword1" name="password" required>
      </div>
      <button type="submit">Submeter</button>
    </form>
  </div>



  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"></script>
</body>

<!-- 
  lista dos utilizadores e senhas 
  admin - password1
  supervisor - password2
  user - password3
-->


</html>