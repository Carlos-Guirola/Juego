<?php
include '../conexion.php';

$correo_error = $password_error = $general_error = "";

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar que los campos no estén vacíos
    if (empty($_POST["correo"])) {
        $correo_error = "El campo de usuario es obligatorio.";
    } elseif (empty($_POST['password']) || empty($_POST['confirm-password'])) {
        $password_error = "Ambos campos de contraseña son obligatorios.";
    } else {
        // Verificar si se ha enviado un correo válido
        if (filter_var($_POST["correo"])) {
            $correo = $_POST["correo"];
            $sql = "SELECT * FROM registros WHERE usuario = '$correo'";
            $result = $enlace->query($sql);

            if ($result->num_rows > 0) {
                // Si el correo existe en la base de datos, verificar las contraseñas
                if ($_POST['password'] === $_POST['confirm-password']) {
                    $nueva_contraseña = $_POST['password'];
                    $hash_nueva_contraseña = password_hash($nueva_contraseña, PASSWORD_DEFAULT);

                    // Actualizar la contraseña en la base de datos
                    $sql_update = "UPDATE registros SET contraseña = '$hash_nueva_contraseña' WHERE usuario = '$correo'";
                    if ($enlace->query($sql_update) === TRUE) {
                        // Contraseña actualizada correctamente, mostrar alerta y redirigir
                        echo "<script>
                                alert('Contraseña actualizada correctamente.');
                                window.location.href = '../index.html';
                              </script>";
                        exit();
                    } else {
                        // Error al actualizar la contraseña
                        $general_error = "Error al actualizar la contraseña.";
                    }
                } else {
                    // Las contraseñas no coinciden
                    $password_error = "Las contraseñas no coinciden.";
                }
            } else {
                // El correo no existe en la base de datos
                $correo_error = "El usuario no existe en la base de datos.";
            }
        } 
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fortuna Real - Recuperar contraseña</title>
    <link rel="stylesheet" href="../CSS/estiloLoginSesion.css">
</head>
<body>
    <img src="../IMG/img2.1pc.png" alt="imagen Fondo de Inicio de sesión" class="ipi">
    <div class="filtro"></div>

    <div class="PaginaRegistrarse">
        <div class="FormularioDeRegistro">
            <div class="Logo">
                <img src="../IMG/Logo.png" alt="Logo" class="icono">
                <h2>Recuperar contraseña</h2>
            </div>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" name='Recuperar' method="post">
                <div class="DatosFormulario">
                    <label for="Correo">Usuario:</label>
                    <input type="text" id="Correo" name="correo" class="CampoIntroducirDatos" placeholder="Ingrese su usuario a recuperar">
                </div>
                <div class="DatosFormulario">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" class="CampoIntroducirDatos" placeholder="Ingrese su nueva contraseña">
                </div>
                <div class="DatosFormulario">
                    <label for="confirm-password">Confirmar Contraseña:</label>
                    <input type="password" id="confirm-password" name="confirm-password" class="CampoIntroducirDatos" placeholder="Confirme su contraseña">
                    <?php if ($password_error) { ?>
                    <p class="error-message"><?php echo $password_error; ?></p>
                    <?php } ?>
                </div>
                <?php if ($correo_error) { ?>
                    <p class="error-message"><?php echo $correo_error; ?></p>
                    <?php } ?>
                <?php if ($general_error) { ?>
                <p class="error-message"><?php echo $general_error; ?></p>
                <?php } ?>
                <button name="Recuperar" type="submit" class="BotonDeRegistro">Recuperar</button>
            </form>
            <p><a href="../InicioSesion.php">Iniciar Sesión</a></p>
        </div>
    </div>
    <style>
        .error-message {
            color: red;
        }
    </style>
</body>
</html>
