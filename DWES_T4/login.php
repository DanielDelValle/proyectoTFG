<?php
session_start();
if (isset($_POST["entrar"])) {
	//Procesar formulario
	//Obtener contraseña codificada de bd, por ejemplo:
	$user = "foc";
	$pass = crypt('Fdwes!22', '$1$somethin$');
	//&pass = md5('Fdwes!22')
	$mensaje = "";
	//Comprobar que el nombre y contrasena no están vacíos
	if (isset($_POST["usuario"])) $usuario = $_POST["usuario"];
	else $usuario = "";

	if (isset($_POST["contrasena"])) $contrasena = $_POST["contrasena"];
	else $contrasena = "";

	if ($usuario != "" && $contrasena != "") {
		//Comprobar credenciales
		//if ($usuario == "admin" && md5($contrasena) == $pass)
		if (($usuario == $user) && (crypt($contrasena, '$1$somethin$')) == $pass) {
			//Login correcto
			//Establecer sesion autentificada
			$_SESSION["usuario"] = $usuario;

			exit(header("location:sesion.php"));
		} else $mensaje = "<h2>Usuario y/o contraseña incorrectos</h2>";
	} else $mensaje = "<h2>Los campos usuario y contraseña son obligatorios<h2>";
} else if (isset($_SESSION["usuario"])) {
	//Sesión ya iniciada
	$usuario = $_SESSION["usuario"];
	$mensaje = "";
} else {
	//Carga en vacío sin sesión iniciada
	$usuario = "";
	$mensaje = "";
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<title>Login T4 DWES Daniel del Valle</title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<style type="text/css">
		@import "tarea4.css";
	</style>
</head>

<body>
	<h1>Bienvenido</h1>
	<div>
		<form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">

				<legend>Acceso a usuarios</legend>

				<label for="usuario">Usuario</label>
				<input type="text" name="usuario" id="usuario" value="<?= $usuario ?>" />


				<label for="contrasena">Contraseña</label>
				<input type="password" name="contrasena" id="contrasena" />
				<br>

				<!--	<input type = "submit" name ="entrar" value = "Entrar" method ="POST" /> -->
				<button type="submit" name="entrar" method="POST">Entrar</button>

		</form>
	</div>
	<p> <?php echo"<p class='mensaje'>{$mensaje}<p><br>";?>
</body>

</html>