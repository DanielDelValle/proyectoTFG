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
    controlador_mercancia(); 
}

elseif ($URI == 'detalle_producto' && isset($_GET['id'])) 
{

    controlador_detalle_producto($_GET['id']); 
}


elseif ($URI == 'detalle_mercancia' && isset($_GET['id'])) 
{

    controlador_detalle_mercancia($_GET['id']); 
}

elseif ($URI == 'modificar_mercancia' && isset($_GET['id'])) 
{

    controlador_modificar_mercancia($_GET['id']); 
}
elseif ($URI == 'detalle_cliente' && isset($_GET['nif'])) 
{

    controlador_detalle_cliente($_GET['nif']); 
}

elseif ($URI == 'detalle_factura' && isset($_GET['id_factura'])) 
{

    controlador_detalle_factura($_GET['id_factura']); 
}

elseif ($URI == 'detalle_factura_pdf' && isset($_GET['id_factura'])) 
{

    controlador_detalle_factura_pdf($_GET['id_factura']); 
}

elseif ($URI == 'detalle_albaran' && isset($_GET['id_albaran'])) 
{

    controlador_detalle_albaran($_GET['id_albaran']); 
}


elseif ($URI == 'detalle_empleado') 
{

    controlador_detalle_empleado(); 
}

elseif ($URI == 'detalle_pedido' && isset($_GET['id_pedido'])) 
{

    controlador_detalle_pedido($_GET['id_pedido']); 
}


elseif ($URI == 'iniciar_sesion')
{      
		controlador_iniciar_sesion(); 
}

elseif ($URI == 'cambiar_contrasena')
{      
		controlador_cambiar_contrasena(); 
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

elseif ($URI == 'control_pedidos')
{      
		controlador_pedidos(); 
}

elseif ($URI == 'control_facturas')
{      
		controlador_facturas(); 
}

elseif ($URI == 'control_cuentas')
{      
		controlador_cuentas(); 
}

elseif ($URI == 'control_stock')
{      
		controlador_stock(); 
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
elseif ($URI == 'contacto') 
{

    controlador_contacto(); 
}



else 
{ //Podemos gestionar errores de URI de esta forma
   // header('Status: 404 Not Found');
    echo '<html><body><h1>La página a la que intenta acceder no       
          existe</h1></body></html>';
}
?>
<!--<script>setInterval(function(){location.reload(true);}, 50000);</script>-->