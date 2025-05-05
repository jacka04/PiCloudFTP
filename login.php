<?php
session_start();
require_once "conexiondb.php"; 

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    if (!empty($username) && !empty($password)) {
        $sql = "SELECT id, username, password FROM usuarios WHERE username = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($id, $db_username, $db_password);
                $stmt->fetch();

                if (password_verify($password, $db_password)) {
                    $_SESSION["loggedin"] = true;
                    $_SESSION["username"] = $db_username;
                    
                    header("Location: pagina_ftp.php");
                    exit();
                } else {
                    $message = "Contraseña incorrecta.";
                }
            } else {
                $message = "El nombre de usuario no existe.";
            }
            $stmt->close();
        }
    } else {
        $message = "Por favor, completa todos los campos.";
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

<nav class="navbar">
    <div class="navbar-left">
        <a href="index.php">Inicio</a>
        <a href="quiensomos.php">¿Qué Hacemos?</a>
    </div>
    <div class="navbar-right">
        <a href="login.php" class="active">Iniciar Sesión</a>
        <a href="registro.php">Registrarse</a>
    </div>
</nav>

<div class="container">
    <div class="left-panel">
        <h2>Iniciar Sesió</h2>
        <p>Accede a tu cuenta.</p>

        <?php if (!empty($message)) { echo "<p class='error'>$message</p>"; } ?>

        <form action="login.php" method="POST">
            <input type="text" name="username" placeholder="Nombre de usuario" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Iniciar Sesión</button>
            <a href="registro.php">¿No tens conta? Crea una ara!</a>
        </form>
    </div>
</div>

</body>
</html>