<?php // Controlador frontal 
require_once 'funciones_sesion.php';
require_once "modelo.php";
require_once 'validadores.php';
require_once 'controladores.php';


// Recogemos la uri insertada
//$URI = $_SERVER['REQUEST_URI'];

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$segments = explode('/', $path);
$URI = $segments[count($segments)-1];


/**si quisiera distinguir entre misma extension "home" por ejemplo, precedida de "empleado" o "cliente" para aplicar una u otra plantilla(vista) y controlador
$URI2 = $segments[count($segments)-2];**/

echo ">>>$URI<br>";  //para mostrar la URI actual

if ($URI == 'index.php') 
{
    controlador_index(); // Se ejecuta el controlador específico de index
}

elseif ($URI == 'detalle_producto' && isset($_GET['id'])) 
{
    // Se ejecuta el controlador específico que muestra los detalles de un 
    // articulo específico
    controlador_detalle($_GET['id']); 
}


elseif ($URI == 'busqueda') 
{
    controlador_busqueda(); 
}



elseif ($URI == 'login')
{      
		controlador_login(); 
}




elseif ($URI == 'home')
{
		controlador_home(); 
}



/**si quisiera distinguir entre misma extension "home" porejemplo, precedida de "empleado" o "cliente" para aplicar una u otra plantilla(vista) y controlador
elseif (($URI == 'home') && ($URI2 == 'empleado.php'))
{
}**/

elseif ($URI == 'adios')
{
		controlador_adios(); 
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
<script>setInterval(function(){location.reload(true);}, 5000000);</script>