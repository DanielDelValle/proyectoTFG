<?php
//session_start(); //LO COMENTÉ PARA ELIMINAR DUPLICIDAD DE SESSION
if (isset($_POST["entrar"])) {
	//Procesar formulario
	//Obtener contraseña codificada de bd, por ejemplo:
/*	$guests_users = array(array("daniel"=>"daniel88"), array("manolo@gmail.com"=>"manolete"));
	$employee_users = array(array("admin"=>"admin88"), array("storage"=>"storage88"));
	Ejemplo de array que contendria tablas de user y contraseña obtenidas de BBDD*/
	$user = "daniel";
	$pass = crypt('daniel', '$1$somethin$');
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

			exit(header("location:home_empleados"));
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