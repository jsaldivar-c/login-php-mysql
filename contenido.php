<?php session_start();

# Se valida si el usuario esta en sesión.
if (isset($_SESSION['usuario'])) {
	require 'views/contenido.view.php';
} else {
	header('Location: login.php');
	die();
}

?>