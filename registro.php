<?php
session_start();
require_once "conexiondb.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Formato de correo inválido.";
    } elseif (strlen($password) < 6) {
        $message = "La contraseña debe tener al menos 6 caracteres.";
    } else {
        $sql_check = "SELECT id FROM usuarios WHERE username = ? OR email = ?";
        if ($stmt_check = $conn->prepare($sql_check)) {
            $stmt_check->bind_param("ss", $username, $email);
            $stmt_check->execute();
            $stmt_check->store_result();

            if ($stmt_check->num_rows > 0) {
                $message = "El nombre de usuario o correo ya están registrados.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $role_id = 1; 
                $sql_insert = "INSERT INTO usuarios (username, email, password, role_id) VALUES (?, ?, ?, ?)";
                if ($stmt_insert = $conn->prepare($sql_insert)) {
                    $stmt_insert->bind_param("sssi", $username, $email, $hashed_password, $role_id);

                    if ($stmt_insert->execute()) {
                        $_SESSION['success'] = "El usuario se ha creado correctamente.";
                        header("Location: login.php");
                        exit();
                    } else {
                        $message = "Error al crear el usuario. Inténtalo nuevamente.";
                    }
                    $stmt_insert->close();
                }
            }
            $stmt_check->close();
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Servidor FTP</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

<nav class="navbar">
    <div class="navbar-left">
        <a href="index.php">Inicio</a>
        <a href="quiensomos.php">¿Qué Hacemos?</a>
    </div>
    <div class="navbar-right">
        <a href="login.php">Iniciar Sesión</a>
        <a href="registro.php" class="active">Registrarse</a>
    </div>
</nav>

<div class="container">
    <div class="left-panel">
        <h2>Registrarse</h2>
        <p>¡Crea tu cuenta para acceder a los servicios!</p>

        <?php if (!empty($message)) { echo "<p class='error'>$message</p>"; } ?>

        <form action="registro.php" method="POST">
            <input type="text" name="username" placeholder="Nombre de usuario" required>
            <input type="email" name="email" placeholder="Correo electrónico" required>
            <input type="password" name="password" placeholder="Contraseña (mín. 6 caracteres)" required>
            <button type="submit">Registrarse</button>
            <a href="login.php">¿Ya tienes cuenta? Inicia sesión.</a>
        </form>
    </div>
</div>

</body>
</html>
