<?php
require 'funciones.php';
checkSession();
closeSession();
killCookie();
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<title>Hasta Pronto</title>
	<meta charset="UTF-8">
	<meta http-equiv="refresh" content="3;url=login.php" />  <!-- redirige al login tras 3 segundos, para poder visualizar el mensaje de despedida -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<style type="text/css">
		@import "tarea4.css";
	</style>
</head>

<body>
	<p>
	<h2><b><br>Cerrando sesión <br><br><br> ¡¡Hasta Pronto!! </h2></b></p>

</body>

</html>