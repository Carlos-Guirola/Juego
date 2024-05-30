<?php
include '../conexion.php';
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT idRegistro, edad, usuario, puntaje, spin FROM registros ORDER BY puntaje DESC";
    $result = $enlace->query($sql);
    $usuarios = [];

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $usuarios[] = $row;
        }
    }

    echo json_encode($usuarios);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = json_decode(file_get_contents('php://input'), true);
    $idRegistro = $datos['idRegistro']; 
    $usuario = $datos['usuario'];
    $edad = $datos['edad'];
    $puntaje = $datos['puntaje'];
    $spin = $datos['spin'];
    $sql = "UPDATE registros SET 
                edad = '$edad', 
                usuario = '$usuario', 
                puntaje = $puntaje, 
                spin = $spin 
            WHERE idRegistro = $idRegistro";

    if ($enlace->query($sql) === TRUE) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar los datos: ' . $enlace->error]);
    }
}

$enlace->close();
?>
