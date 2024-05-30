<?php
session_start();
include 'conexion.php';
if (!isset($_SESSION['idRegistro']) || empty($_SESSION['idRegistro'])) {
    echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión para continuar']);
    exit();
}
$usuario = $_SESSION['idRegistro'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'increment_score' && isset($_POST['points'])) {
    $points = intval($_POST['points']);
    $stmt = $enlace->prepare("UPDATE registros SET puntaje = puntaje + ? WHERE idRegistro = ?");
    $stmt->bind_param("ii", $points, $usuario);
    if ($stmt->execute()) {
        $stmt = $enlace->prepare("SELECT puntaje FROM registros WHERE idRegistro = ?");
        $stmt->bind_param("i", $usuario);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $_SESSION['puntaje'] = $row['puntaje'];
                echo json_encode(['success' => true, 'new_score' => $_SESSION['puntaje']]);
            } else {
                echo json_encode(['success' => false, 'message' => 'No se encontró el usuario.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al obtener el nuevo puntaje: ' . $enlace->error]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar el puntaje: ' . $enlace->error]);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Solicitud inválida']);
}
?>
