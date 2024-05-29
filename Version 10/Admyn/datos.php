<?php
include '../conexion.php'; // Asegúrate de que este archivo contiene la conexión a la base de datos
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Obtener datos de la base de datos y devolverlos como JSON
    $sql = "SELECT idRegistro, eda, usuario, puntaje, spin FROM registros ORDER BY puntaje DESC";
    $result = $enlace->query($sql);
    $usuarios = [];

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $usuarios[] = $row;
        }
    }

    echo json_encode($usuarios);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos enviados desde el cliente
    $datos = json_decode(file_get_contents('php://input'), true);
    $idRegistro = $datos['idRegistro']; // Aquí añadimos esta línea para obtener el idRegistro
    $usuario = $datos['usuario'];
    $eda = $datos['eda'];
    $puntaje = $datos['puntaje'];
    $spin = $datos['spin'];

    // Preparar la consulta de actualización
    $sql = "UPDATE registros SET 
                eda = '$eda', 
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
