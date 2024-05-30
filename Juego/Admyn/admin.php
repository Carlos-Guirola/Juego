<?php 
session_start();
include '../conexion.php';
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
  header('Location: login.php');
  exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../CSS/styletable.css">
<link rel="icon" type="img/png" href="../IMG/Logo.png">
<title>Fortuna Real</title>
</head>
<body>
<h2>Tabla de Usuarios</h2>
<table id="tablaAdmyn">
  <tr>
    <th>Id Registro</th>
    <th>Usuarios</th>
    <th>Fecha Nac.</th>
    <th>Puntaje</th>
    <th>Spin</th>
    <td><button><a href="../juego/cerrar.php">Cerrar</a></button></td>
  </tr>
</table>

<script>
document.addEventListener('DOMContentLoaded', function() {
  fetch('datos.php')
    .then(response => response.json())
    .then(data => {
      const tabla = document.getElementById('tablaAdmyn');
      data.forEach(fila => {
        const tr = document.createElement('tr');
        const tdId = document.createElement('td');
        tdId.textContent = fila.idRegistro;

        const tdUsuario = document.createElement('td');
        const inputUsuario = document.createElement('input');
        inputUsuario.type = 'text';
        inputUsuario.value = fila.usuario;
        tdUsuario.appendChild(inputUsuario);

        
        const tdEdad = document.createElement('td');
        const inputEdad = document.createElement('input');
        inputEdad.type = 'date';
        inputEdad.value = fila.edad;
        tdEdad.appendChild(inputEdad);;

        const tdPuntaje = document.createElement('td');
        const inputPuntaje = document.createElement('input');
        inputPuntaje.type = 'number';
        inputPuntaje.value = fila.puntaje;
        tdPuntaje.appendChild(inputPuntaje);
        
        const tdSpin = document.createElement('td');
        const inputSpin = document.createElement('input');
        inputSpin.type = 'number';
        inputSpin.value = fila.spin;
        tdSpin.appendChild(inputSpin);

        const tdAcciones = document.createElement('td');
        const botonGuardar = document.createElement('button');
        botonGuardar.textContent = 'Guardar';
        botonGuardar.addEventListener('click', () => {
          const datosActualizados = {
            idRegistro: fila.idRegistro,
            usuario: inputUsuario.value,
            eda: inputEdad.value,
            puntaje: inputPuntaje.value,
            spin: inputSpin.value
          };
          guardarCambios(datosActualizados);
        });
        tdAcciones.appendChild(botonGuardar);
        tr.appendChild(tdId);
        tr.appendChild(tdUsuario)
        tr.appendChild(tdEdad);;
        tr.appendChild(tdPuntaje);
        tr.appendChild(tdSpin);
        tr.appendChild(tdAcciones);
        tabla.appendChild(tr);
      });
    })
    .catch(error => console.error('Error:', error));
});

function guardarCambios(datos) {
  fetch('datos.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(datos)
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert('Datos actualizados correctamente');
    } else {
      alert('Error al actualizar los datos');
    }
  })
  .catch(error => console.error('Error:', error));
}
</script>
</body>
</html>



