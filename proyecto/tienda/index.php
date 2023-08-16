<?php // Controlador frontal 
require_once 'controladores.php';


// Recogemos la uri insertada

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$segments = explode('/', $path);
$URI = $segments[count($segments)-1];


/**si quisiera distinguir entre misma extension "home" por ejemplo, precedida de "empleado" o "cliente" para aplicar una u otra plantilla(vista) y controlador
$URI2 = $segments[count($segments)-2];**/

//echo ">>>$URI<br>";  //para mostrar la URI actual

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



elseif ($URI == 'iniciar_sesion')
{      
		controlador_iniciar_sesion(); 
}




elseif ($URI == 'home')
{
		controlador_home(); 
}



/**si quisiera distinguir entre misma extension "home" porejemplo, precedida de "empleado" o "cliente" para aplicar una u otra plantilla(vista) y controlador
elseif (($URI == 'home') && ($URI2 == 'empleado.php'))
{
}**/


elseif ($URI == 'mi_cuenta')
{      
		controlador_mi_cuenta(); 
}

elseif ($URI == 'mis_datos')
{      
		controlador_mis_datos(); 
}

elseif ($URI == 'mis_pedidos')
{      
		controlador_mis_pedidos(); 
}


elseif ($URI == 'mi_cesta')
{      
		controlador_mi_cesta(); 
}

elseif ($URI == 'datos_envio')
{      
		controlador_datos_envio(); 
}

elseif ($URI == 'confirmar_pedido')
{      
		controlador_confirmar_pedido(); 
}

elseif ($URI == 'pedido_realizado')
{      
		controlador_pedido_realizado(); 
}





elseif ($URI == 'cerrar_sesion')
{
		controlador_cerrar_sesion(); 
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