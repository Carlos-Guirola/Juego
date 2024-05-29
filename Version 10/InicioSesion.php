<?php 
include 'conexion.php';
session_start();
$mensajeError = "";
// Verificacion si se ha enviado el formulario de inicio de sesión
if(isset($_POST['Iniciar'])){
    // Traer datos del formulario
    $correo = $_POST['correo'];
    $contraseña = $_POST['password'];

    // Consultar la contraseña almacenada en la base de datos para el usuario ingresado
    $consulta = "SELECT contraseña FROM registros WHERE usuario = '$correo'";
    $resultado = mysqli_query($enlace, $consulta);

    if(mysqli_num_rows($resultado) == 1){
        // Obtener la contraseña almacenada en la base de datos
        $fila = mysqli_fetch_assoc($resultado);
        $hash_guardado = $fila['contraseña'];

        //  Aca verifico si la contraseña ingresada coincide con la contraseña en la base de datos
        if (password_verify($contraseña, $hash_guardado)) {
            // credenciales son correctas
            // Consulto toda la información del usuario
            $consulta_usuario = "SELECT * FROM registros WHERE usuario = '$correo'";
            $resultado_usuario = mysqli_query($enlace, $consulta_usuario);
            $usuario = mysqli_fetch_assoc($resultado_usuario);

            // Guardar la información del usuario para mostrar en sessiones
            $_SESSION['usuario'] = $usuario['usuario'];
            $_SESSION['puntaje'] = $usuario['puntaje'];
            $_SESSION['idRegistro'] = $usuario['idRegistro'];
            $_SESSION['spin'] = $usuario['spin'];

            // Redirigiendo al juego 
            header("Location: juego/juego.php");
            exit();
        } else {
            // Las credenciales son incorrectas
            $mensajeError = "Contraseña incorrecta.";
        }
    } else {
        // No se encontró un usuario con el correo dado
        $mensajeError = "Usuario no encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Fortuna Real!</title>
    <link rel="stylesheet" href="CSS/estiloLoginSesion.css">
</head>

<body>
    <img src="IMG/img2.1pc.png" alt="imagen Fondo de Inicio de sesión" class="ipi">
    <div class="filtro"></div>

    <div class="PaginaRegistrarse">
        <div class="FormularioDeRegistro">
            <div class="Logo">
                <img src="IMG/Logo.png" alt="Logo" class="icono">
                <h2>Iniciar Sesión</h2>
            </div>
            <form action="" name='Iniciar' method="post">
                <div class="DatosFormulario">
                    <label for="Correo">Usuario: </label>
                    <input type="text" id="Correo" name="correo" class="CampoIntroducirDatos" placeholder="Ingrese su usuario">
                </div>

                <div class="DatosFormulario">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" class="CampoIntroducirDatos" placeholder="Ingrese su contraseña">
                </div>
                <?php if(isset($mensajeError)) { ?>
                <p style="color: red;">
                    <?php echo $mensajeError; ?>
                </p>
                <?php } ?>
                <button name="Iniciar" type="submit" class="BotonDeRegistro">Iniciar Sesión</button>
            </form>
            <p>¿No tienes una cuenta? <a href="Registro.php">Registrarse</a></p>
            <p>¿Olvidaste tu contraseña? <a href="Rcontraseña/Rcpc.php">Recupérala</a></p>
        </div>
    </div>
</body>
</html>
