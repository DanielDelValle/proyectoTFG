<!DOCTYPE HTML>
<html>

 <head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale =1.0";>

  <title> Tarea 2 DWES - 2da parte</title>
 </head>
 
 <body>
	<main>
	
		<?php 
			$entero = 10;
			$decimal = 8.22;
			$booleano = True;
			$texto = "cadena";
			const NUMERO_PI = 3.14;    // las constantes deben nombrarse en Mayúsculas
			echo 'Valor de la variable entero: ' . $entero . '<br>';
			echo 'Valor de la variable decimal: ' . $decimal . '<br>';
			echo 'Valor de la variable booleano: ' . ($booleano ? 'true <br>':'false <br>');  //con esta opción, en lugar de mostrar por pantalla "1" o "0" obligo a php a mostrar True o False (con un salto de linea a continuación).
			echo 'Valor de la variable texto: ' . $texto . '<br>';
			echo 'Valor de la constante pi: ' . NUMERO_PI . '<br>';
			echo 'Resultado de sumar entero + decimal: ' . $entero + $decimal;

        ?>
    
	</main>

  </script>
 </body>
</html>
