<?php
include 'conexion.php';

function calcularEdad($fecha_nacimiento) {
    $hoy = new DateTime();
    $fecha_nac = new DateTime($fecha_nacimiento);
    $edad = $hoy->diff($fecha_nac);
    return $edad->y;
}

$mensaje = $mensaje2 = $mensaje3 = $mensaje4 = "";

if(isset($_POST['Registrarse'])){
    // Verificar si algún campo está vacío
    if(empty($_POST['nombres']) || empty($_POST['apellidos']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['confirm-password']) || empty($_POST['fecha'])){
        $mensaje2 = "Por favor completa todos los campos.";
    } else {
        // Todos los campos están llenos
        $nombre = $_POST['nombres'];
        $apellido = $_POST['apellidos'];
        $correo = $_POST['email'];
        $contraseña = $_POST['password'];
        $contra = $_POST['confirm-password'];
        $fecha_nacimiento = $_POST['fecha'];
        $spin=5;

        // Calcular la edad a partir de la fecha de nacimiento
        $edad = calcularEdad($fecha_nacimiento);

        // Verificar si la edad es mayor o igual a 18 años
        if($edad >= 18){
            // Verifica si las contraseñas coinciden
            if($contraseña == $contra){
                // Verifica si el correo ya está registrado
                $consultaCorreo = "SELECT * FROM registros WHERE correo = '$correo'";
                $resultado = mysqli_query($enlace, $consultaCorreo);
                if(mysqli_num_rows($resultado) > 0){
                    $mensaje3 = "Este correo electrónico ya está registrado.";
                } else {
                    // Si el checkbox de términos y condiciones está marcado
                    if(isset($_POST['acceptTerms'])) {
                        $insertarDatos = "INSERT INTO registros VALUES('', '$nombre', '$apellido', '$fecha_nacimiento',  '$correo','','$contraseña','$spin')";
                        $ejecutarInsertar = mysqli_query($enlace, $insertarDatos);
                        header("Location: juego/juego.html");
                        exit();
                    } else {
                        $mensaje = "Debes aceptar los términos y condiciones para registrarte.";
                    }
                }
            } else { 
                // Las contraseñas no coinciden
                $mensaje = "Las contraseñas no coinciden";
            }
        } else {
            // La edad es menor de 18 años
            $mensaje4 = "Debes ser mayor de 18 años para registrarte.";
        } 
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fortuna Real</title>
    <link rel="stylesheet" href="CSS/estiloLoginSesion.css">
</head>

<body>
    <img src="IMG/img2.1pc.png" alt="imagenFondo1" class="ip">
    <div class="filtro-negro"></div>

    <div class="PaginaRegistrarse">
        <div class="FormularioDeRegistro">
            <div class="Logo">
                <img src="IMG/Logo.png" alt="Logo" width="85px" height="85px">
                <h2>Regístrate</h2>
            </div>

            <form action="" name="Registro" method="post">
                <div class="DatosFormulario">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombres" class="CampoIntroducirDatos"
                        placeholder="Ingrese su nombre">
                </div>

                <div class="DatosFormulario">
                    <label for="apellidos">Apellidos:</label>
                    <input type="text" id="apellidos" name="apellidos" class="CampoIntroducirDatos"
                        placeholder="Ingrese sus apellidos">
                </div>
                <div class="DatosFormulario">
                    <label for="fecha">Fecha de nacimiento:</label>
                    <input type="date" id="feha" name="fecha" class="CampoIntroducirDatos">
                </div>

                <div class="DatosFormulario">
                    <label for="email">Correo Electrónico:</label>
                    <input type="email" id="email" name="email" class="CampoIntroducirDatos"
                        placeholder="Ingrese su correo electrónico">
                </div>

                <div class="DatosFormulario">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" class="CampoIntroducirDatos"
                        placeholder="Ingrese su contraseña">
                </div>

                <div class="DatosFormulario">
                    <label for="confirm-password">Confirmar Contraseña:</label>
                    <input type="password" id="confirm-password" name="confirm-password" class="CampoIntroducirDatos"
                        placeholder="Confirme su contraseña">
                </div>
                <?php if(isset($mensaje)) { ?>
                <p style="color: red;"><?php echo $mensaje; ?></p> <!-- Mensaje de error -->
                <?php } ?>
                <?php if(isset($mensaje2)) { ?>
                <p style="color: red;"><?php echo $mensaje2; ?></p> <!-- Mensaje de error -->
                <?php } ?>
                <?php if(isset($mensaje3)) { ?>
                <p style="color: red;"><?php echo $mensaje3; ?></p> <!-- Mensaje de error -->
                <?php } ?>
                <?php if(isset($mensaje4)) { ?>
                <p style="color: red;"><?php echo $mensaje4; ?></p> <!-- Mensaje de error -->
                <?php } ?>
                <a onclick="openModal()">Términos y Condiciones</a>
                <div class="terminos-container">
    <input type="checkbox" id="acceptTerms" name="acceptTerms">
    <label for="acceptTerms" class="terminos">Acepto los términos y condiciones</label>
</div>

                

                <div id="myModal" class="modal">
                    <div class="modal-content">
                        <span class="close" onclick="closeModal()">&times;</span>
                        <h3>Términos y Condiciones</h3>
                        <p>-Para participar en Fortuna Real, debes ser mayor de edad según las leyes. <br>
                            -Nos reservamos el derecho de solicitar pruebas de edad y de restringir el acceso al juego a
                            cualquier persona que no cumpla con este requisito. <br>
                            -Para jugar en Fortuna Real, debes registrarte proporcionando información precisa y real.
                            <br>
                            -Solo se permite una cuenta por usuario. <br>
                            -Después del registro, cada usuario tiene un límite de 5 intentos para jugar. <br>
                            -Una vez agotados los 5 intentos, el usuario no podrá participar más en el juego a menos que
                            se especifique lo contrario por parte de la administración del juego. <br>
                            -La participación en Fortuna Real es voluntaria y bajo la responsabilidad del usuario. <br>
                            -La administración del juego no se hace responsable de cualquier pérdida o daño derivado del
                            uso del juego. <br>
                            -Nos reservamos el derecho de suspender o cancelar el acceso al juego en caso de sospecha de
                            fraude, abuso o incumplimiento de estos términos y condiciones o informacion compartida
                            falsa. <br>
                            -Nos reservamos el derecho de modificar estos términos y condiciones en cualquier momento y
                            sin previo aviso.
                            -Se te anima a revisar periódicamente estos términos para estar al tanto de cualquier
                            cambio. <br>
                            -Al registrarte y jugar a "Fortuna Real", aceptas los términos y condiciones establecidos
                            anteriormente.
                            -Si tienes alguna pregunta o inquietud sobre estos términos, por favor contáctanos a través
                            de: <br>
                            soporteTecnicoRealFortuna@gmail.com
                            Validad desde el 15 junio 2024
                        </p>
                    </div>
                </div>
                <button type="submit" class="BotonDeRegistro" name="Registrarse">Registrarse</button>

            </form>
            <p>¿Ya tienes una cuenta? <a href="InicioSesion.php">Iniciar Sesión</a></p>
        </div>
    </div>
    <script src="alertas.js"></script>
</body>

</html>