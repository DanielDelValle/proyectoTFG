<?php
require 'funciones.php';
checkSession();
checkCookie();
?>
<?php
$tel_ok = false;
$email_ok = false;
$usuario = $_SESSION["usuario"];
$bienvenida = "<h3><b>Bienvenido " . $usuario . "</b></h3>";
$mensaje = "";
//inicializo las variables

if (isset($_POST["grabar"])) {
    if (isset($_POST["telefono"]))
        $telefono = $_POST["telefono"];
    //si se ha pulsado el boton grabar y el campo "telefono" está relleno: entonces se recoge dicha información en la variable "telefono"

    if ($telefono != "") {
        $tel_ok = true;
    } else {
        $tel_ok = false;
        $mensaje = $mensaje . "<br>Introduzca un telefono";
    }  //si el campo tiene el valor por defecto "" entonces se indica con el mensaje.

    if (isset($_POST["email"]))
        $email = $_POST["email"];
    //si se ha pulsado el boton grabar y el campo "email" está relleno: entonces se recoge dicha información en la variable "email"

    if ($email != "") {
        $email_ok = true;
    } else {
        $email_ok = false;
        $mensaje = $mensaje . "<br>Introduzca un email";   //si el campo tiene el valor por defecto "" entonces se indica con el mensaje.
    }
} else {
    //Primer acceso o reseteo
    $telefono = "";
    $email = "";
}
//Aquí filtro el telefono para que sea numérico, de 9 cifras.
if ($tel_ok) {
    switch (true) {

        case (!is_numeric($telefono)):
            $telefono = "";
            $mensaje = $mensaje . "<br>Introduzca un telefono formado solo por números";
            break; //en caso de valor NO numérico

        case (strlen($telefono) != 9):
            $telefono = "";
            $mensaje = $mensaje . "<br>El teléfono debe contener exáctamente 9 cifras";
            break; // encaso de longitud superior a 9

        case (strlen($telefono) == 9):
            $_SESSION['telefono'] = $telefono;
            $mensaje = $mensaje . "<br>telefono <b>{$_SESSION['telefono']}</b> grabado correctamente ";
            break;  //caso buscado: valor numérico entre 0 y 10 --> se ejecuta la función y se crea la tabla

        default:
            $mensaje = $mensaje . "<br>Error al introducir el telefono"; // en caso de otro tipo de valor
    }
}
//Aquí compruebo que el email contiene una arroba (no es un filtro 100% exacto, pero los fallos más obvios los cubre)
if ($email_ok) {

    if (!preg_match('/@/', $email)) {
        $email = "";
        $mensaje = $mensaje . "<br>Introduzca un email correcto";
    } else {
        $_SESSION['email'] = $email;
        $mensaje = $mensaje . "<br>email <b>{$_SESSION['email']}</b> grabado correctamente ";
    }
}
//compruebo que se ha pulsado "grabar horario" y elegido una opción
if (isset($_POST["horario"])) {
    if ($_POST["horario"] == "") {
        $mensaje = "<br>No has elegido ningún horario";
    } else {
        $choice = filter_input(INPUT_POST, 'horario');
        setcookie('horario', $choice, time() + 3600);
        $mensaje = $mensaje . "<br>Elección de horario grabada : <b>{$choice}</b>" . "<br>";   //retornar el mensaje de feedback para el usuario cuando se pulsa el botón "grabar horario" 
    }
}

if (isset($_POST["grabar"])) {

    echo "<p class='mensaje'>{$mensaje}<p><br>";      //retornar el mensaje de feedback para el usuario cuando se pulsa el botón "grabar" 
}
if (isset($_POST["grabar_hor"])) {
    echo "<p class='mensaje'>{$mensaje}<p><br>";        //retornar el mensaje de feedback para el usuario cuando se pulsa el botón "grabar horario" 

}


if (isset($_POST["borrar"])) {
    exit(header("location:adios.php"));  //redirige a pagina de despedida
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style type="text/css">
        @import "tarea4.css";
    </style>
    <title>Formulario T4 DWES Daniel del Valle</title>
</head>

<body>
        <p>
        <h3><?= $bienvenida ?></h3>
        <div contact>
            <form method="POST"  action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" autocomplete>
                <!--  se define método post y se evita inyección codigo malicioso con htmlentities -->
                <fieldset>
                    <legend>Entrada de datos personales</legend><br>



                    <label for="telefono">Telefono</label>
                    <input id="telefono" type="text" placeholder="Introduzca su teléfono" name="telefono" value="<?php if(isset($_SESSION['telefono'])){echo $_SESSION['telefono'];}?>" /> <!--  se establece el valor del campo para que se mantenga visible en la web -->

                    <br>

                    <label for="email">Dirección Email</label>
                    <input id="email" type="text" placeholder= "Introduzca su email" name="email" value="<?php if(isset($_SESSION['email'])){echo $_SESSION['email'];}?>" /> <!--  se establece el valor del campo para que se mantenga visible en la web -->
                    <br>
                    <!-- <input name="grabar" type="submit" , value="Grabar" , method="POST" /> -->
                    <!--  el botón "Grabar" da la orden de procesar el formulario -->
                    <button type="submit" name="grabar" method="POST">Grabar</button>
                </fieldset>
            </form>
            <br>
            <form method="POST" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" autocomplete>
                <fieldset >
                    <legend>Selección de Horario</legend><br>
                    <label for="horario">Horario</label>
                    <select name="horario" id="horario">
                        <option <?php if (!$choice) { ?>selected="true" <?php }; ?>value="">--- Elija una opción ---</option>
                        <option <?php if ($choice == 'mañana') { ?>selected="true" <?php }; ?>value='mañana'>Mañana</option>
                        <option <?php if ($choice == 'tarde') { ?>selected="true" <?php }; ?>value='tarde'>Tarde</option>
                        <option <?php if ($choice == 'noche') { ?>selected="true" <?php }; ?>value='noche'>Noche</option>

                    </select>
                    <!--    <input type="submit" name="grabar_hor" value="Grabar Horario"  method = "POST"/>-->
                    <!--  el botón "Grabar hoario" guardar el horario elegido en una cookie -->
                    <button type="submit" name="grabar_hor" method="POST">Grabar Horario</button>
                    <br><br>
                    <!--<input type="submit" name="borrar" value="Borrar" method="POST" />-->
                    <!--  el botón "Borrar" da la orden de borrar datos de sesión y elimina cookie-->
                    <button type="submit" name="borrar" method="POST">Borrar</button>
                </fieldset>
            </form>
        </div>

</body>

</html>