<?php // Controlador frontal 
require_once 'controladores.php';


// Recogemos la uri insertada

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$segments = explode('/', $path);
$URI = $segments[count($segments)-1];

//$URI2 = $segments[count($segments)-2];**/

//si quisiera distinguir entre misma extension "home" por ejemplo, seguida de "empleado" o "cliente" para aplicar una u otra plantilla(vista) y controlador


//echo ">>>$URI<br>";  //para mostrar la URI actual

if ($URI == 'index.php') 
{
    controlador_index(); // Se ejecuta el controlador específico de index
}


elseif ($URI == 'mercancia') 
{
    controlador_mercancia(); // Se ejecuta el controlador específico de index
}


elseif ($URI == 'detalle_producto' && isset($_GET['id'])) 
{
    // Se ejecuta el controlador específico que muestra los detalles de un articulo específico
    controlador_detalle_producto($_GET['id']); 
}

elseif ($URI == 'detalle_mercancia' && isset($_GET['id'])) 
{
    // Se ejecuta el controlador específico que muestra los detalles de un  producto específico
    controlador_detalle_mercancia($_GET['id']); 
}
elseif ($URI == 'detalle_cliente' && isset($_GET['email'])) 
{
    // Se ejecuta el controlador específico que muestra los detalles de un  pedido específico
    controlador_detalle_cliente($_GET['email']); 
}


elseif ($URI == 'detalle_pedido' && isset($_GET['id_pedido'])) 
{
    // Se ejecuta el controlador específico que muestra los detalles de un  pedido específico
    controlador_detalle_pedido($_GET['id_pedido']); 
}



elseif ($URI == 'iniciar_sesion')
{      
		controlador_iniciar_sesion(); 
}

elseif ($URI == 'crear_cuenta')
{      
		controlador_crear_cuenta(); 
}

elseif ($URI == 'alta_empleado')
{      
		controlador_alta_empleado(); 
}


elseif ($URI == 'home_admon')
{
		controlador_home_admon(); 
}


elseif ($URI == 'home_almacen')
{
		controlador_home_almacen(); 
}


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

elseif ($URI == 'pedidos')
{      
		controlador_pedidos(); 
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

elseif ($URI == 'pedido_realizado' && isset($_GET['id_pedido']))
{      
		controlador_pedido_realizado($_GET['id_pedido']); 
}


elseif ($URI == 'cerrar_sesion')
{
		controlador_cerrar_sesion(); 
}

elseif ($URI == 'registro_correcto') 
{

    controlador_registro_correcto(); 
}
elseif ($URI == 'alta_correcta') 
{

    controlador_alta_correcta(); 
}


else 
{ //Podemos gestionar errores de URI de esta forma
   // header('Status: 404 Not Found');
    echo '<html><body><h1>La página a la que intenta acceder no       
          existe</h1></body></html>';
}
?>
<script>setInterval(function(){location.reload(true);}, 5000000);</script>