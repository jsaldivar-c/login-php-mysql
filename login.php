<?php session_start();

# Si el usuario tiene una sesion redirigimos al contenido, para que no pueda acceder al formulario.
if (isset($_SESSION['usuario'])) {
	header('Location: index.php');
	die();
}

# Validar si ya han sido enviado los datos.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$usuario  = filter_var(strtolower($_POST['usuario']), FILTER_SANITIZE_STRING);
	$password = $_POST['password'];
	$password = hash('sha512', $password);

	# Conexión a Base de Datos
	try {
		$conexion = new PDO('mysql:host=localhost:3306;dbname=josesald_login', 'josesc', 'qw123456');
	} catch (PDOException $e) {
		echo "Error:" . $e->getMessage();
	}

	$statement = $conexion->prepare('SELECT * FROM usuarios WHERE usuario = :usuario AND pass = :password');
	$statement->execute(array(
			':usuario' => $usuario,
			':password' => $password
		));

	$resultado = $statement->fetch();
	if ($resultado !== false) {
		$_SESSION['usuario'] = $usuario;
		header('Location: index.php');
	} else {
		$errores = '<li>Datos incorrectos</li>';
	}
}

require 'views/login.view.php';

?>