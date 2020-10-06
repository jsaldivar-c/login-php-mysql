<?php session_start();

	if (isset($_SESSION['usuario'])) {
		header('Location: index.php');
	}
	# Obtenemos las variables del formulario
	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
		$usuario   = $_POST['usuario'];
		$password  = $_POST['password'];
		$password2 = $_POST['password2'];

		$errores = '';
		# Validamos que las variables no eesten vacias
		if(empty($usuario) or empty($password) or empty($password2)){
			$errores .= '<li>Por favor rellena todos los datos correctamente</li>';
		}
		else{
			try{
				# Si las variables no estan vacias realizamos la conexion a la BD.
				$conexion = new PDO('mysql:host=localhost:3306;dbname=josesald_login', 'josesc', 'qw123456');
			}
			catch(PDOException $e){
				echo "Error: " . $e->getMessage();
			}
			# Validamos que el usuario no exista
			$statement = $conexion->prepare('SELECT * FROM usuarios WHERE usuario = :usuario LIMIT 1');
			$statement->execute(array(':usuario' => $usuario));
			$resultado = $statement->fetch();
			# Si el usuario existe se envia un mensaje
			if($resultado != false){
				$errores .= '<li>El nombre de usuario ya existe</li>';
			}
			# Encriptamos el password
			$password = hash('sha512', $password);
			$password2 = hash('sha512', $password2);

			# Validamos que los 2 passwords sean iguales
			if($password != $password2){
				$errores .= '<li>Las contraseñas no son iguales</<li>';
			}
		}
		# Si no hay ningun error realizamos la inserción del usuario.
		if($errores == ''){
			$statement = $conexion->prepare('INSERT INTO usuarios (id,usuario,pass) VALUES (null,:usuario,:password)');
			$statement->execute(array(
				':usuario' => $usuario,
				':password' => $password
			));
			# Una vez que se inserta el registro nos envia al Login.
			header('Location: login.php');
		}
	}
	require 'views/registrate.view.php';
?>