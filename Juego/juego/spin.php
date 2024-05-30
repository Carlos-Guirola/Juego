<?php
session_start();
include 'conexion.php';
if (!isset($_SESSION['idRegistro']) || empty($_SESSION['idRegistro'])) {
    echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión para continuar']);
    exit();
}
$usuario = $_SESSION['idRegistro'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'spin') {
    $sql = "SELECT spin FROM registros WHERE idRegistro = $usuario";
    $result = $enlace->query($sql);
    if ($result) {
        $row = $result->fetch_assoc();
        $_SESSION['spin'] = $row['spin'];
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al obtener los intentos del usuario']);
        exit();
    }

    if ($_SESSION['spin'] > 0) {
        $_SESSION['spin']--;
        $sql = "UPDATE registros SET spin = spin - 1 WHERE idRegistro = $usuario";
        if ($enlace->query($sql) !== TRUE) {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar los intentos: ' . $enlace->error]);
            exit();
        }
        echo json_encode(['success' => true, 'spin' => $_SESSION['spin']]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No te quedan más intentos el juego a terminado.']);
    }
}
?>


