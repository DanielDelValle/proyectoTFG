<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="tarea3.css" />
    <title>RAC_E</title>
</head>

<body>
    <section>

        <?php

        require("tarea3.php");  //importo el archivo tarea3.php que contiene las funciones necesarias: 'generarArray' y 'tabla'
        $valido = true;
        $valor = "";
        $mensaje = "";
        //inicializo las variables

    if (isset($_POST["calcular"])) {
        if (isset($_POST["valor"]))
                $valor = $_POST["valor"];    //si se ha pulsado el boton calcular y el campo "valor" está relleno: entonces se recoge dicha información en la variable "valor"
		if ($valor != "")	
			$valido = true;    //si el valor recogido es distinto de un valor vacío
		else $valido = false; $mensaje = "Introduzca un valor";   //si el campo tiene el valor por defecto "" entonces se indica con el mensaje.

        }
	else 
	    {
		//Primer acceso o reseteo
		$valor = "";
	    }

        if ($valido) {
            switch (true) {

                case (!is_numeric($valor)):
                    $valor = "";
                    $mensaje = "Introduzca un valor numérico";
                    break; //en caso de valor NO numérico

                case ($valor < 0):
                    $valor = "";
                    $mensaje = "Introduzca un valor positivo";
                    break; //en caso de valor inferior a 0

                case ($valor > 10):
                    $valor = "";
                    $mensaje = "Valor demasiado alto";
                    break; // encaso de valor superior a 10

                case (($valor >= 0) && ($valor <= 10)):
                    $mensaje = tabla(generarArray($valor));
                    break;  //caso buscado: valor numérico entre 0 y 10 --> se ejecuta la función y se crea la tabla

                default:
                    $mensaje = "Valor desconocido"; // en caso de otro tipo de valor
            }
        }
      ?>

        <h1>Tarea 3 - Programación Básica</h1>
        <form method="post" , action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">  <!--  se define método post y se evita inyección codigo malicioso con htmlentities -->
            <legend>ENTRADA DE VALOR NUMÉRICO</legend><br>
            <label for="valor">valor</label>
            <input id="valor" type="text" placeholder= "introduzca un valor" name= "valor"
            value="<?= $valor ?>" />   <!--  se establece el valor del campo para que se mantenga visible en laweb -->
            <input name="calcular" type="submit", value = "Calcular" />   <!--  el botón "Calcular" servirá para dar la orden de procesar el formulario -->
        </form>
                <br><hr>

      <?php

        if (isset($_POST["calcular"])) {
                echo $mensaje;        //retornar el mensaje de feedback para el usuario cuando se pulsa el botón "Calcular" 
            
        }
        ?>


    </section>
</body>
<footer>
    <button><a href="https://danivalleglez53665340s.000webhostapp.com/">Volver al inicio</a>
</footer>


</html>