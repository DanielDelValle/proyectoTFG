<?php
session_start();
session_destroy();
// Controlador frontal (como index.php)
require_once "modelo.php";
require_once 'controladores.php';


// Recogemos la uri insertada
//$URI = $_SERVER['REQUEST_URI'];

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$segments = explode('/', $path);
$URI = $segments[count($segments)-1];


/**si quisiera distinguir entre misma extension "home" por ejemplo, precedida de "empleado" o "cliente" para aplicar una u otra plantilla(vista) y controlador
$URI2 = $segments[count($segments)-2];**/

//echo ">>>$URI<br>";  para mostrar la URI actual

if ($URI == 'index.php') 
{
    controlador_index(); // Se ejecuta el controlador específico de index
}

elseif ($URI == 'detalle_articulos') 
{
    controlador_detalle(); 
}


elseif ($URI == 'login_clientes')
{      
		controlador_login_clientes(); 
}


elseif ($URI == 'login_empleados')
{      
		controlador_login_empleados(); 
}

elseif ($URI == 'home_clientes')
{
		controlador_home_clientes(); 
}


elseif ($URI == 'home_empleados')
{
		controlador_home_empleados(); 
}


/**si quisiera distinguir entre misma extension "home" porejemplo, precedida de "empleado" o "cliente" para aplicar una u otra plantilla(vista) y controlador
elseif (($URI == 'home') && ($URI2 == 'empleado.php'))
{
		controlador_home_empleado(); 
}**/

 elseif ($URI == 'articulo' && isset($_GET['id'])) 
{
    // Se ejecuta el controlador específico que muestra los detalles de un 
    // articulo específico
    controlador_detalle($_GET['id']); 
}


elseif ($URI == 'registro') 
{
    // Se ejecuta el controlador específico de registro
    controlador_registro(); 
}


else 
{ //Podemos gestionar errores de URI de esta forma
    header('Status: 404 Not Found');
    echo '<html><body><h1>La página a la que intenta acceder no       
          existe</h1></body></html>';
}
?>
<script>setInterval(function(){location.reload(true);}, 50000);</script>