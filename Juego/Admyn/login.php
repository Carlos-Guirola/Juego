<?php
session_start();
include '../conexion.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM usuarios WHERE username = '$username' AND password = '$password' AND role = 'admin'";
    $result = $enlace->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'admin';
        header('Location: admin.php');
        exit();
    } else {
        $error_message = 'Usuario o contraseña incorrectos';
    }
}
$logged_in = isset($_SESSION['username']) && $_SESSION['role'] === 'admin';
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../CSS/estiloLoginSesion.css">
<link rel="icon" type="img/png" href="../IMG/Logo.png">
<title>Fortuna Real</title>
</head>
<body>
    <img src="../IMG/img2.1pc.png" alt="imagen Fondo de Inicio de sesion" class="ipi">
    <div class="filtro"></div>

    <div class="PaginaRegistrarse">
        <div class="FormularioDeRegistro">
            <div class="Logo">
                <img src="../IMG/Logo.png" alt="Logo" width="85px" height="85px">
                <h2>Iniciar Sesión como Administrador</h2>
            </div>
            <?php if (isset($error_message)): ?>
                <p style="color: red;"><?= $error_message ?></p>
            <?php endif; ?>
            <form method="post">
                <div class="DatosFormulario">
                    <label for="username">Usuario:</label>
                    <input type="text"  class="CampoIntroducirDatos" id="username" name="username" required>
                </div>
                <div class="DatosFormulario">
                    <label for="password">Contraseña:</label>
                    <input type="password" class="CampoIntroducirDatos" id="password" name="password" required>
                </div>
                <button type="submit" name="login" class="BotonDeRegistro">Iniciar Sesión</button>
            </form>
        </div>
    </div>
</body>
</html>


