<?php 
session_start();
include 'conexion.php';
if (!isset($_SESSION['idRegistro']) || empty($_SESSION['idRegistro'])) {
    echo 'Debes <a href="../InicioSesion.php">iniciar sesión</a> para continuar';
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Fortuna Real</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link rel="stylesheet" href="estiloJuego.css">
    <link rel="icon" type="img/png" href="../IMG/Logo.png">
</head>
<header class="hear">
    <div class="coins">Puntaje: <?php echo $_SESSION['puntaje']; ?></div>
    <div class="usuario"><?php echo $_SESSION['usuario']; ?></div>
    <h1>FORTUNA REAL</h1>
    <a href="Tabla de puntos.html">
        <div class="tabla">Tabla</div>
    </a>
    <form action="cerrar.php" method="post">
        <button type="submit" class="salir">Cerrar Sesión</button>
    </form>
</header>
<body>
    <div class="tragamonedas">
        <div class="carretes"></div>
        <div class="carretes"></div>
        <div class="carretes"></div>
        <div class="carretes"></div>
        <div class="center"></div>
    </div>
    <div id="depurador" class="depurador"></div>
    <footer>
    <form id="spinForm" method="post" action="spin.php">
        <button id="botonJugar" type="button">SPIN...</button>
        <input type="hidden" name="action" value="spin">
    </form>
        <div class="win">
            <h2 id="tx">TOTAL WIN: 0</h2>
        </div>
        <div class="canspin">Spins: <span id="spinCount"><?php echo $_SESSION['spin']; ?></span></div>
    </footer>
    <script src="script.js"></script>
</body>
</html>
