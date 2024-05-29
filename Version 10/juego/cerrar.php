<?php
// Iniciar la sesión si no está iniciada
session_start();
session_destroy();
header("Location: ../index.html"); 
?>
