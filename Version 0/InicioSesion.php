<?php 
 include 'conexion.php';
?>

<?php 
if(isset($_POST['Iniciar'])){
    $correo = $_POST['correo'];
    $contraseña = $_POST['password'];

    // Verificar las credenciales en la base de datos
    $consulta = "SELECT * FROM registros WHERE correo = '$correo' AND contraseña = '$contraseña'";
    $resultado = mysqli_query($enlace, $consulta);

    if(mysqli_num_rows($resultado) == 1){
        // Usuario autenticado correctamente, puedes redirigir o realizar otras acciones
        // Por ejemplo, redireccionar a una página de inicio de sesión exitosa
        header("Location: juego/juego.html");
        exit(); // Importante: detener la ejecución del script después de redirigir
    } else {
        // Las credenciales son incorrectas
        $mensajeError = "Correo electrónico o contraseña incorrectos. Por favor, inténtalo de nuevo.";
    }
}
?> 
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>!Fortuna Real¡</title>
  <link rel="stylesheet" href="CSS/estiloLoginSesion.css">
</head>
<body>
    <img src="IMG/img2.1pc.png" alt="imagen Fondo de Inicio de sesion" class="ipi">
    <div class="filtro"></div>

    <div class="PaginaRegistrarse">
        <div class="FormularioDeRegistro">
        <div class="Logo">
            <img src="IMG/Logo.png" alt="Logo" width="85px" height="85px">
             <h2>Iniciar Sessión</h2>
        </div>
            <form action="" name='Iniciar' method="post">
            <div class="DatosFormulario">
            <label for="Correo">Correo:</label>
            <input type="email" id="Correo" name="correo" class="CampoIntroducirDatos" placeholder="Ingrese su Correo">
            </div>
    
            <div class="DatosFormulario">
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" class="CampoIntroducirDatos" placeholder="Ingrese su contraseña">
            </div>
            <?php if(isset($mensajeError)) { ?>
                <p style="color: red;"><?php echo $mensajeError; ?></p> <!-- Mensaje de error -->
            <?php }
             ?>
            <button name = "Iniciar" type="submit" class="BotonDeRegistro">Iniciar Sessión</button>
            
        </form>
        <p>¿No tienes una cuenta? <a href="Registro.php">Registrarse</a></p>
        <p>¿Olvidastes tu contraseña? <a href="Rcpc.html">Recuperala</a></p>
        </div>
    </div>
</body>
</html>