<?php
// controladores.php
require_once '../vendor/autoload.php';
require_once 'email.php';


$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

require_once 'funciones_sesion.php';
require_once "modelo.php";
require_once 'validadores.php';

$base = 'base.html';
global $base;

function get_URI(){
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $segments = explode('/', $path);
    $URI = $segments[count($segments)-1];  
    //Adaptamos URI al usuario: 
    $URI = str_replace("_", " ", $URI );   //sustituyo las dash(necesarias para las rutas) por espacios en blanco para mejor legibilidad del usuario
    if($URI === "index.php") $URI = "nuestros productos";  //es la unica vista que no coincide en nombre con su controlador (por ser la principal)

    return $URI;
}


/*$tel_ok = false;
$email_ok = false;
$usuario = "";
$contrasena = "";
$estado = "";
$mensaje = "";*/
//$twig->addGlobal('session', $_SESSION); // para el tema sesiones de usuario


/*, [
    'cache' => 'templates/compilation_cache',
]);
descomentar y poner a continuacion de la linea "$twig=....($loader," para habilitar cache*/ 


/*function controlador_index2()
{
    // Petición al modelo para que retorne la lista de productos de la BD
    $productos = cargar_datos();
    $sugerencias = resultado_ajax();
    
    // Carga la plantilla que se mostrará al usuario con los datos recuperados del modelo
	global $twig;
    // Carga la plantilla que se mostrará al usuario con los datos recuperados 
    // del modelo
    $template = $twig->load('productos.html');
	echo $template->render(array ( 'productos' => $productos, 'sugerencias' => $sugerencias));
    echo gettype($sugerencias);
}*/
//CONTROLA LA PAGINA PRINCIPAL, DONDE SE MUESTRAN LOS PRODUCTOS DE LA TIENDA
function controlador_index()
{ $URI = get_URI();
    session_start();
    $productos = lista_productos();
    //estas variables son necesarias para variar el texto de los links, dependiendo de si está el usuario logeado o no
    $logged = isset($_SESSION['usuario']) ? "cerrar_sesion" : "iniciar_sesion";
    $logged_legible = isset($_SESSION['usuario']) ? "Cerrar Sesión" : "Iniciar Sesión";

    $total_prods = (isset($_SESSION['usuario']) && isset($_SESSION['cesta'])) ? count($_SESSION['cesta']) : 0;
    
    // Carga la plantilla que se mostrará al usuario con los datos recuperados del modelo
	global $twig;
    // Carga la plantilla que se mostrará al usuario con los datos recuperados 
    // del modelo
    $template = $twig->load('productos.html');
	echo $template->render(array ('URI'=>$URI, 'logged'=>$logged, 'logged_legible'=>$logged_legible, 'productos' => $productos, 'total_prods'=>$total_prods));
}

function controlador_mercancia()
{ $URI = get_URI();
    $usuario = checkSession();
    //Con las siguientes 2 lineas restrinjo el acceso a partes solo destinadas a empleados
    $base = checkDomain($usuario);   
    if ($base !== 'base_empl') exit(header('location:iniciar_sesion'));

    $empleado = datos_empleado($usuario);

    // Petición al modelo para que retorne la lista de productos de la BD
    $productos = lista_productos();
    $total_prods = (isset($_SESSION['usuario']) && isset($_SESSION['cesta'])) ? count($_SESSION['cesta']) : 0;
    
    // Carga la plantilla que se mostrará al usuario con los datos recuperados del modelo
	global $twig;
    // Carga la plantilla que se mostrará al usuario con los datos recuperados 
    // del modelo
    $template = $twig->load('mercancia.html');
	echo $template->render(array ('URI'=>$URI, 'empleado'=>$empleado, 'productos' => $productos));
}

function controlador_stock()
{ $URI = get_URI();
    $usuario = checkSession();
    $base = checkDomain($usuario);   
    $empleado = datos_empleado($usuario);
  //  if ($empleado->tipo_cuenta != 'admon'){exit(header('location:iniciar_sesion'));} //solo ADMON puede manipular cuentas de usuario
    $logged = isset($_SESSION['usuario']) ? "cerrar_sesion" : "iniciar_sesion";
    $logged_legible = isset($_SESSION['usuario']) ? "Cerrar Sesión" : "Iniciar Sesión";
    $lista_productos = lista_productos();

   if (isset($_POST["editar"])) {
       
   }
  
    $mensaje = '';

    global $twig;
    $template = $twig->load('control_stock.html');  
	echo $template->render(array ('URI'=>$URI, 'empleado'=>$empleado, 'lista_productos' => $lista_productos,  'mensaje'=> $mensaje, 'logged'=>$logged, 'logged_legible'=>$logged_legible));
    

}

function controlador_detalle_producto($id)
{   
    $URI = get_URI(); 
    $_SESSION['cesta'] = checkCesta();
    $logged = isset($_SESSION['usuario']) ? "cerrar_sesion" : "iniciar_sesion";
    $logged_legible = isset($_SESSION['usuario']) ? "Cerrar Sesión" : "Iniciar Sesión";
    $cesta = $_SESSION['cesta'];
    $total_prods = (isset($_SESSION['usuario']) && isset($_SESSION['cesta'])) ? count($_SESSION['cesta']) : 0;
   
    $producto = get_object_vars(detalle_producto($id));  //transformo el objeto que devuelve el modelo en array asociativo
    $mensaje = "";

    if (isset($_POST["anadir_producto"])) {
        session_start();   // PARA PERMITIR AÑADIR PRODUCTOS SIN HABER INICIADO SESION (CESTA SOLO ACCESIBLE TRAS LOGIN)
        $usuario = checkSession();
        $_SESSION['cesta'] = checkCesta();
        $logged = isset($_SESSION['usuario']) ? "cerrar_sesion" : "iniciar_sesion";
        $logged_legible = isset($_SESSION['usuario']) ? "Cerrar Sesión" : "Iniciar Sesión";
        $total_prods = (isset($_SESSION['usuario']) && isset($_SESSION['cesta'])) ? count($_SESSION['cesta']) : 0;

        $prod_add = array();  //$prod_add = new ArrayObject(); 
        $mensaje = 'Producto añadido a la cesta';

        
        $prod_add['id_prod'] = (int)$producto['id_prod'];
        $prod_add['nombre'] = $producto['nombre'];
        $prod_add['precio'] = (float)$producto['precio'];
        $prod_add['cantidad'] = (float)1.0;
        
        //si el array "cesta" dentro de SESSION tiene alguna entrada, se compara la id del producto a introductir con los KEYS ( ya que lo diseñé así) .
        //Si ya está dentro, sólo se actualiza la cantidad.
        if(count($_SESSION['cesta']) != 0){
            if(in_array($prod_add['id_prod'], array_keys($_SESSION['cesta']))){
                $_SESSION['cesta'][$prod_add['id_prod']]['cantidad'] +=1;

            } 
            else {
                //Si ninguno de los productos de la cesta es el que vamos a introducir, se introduce el mismo
                $_SESSION['cesta'][$prod_add['id_prod']] = $prod_add;
            }
        
        }else{
            // Si la cesta esta vacia se introduce directamente el producto, dandole como indice la ID del mismo para mas facil identificacion.
            $_SESSION['cesta'][$prod_add['id_prod']] = $prod_add;
        }

        }
    
    global $twig;
    $template = $twig->load('detalle_producto.html');  
	echo $template->render(array ('URI'=>$URI, 'producto' => $producto, 'total_prods'=>$total_prods, 'mensaje'=> $mensaje, 'logged'=>$logged, 'logged_legible'=>$logged_legible));
    
}

function controlador_detalle_mercancia($id)
{   $URI = get_URI();    
    //Con las siguientes 2 lineas restrinjo el acceso a partes solo destinadas a empleados
    $usuario = checkSession();
    $base = checkDomain($usuario);   
    if ($base !== 'base_empl') exit(header('location:iniciar_sesion'));

    $empleado = datos_empleado($usuario);

    // Petición al modelo para que retorne la lista de productos de la BD
    $producto = get_object_vars(detalle_producto($id));  //transformo el objeto que devuelve el modelo en array asociativo
    $mensaje = "";

    
    global $twig;
    $template = $twig->load('detalle_mercancia.html');  
	echo $template->render(array ('URI'=>$URI, 'empleado'=>$empleado, 'base'=>$base, 'producto' => $producto, 'mensaje'=> $mensaje));
    
}

function controlador_detalle_factura($id_factura)////////////este se lllama detallle_ffactura
{   $URI = get_URI();    
    //Con las siguientes 2 lineas restrinjo el acceso a partes solo destinadas a empleados
    $usuario = checkSession();
    $base = checkDomain($usuario);   
    $empleado = datos_empleado($usuario);
    $cliente = datos_cliente($usuario);
    $logged = isset($_SESSION['usuario']) ? "cerrar_sesion" : "iniciar_sesion";
    $logged_legible = isset($_SESSION['usuario']) ? "Cerrar Sesión" : "Iniciar Sesión";

    $factura = datos_factura($id_factura);
    $contenido= unserialize ($factura->contenido);  //DESHAGO EL "SERIALIZE" QUE PERMITIA GUARDAR EL ARRAY EN LA BBDD, PARA PODER INTERPRETARLO
    $mensaje = "";

  //  var_dump($factura);


    global $twig;
    $template = $twig->load('detalle_factura.html');  ////////////este se lllama detallle_ffactura
	echo $template->render(array ('URI'=>$URI, 'empleado'=>$empleado, 'usuario'=>$usuario,'base'=>$base, 'factura'=>$factura, 'contenido'=>$contenido, 'mensaje'=> $mensaje, 'logged'=>$logged, 'logged_legible'=>$logged_legible));
    
}

function controlador_detalle_cliente($nif)
{   $URI = get_URI();
    $usuario = checkSession();
    //Con las siguientes 2 lineas restrinjo el acceso a partes solo destinadas a empleados
    $base = checkDomain($usuario);  
    if ($base !== 'base_empl') exit(header('location:iniciar_sesion'));

    $empleado = datos_empleado($usuario); 
    if ($empleado->tipo_cuenta != 'admon'){exit(header('location:control_pedidos'));}  // sólo el administrativo puede ver los datos del cliente
    $cliente = datos_cliente_nif($nif);
    $email = $cliente->email;    
    $total_pedidos = count(pedidos_usuario($nif)); //EL NÚM DE ELEMENTOS DEL ARRAY DE LOS PEDIDOS DEL USUARIO
    $total_gasto = array_sum(array_column(pedidos_usuario($nif), 'total_pedido')); // SUMO LOS ELEMENTOS DEL ARRAY QUE CONTIENE EL TOTAL DE PRECIO DE TODOS LOS PEDIDOS DEL USUARIO
    $mensaje = "";

    if(isset($_POST['volver_pedidos']))exit(header('location:control_pedidos'));

    
    global $twig;
    $template = $twig->load('detalle_cliente.html');  
	echo $template->render(array ('URI'=>$URI, 'empleado'=>$empleado, 'cliente'=>$cliente, 'mensaje'=> $mensaje, 'total_pedidos'=>$total_pedidos, 'total_gasto' =>$total_gasto));
    
}


function controlador_detalle_empleado()
{   $URI = get_URI();
    $usuario = checkSession();
    $base = checkDomain($usuario);   
    if ($base !== 'base_empl') exit(header('location:iniciar_sesion'));
    $empleado = datos_empleado($usuario);
    $mensaje = "";
    global $twig;
    $template = $twig->load('detalle_empleado.html');  
	echo $template->render(array ('URI'=>$URI, 'empleado'=>$empleado, 'mensaje'=> $mensaje));
    
}

function controlador_mis_datos()   
{   $URI = get_URI();
    $usuario = checkSession();
    $cliente = datos_cliente($usuario);
    $logged = isset($_SESSION['usuario']) ? "cerrar_sesion" : "iniciar_sesion";
    $logged_legible = isset($_SESSION['usuario']) ? "Cerrar Sesión" : "Iniciar Sesión";
    $total_prods = isset($_SESSION['cesta']) ? count($_SESSION['cesta']) : 0;
    $cesta = checkCesta();
    $total_prods = $_SESSION['total_prods'];
    if (isset($_POST["volver_cuenta"])) exit(header("location:mi_cuenta"));
    if (isset($_POST["mis_pedidos"])) exit(header("location:mis_pedidos"));

    if (isset($_POST["eliminar_cuenta"])) {        
        $contador = eliminar_cuenta($usuario);
        $mensaje = $contador. " cuentas eliminadas";
        $delay=3;
        header("Refresh:$delay");}
   
    global $twig;
    $template = $twig->load('mis_datos.html');
	echo $template->render(array ('URI'=>$URI, 'usuario' =>$usuario, 'cliente'=> $cliente, 'logged'=>$logged, 'logged_legible'=>$logged_legible, 'total_prods'=>$total_prods));

}


function controlador_mi_cesta()
{   $URI = get_URI();
    $usuario = checkSession();
    $cliente = datos_cliente($usuario);
    $logged = isset($_SESSION['usuario']) ? "cerrar_sesion" : "iniciar_sesion";
    $logged_legible = isset($_SESSION['usuario']) ? "Cerrar Sesión" : "Iniciar Sesión";
    $cesta = checkCesta();
    $_SESSION['cesta'] = $cesta;
    $mensaje = "";
	global $twig;


function total_mercancia(){
    $total= 0;
    $total_mercancia = 0;
    $cesta = checkCesta();
    foreach($cesta as $i){
        $total = (($i['precio']) * ($i['cantidad']));
            $total_mercancia += $total;
            
        }$_SESSION['total_mercancia'] = $total_mercancia;

return $total_mercancia;
}
$total_mercancia = total_mercancia();

function total_prods(){
    $total_prods = count($_SESSION['cesta']);
    $_SESSION['total_prods'] = $total_prods;
    return $total_prods;
    }
$total_prods = total_prods();
 
function total_kg(){
    $total_kg = 0;
    foreach((($_SESSION['cesta'])) as $i) {
        $total_kg += $i['cantidad'];
        }$_SESSION['total_kg'] = $total_kg;
    return $total_kg;
    }
$total_kg = total_kg();

function coste_envio($total_kg){
    if($total_kg == 0) $coste_envio=0;
    elseif (($total_kg > 0) && ($total_kg <= 10)) {    //SI EL PESO ES IGUAL O MENOR DE 10KG, EL ENVIO CUESTA 7.5€. DE 10 EN ADELANTE, CUESTA 15€
        $coste_envio = 7.5; 
    }else $coste_envio = 15; 
    $_SESSION['coste_envio'] = $coste_envio;
    return $coste_envio;
}
$coste_envio = coste_envio($total_kg);

function total_pedido(){
    $total_pedido = $_SESSION['total_mercancia'] + $_SESSION['coste_envio'];
    $_SESSION['total_pedido'] = $total_pedido;
    return $total_pedido;
}
$total_pedido = total_pedido();


function borrar_producto(){
    $mensaje="";
    if(isset($_POST['productos_borrar'])) {
    $checked = $_POST['productos_borrar'];
    if(intval($checked) == 1){
        foreach($_POST['productos_borrar'] as $val){
            unset($_SESSION['cesta'][intval($val)]);
        }
        $delay=3;
        header("Refresh:$delay");
        $mensaje = "Producto eliminado con Éxito";
    }
}else $mensaje = "Por favor, seleccione al menos un producto para eliminar";
return $mensaje;
}
    
    
function vaciar_cesta(){
        unset($_SESSION['cesta']);
        $mensaje = "Cesta Vaciada con Éxito";
        $delay=3;
        header("Refresh:$delay");
        return $mensaje;
    }

    if(isset($_POST["datos_envio"])){

        if($total_prods>0){exit(header("location:datos_envio"));}

        elseif ($total_prods===0)$mensaje="No hay productos en su cesta";
    }

    if(isset($_POST["borrar_producto"])){
        $mensaje = borrar_producto();
    }


    if (isset($_POST["vaciar_cesta"])) {
        $mensaje = vaciar_cesta();
    }

    $template = $twig->load('mi_cesta.html');
	echo $template->render(array ('URI'=>$URI, 'usuario' =>$usuario, 'cliente'=> $cliente, 'cesta' => $cesta, 'mensaje' =>$mensaje, 'logged'=>$logged, 'logged_legible'=>$logged_legible, 'total_kg'=> $total_kg, 'total_prods'=> $total_prods,  'coste_envio'=>$coste_envio, 'total_mercancia' =>$total_mercancia, 'total_pedido'=>$total_pedido ));


}

function controlador_datos_envio(){
    $URI = get_URI();
    $usuario = checkSession();
    $cliente = datos_cliente($usuario);
    $logged = isset($_SESSION['usuario']) ? "cerrar_sesion" : "iniciar_sesion";
    $logged_legible = isset($_SESSION['usuario']) ? "Cerrar Sesión" : "Iniciar Sesión";
    $mensaje="";
    $total_mercancia = $_SESSION['total_mercancia'];
    $total_kg = $_SESSION['total_kg'];
    $total_prods = $_SESSION['total_prods'];

    if(isset($_POST["volver_cesta"])){
        exit(header("location:mi_cesta"));
    }

    if(isset($_POST["confirmar_datos"])){
        exit(header("location:confirmar_pedido"));
    }


    global $twig;
    $template = $twig->load('datos_envio.html');
	echo $template->render(array ('URI'=>$URI, 'usuario' =>$usuario, 'cliente'=>$cliente, 'mensaje' => $mensaje, 'logged'=>$logged, 'logged_legible'=>$logged_legible));

}

function controlador_confirmar_pedido(){ 
    $URI = get_URI();
    $usuario = checkSession();
    $cliente = datos_cliente($usuario);
    $logged = isset($_SESSION['usuario']) ? "cerrar_sesion" : "iniciar_sesion";
    $logged_legible = isset($_SESSION['usuario']) ? "Cerrar Sesión" : "Iniciar Sesión";
    $_SESSION['cesta'] = checkCesta();
    $cesta = $_SESSION['cesta'];
    $total_mercancia = $_SESSION['total_mercancia'];
    $total_kg = $_SESSION['total_kg'];
    $total_prods = $_SESSION['total_prods'];
    $coste_envio = $_SESSION['coste_envio'];
    $total_pedido = $_SESSION['total_pedido'];
    $mensaje="";
    $id_pedido= isset($_POST['id_pedido'])? $_POST['id_pedido']: '';
    $forma_pago=isset($_POST['forma_pago'])? $_POST['forma_pago']: '';
    $_SESSION['forma_pago'] = $forma_pago;
    
    
    
    if(isset($_POST["volver_cesta"])){
        exit(header("location:mi_cesta"));
    }

    if(isset($_POST["volver_datos"])){
        exit(header("location:datos_envio"));
    }


        if(isset($_POST["confirmar_pedido"]) && (isset($_POST['forma_pago']))){
   

            $creado_fecha = date('Y-m-d H:i:s'); //hora y fecha actuales
            $fecha = date('dmY_his');
            //El ID_PEDIDO contiene información relevante: el COD_POSTAL para facilitar agrupación de pedidos en almacén y transporte eficiente.
                                                        // el NIF para agrupar pedidos por empresa o titular 
                                                        // la fecha y hora en que se hizo para obtener siempre un número único (necesario para que sea PRIMARY KEY).

            $id_pedido = $cliente->cod_postal."-".$cliente->nif."-".$fecha; 
            $notas = utf8_encode($_POST['notas']);

                //si ambas operaciones insert retornan TRUE
  if(insert_pedido($id_pedido, $cliente->nif, $total_mercancia, $total_kg, $coste_envio, $total_pedido, $forma_pago, $creado_fecha, $notas) && (insert_productos_pedido($id_pedido, $cesta))){
             //  $email = pruebaMail($cliente->email, $cliente->nombre);
            exit(header("location:pedido_realizado?id_pedido=$id_pedido"));
          } else $mensaje= "Error al grabar el pedido - por favor, repita el proceso de nuevo";
              

        } else $mensaje = "Por favor, seleccione una forma de pago";


    global $twig;
    $template = $twig->load('confirmar_pedido.html');
    echo $template->render(array ('URI'=>$URI, 'usuario' =>$usuario, 'cliente'=>$cliente, 'cesta' => $cesta, 'logged'=>$logged, 'logged_legible'=>$logged_legible, 'mensaje' => $mensaje, 'id_pedido'=>$id_pedido, 'total_prods'=>$total_prods, 'total_kg'=> $total_kg, 'total_mercancia'=>$total_mercancia, 'coste_envio'=>$coste_envio, 'total_pedido'=>$total_pedido));
}


function controlador_pedido_realizado($id_pedido){
    $URI = get_URI();
    $usuario = checkSession();
    $cesta = checkCesta();
    $cliente = datos_cliente($usuario);
    $logged = isset($_SESSION['usuario']) ? "cerrar_sesion" : "iniciar_sesion";
    $logged_legible = isset($_SESSION['usuario']) ? "Cerrar Sesión" : "Iniciar Sesión";
    $id_pedido= $_GET['id_pedido'];
    $nif = $cliente->nif;
    $mensaje= '';
    $total_prods = $_SESSION['total_prods'];
    $total_mercancia = $_SESSION['total_mercancia'];
    $total_kg = $_SESSION['total_kg'];
    $coste_envio = $_SESSION['coste_envio'];
    $total_pedido = $_SESSION['total_pedido'];
    $forma_pago = $_SESSION['forma_pago'];
    unset($_SESSION['cesta']);      //eliminamos cesta pues ya se ha transformado en pedido  // COMENTAR CUANDO NECESITE HACER PRUEBAS
    if(isset($_POST["ver_productos"])){
        exit(header("location:index.php"));
    }

    if(isset($_POST["mis_pedidos"])){
        exit(header("location:mis_pedidos"));
    }

    if(isset($_POST["volver_home"])){
        exit(header("location:mi_cuenta"));
    }

    if (isset($_POST["cerrar_sesion"])){
        exit(header('location:cerrar_sesion'));
    }
    global $twig;
    $template = $twig->load('pedido_realizado.html');
	echo $template->render(array ('URI'=>$URI, 'usuario' =>$usuario, 'nif'=>$nif,'cliente'=>$cliente, 'cesta' => $cesta, 'id_pedido'=> $id_pedido, 'logged'=>$logged, 'logged_legible'=>$logged_legible, 'mensaje' => $mensaje, 'total_prods'=>$total_prods, 'total_mercancia'=>$total_mercancia, 'total_kg'=>$total_kg, 'coste_envio'=>$coste_envio, 'total_pedido'=>$total_pedido, 'forma_pago'=>$forma_pago));

}


function controlador_detalle_pedido($id_pedido)
{   $URI = get_URI();   
    $usuario = checkSession();
    $empleado = datos_empleado($usuario);
    $base = checkDomain($usuario);    
    $logged = isset($_SESSION['usuario']) ? "cerrar_sesion" : "iniciar_sesion";
    $logged_legible = isset($_SESSION['usuario']) ? "Cerrar Sesión" : "Iniciar Sesión";
    // Petición al modelo para que retorne la lista de productos de la BD
    $_SESSION['cesta'] = checkCesta();
    $total_prods = (isset($_SESSION['usuario']) && isset($_SESSION['cesta'])) ? count($_SESSION['cesta']) : 0;
    $pedido = items_pedido($id_pedido);  //transformo el objeto que devuelve el modelo en array de objetos
    $facturacion = facturacion_pedido($id_pedido);
    $mensaje = "";
    $funcion_admon = '';
    if ($base == 'base_empl') {
        if ($empleado->tipo_cuenta =='almacen')$funcion_admon = 'hidden';}

    global $twig;
    $template = $twig->load('detalle_pedido.html');  
	echo $template->render(array ('URI'=>$URI, 'base'=>$base, 'empleado'=>$empleado,'pedido' => $pedido, 'facturacion'=>$facturacion, 'total_prods'=>$total_prods, 'mensaje'=> $mensaje, 'logged'=>$logged, 'logged_legible'=>$logged_legible, 'funcion_admon'=>$funcion_admon));
   


}

function controlador_mis_pedidos()   
{   $URI = get_URI();
    $usuario = checkSession();
    $cliente = datos_cliente($usuario);
    $logged = isset($_SESSION['usuario']) ? "cerrar_sesion" : "iniciar_sesion";
    $logged_legible = isset($_SESSION['usuario']) ? "Cerrar Sesión" : "Iniciar Sesión";
    $nif = $cliente->nif;  //en este caso se define el nif como el nif del cliente, con lo que al hacer el JOIN en la consulta a BD, sólo sus pedidos aparecen, acotando la busqueda.
    //$orden = isset($_POST['orden']) ? htmlentities($_POST['orden'], ENT_QUOTES,'utf-8') : 'sin criterio';
    $total_prods = isset($_SESSION['cesta']) ? count($_SESSION['cesta']) : 0;
    $pedidosArray = pedidos_usuario($nif);
    $checked = isset($_POST['pedido_select']) ? $_POST['pedido_select'] : [];   
    $id_pedido = isset($_POST['id_pedido']) ? htmlentities($_POST['id_pedido'],  ENT_QUOTES, "UTF-8") : '';
    $total_kg = isset($_POST['total_kg']) ? htmlentities($_POST['total_kg'],  ENT_QUOTES, "UTF-8") : '';
    $coste_envio = isset($_POST['coste_envio']) ? htmlentities($_POST['coste_envio'],  ENT_QUOTES, "UTF-8") : '';
    $total_pedido = isset($_POST['total_pedido']) ? htmlentities($_POST['total_pedido'],  ENT_QUOTES, "UTF-8") : '';
    $forma_pago = isset($_POST['forma_pago']) ? $_POST['forma_pago'] : '';
    $estado_pago = isset($_POST['estado_pago']) ? htmlentities($_POST['estado_pago'],  ENT_QUOTES, "UTF-8") : '';
    $estado_pedido = isset($_POST['estado_pedido']) ? htmlentities($_POST['estado_pedido'],  ENT_QUOTES, "UTF-8") :'';
    $creado_fecha = isset($_POST['creado_fecha']) ? htmlentities($_POST['creado_fecha'],  ENT_QUOTES, "UTF-8") : '';
    $pagado_fecha = isset($_POST['pagado_fecha']) ? htmlentities($_POST['pagado_fecha'],  ENT_QUOTES, "UTF-8") : '';
    $enviado_fecha = isset($_POST['enviado_fecha']) ? htmlentities($_POST['enviado_fecha'],  ENT_QUOTES, "UTF-8") : '';
    $entregado_fecha = isset($_POST['entregado_fecha']) ? htmlentities($_POST['entregado_fecha'],  ENT_QUOTES, "UTF-8") : '';
    $cancelado_fecha = isset($_POST['cancelado_fecha']) ? htmlentities($_POST['cancelado_fecha'],  ENT_QUOTES, "UTF-8") : '';
    $notas = isset($_POST['notas']) ? htmlentities($_POST['notas'],  ENT_QUOTES, "UTF-8") : '';
    //$orden = isset($_POST['orden']) ? $_POST['orden'] : '';
   // $orden = isset($_POST['orden']) ? htmlentities($_POST['orden'],  ENT_QUOTES, "UTF-8") : '';
    $mensaje='';
    //$where será un string modificable al que iré anexando cada campo en caso de que no esté vacío. Es decir, tendrá una longitud variable.
    $where = "WHERE id_pedido LIKE '%$id_pedido%' AND nif_cliente LIKE '%$nif%'";
    $selected_est_pago = '';
    $id_factura = '';
    $id_albaran = '';
    $filtro = isset($_POST['filtro']) ? $_POST['cancelado_fecha'] : 0;


	global $twig;

    if (isset($_POST["buscar"])){ 
        //$where = "WHERE id_pedido LIKE '%$id_pedido%' ";
        if($total_kg != '')$where .= "AND total_kg LIKE '%$total_kg%'";
        if($coste_envio != '')$where .= "AND coste_envio LIKE '%$coste_envio%'";
        if($total_pedido != '')$where .= "AND total_pedido LIKE '%$total_pedido%'";
        if($forma_pago != ''){$where .= "AND forma_pago LIKE '%$forma_pago%'"; $selected_est_pago = 'selected';}
        if($estado_pago != ''){$where .= "AND estado_pago LIKE '%$estado_pago%'"; $selected_est_pago = 'selected';}
        if($estado_pedido != ''){$where .= "AND estado_pedido LIKE '%$estado_pedido%'";$selected_est_pago = 'selected';}
        if($creado_fecha != '')$where .= "AND creado_fecha LIKE '%$creado_fecha%'";
        if($pagado_fecha != '')$where .= "AND pagado_fecha LIKE '%$pagado_fecha%'";
        if($enviado_fecha != '')$where .= "AND enviado_fecha LIKE '%$enviado_fecha%'";
        if($entregado_fecha != '')$where .= "AND entregado_fecha LIKE '%$entregado_fecha%'";
        if($cancelado_fecha != '')$where .= "AND cancelado_fecha LIKE '%$cancelado_fecha%'";
        if($notas != '')$where .= "AND notas LIKE '%$notas%'";

        $pedidosArray = pedidos_busqueda($where); 
                            //Genero lista de pedidos con el where según criterios de búsqueda combinados
         $mensaje = "Encontrados ".count($pedidosArray). " pedidos";
        }
        
    
        if (isset($_POST["marcar_recibido"])) {
            $contador = 0;
            //If intval($checked == 1 significa que el array "checked" tiene al menos un valor dentro, con lo que hay algún pedido seleccionado)
            if(intval($checked) == 1){            
                foreach($checked as $id_pedido){
                    $situacion_pedido = situacion_pedido($id_pedido); 
                    if($situacion_pedido->estado_pedido =='enviado'){
                    $entregado_fecha = date('Y-m-d H:i:s');
                    $cuenta = pedido_entregado($id_pedido, $entregado_fecha);
                    $contador +=$cuenta;
                    $mensaje = $contador. " Pedidos marcados como 'Recibido'";
                }else $mensaje = "Alguno de los pedidos no puede marcarse como recibido, ya que se encuentra cancelado o pendiente de envío/pago";
            }
                $delay=3;
                header("Refresh:$delay");    
            }
        else $mensaje = "Por favor, seleccione al menos un producto para modificar";
        }

    //Para uso en caso de pedidos pendientes de pago y/o envio.
    if (isset($_POST["cancelar_pedido"])) {
        $contador = 0;
        $contador1 = 0;
        $contador2 = 0;
        $contador3= 0;
        //If intval($checked) == 1 significa que el array "checked" tiene al menos un valor dentro, con lo que hay algún pedido seleccionado)
        if(intval($checked) == 1){            
            foreach($checked as $val){
                $situacion_pedido = situacion_pedido($val); 
                //solo podrá anularse el pedido, si está procesandose o devuelto (una vez se haya recibido de vuelta la mercancia)
                if($situacion_pedido->estado_pedido =='procesando'){
                    $cancelado_fecha = date('Y-m-d H:i:s');
                    if($situacion_pedido->estado_pago =='pendiente'){
                    $estado_pago_nuevo = 'pendiente';
                    $cuenta = pedido_cancelado($val, $cancelado_fecha, $estado_pago_nuevo);
                    $contador +=$cuenta;
                    $mensaje = $contador. " Pedidos Cancelados "; }
                        
                //solo podrá anularse la factura, si esta existe, osea está marcado como pagado y además se encuentre como procesando o como devuelto(la mercancia)
                //Aunque es a priori extraño que un pedido se haya entregado sin pagarse, podrían ser pagos a 90 días por parte de empresas
                    elseif($situacion_pedido->estado_pago == 'pagado'){
                    $estado_pago_nuevo = 'devolución';
                    $cuenta = pedido_cancelado($val, $cancelado_fecha, $estado_pago_nuevo);
                    $contador +=$cuenta;
                    $mensaje = $contador. " Pedidos Cancelados "; 
                    $id_fact_activa = factura_activa($val)->id_factura; //para obtener la ID de la factura cuyo estado es activo, y asi solo cancelar dicha fact.
                    $rectif = 'RECTIF_'.$id_fact_activa;
                    $cuenta1 = factura_cancelada($id_fact_activa, $rectif, $cancelado_fecha); 
                    $contador1 += $cuenta1;
                    facturacion_cancelada($id_fact_activa, $rectif); 
                    //$mensaje .= $contador1. " Facturas Canceladas ";
                    $fact_activa = datos_factura($id_fact_activa);
                    //En esta factura, el argumento "creado_fecha" se le dará el valor de la fecha actual, que en este caso está guardado en "$cancelado_fecha"
                    $cuenta3 = factura_rectif($rectif, $fact_activa->id_pedido, $fact_activa->nif, $fact_activa->nombre, $fact_activa->direccion, $fact_activa->localidad, 
                                                $fact_activa->cod_postal, $fact_activa->provincia, (-$fact_activa->total_mercancia), (-$fact_activa->coste_envio), 
                                                (-$fact_activa->base_imponible), (-$fact_activa->iva), (-$fact_activa->total_pedido), $fact_activa->forma_pago, 
                                                $cancelado_fecha, $fact_activa->contenido); 
                    $contador3 += $cuenta3;
                    $mensaje .= $contador3. " Facturas Rectificativas Creadas";

                    $pedido = items_pedido($val);
                        foreach($pedido as $prod){
                        $cuenta2 = actualizar_stock($prod->id_prod, $prod->cantidad, 'sumar');
                        $contador2 +=$cuenta2;     
                        //$mensaje .= $contador2. " Stock Actualizados ";        
                            }                
                }
            } else $mensaje = "Alguno de los pedidos seleccionados no puede marcarse como devuelto debido al estado en que se encuentra";
        }
            $delay=3;
            header("Refresh:$delay");
            
        }else $mensaje = "Por favor, seleccione al menos un pedido para modificar";   
    }

    
    if (isset($_POST["devolver_pedido"])) {
        $checked = isset($_POST['pedido_select']) ? $_POST['pedido_select'] : []; 
        if(intval($checked) == 1){      
            $contador = 0;      
            foreach($checked as $id_pedido){
                $situacion_pedido = situacion_pedido($id_pedido); 
                if($situacion_pedido->estado_pedido =='entregado'){
                    $cancelado_fecha = date('Y-m-d H:i:s');
                    $cuenta = devolver_pedido($id_pedido, $cancelado_fecha);
                    $contador +=$cuenta;
                    $mensaje = "Se solicitó la devolución de " . $contador. " pedido - Gestionaremos su reembolso en cuanto recibamos los productos";
            }else $mensaje = "Debe recibir su pedido para solicitar la devolución - Si ya lo tiene, por favor márquelo como 'recibido' y podrá solicitar el reembolso.";
        }
            $delay=3;
            header("Refresh:$delay");    
        }
    else $mensaje = "Por favor, seleccione al menos un producto para modificar";
    }


   
    if (isset($_POST["cerrar_sesion"])){
        exit(header('location:cerrar_sesion'));
    }
    $template = $twig->load('mis_pedidos.html');
	echo $template->render(array ('URI'=>$URI, 'usuario' =>$usuario, 'cliente'=> $cliente, 'logged'=>$logged, 'mensaje'=>$mensaje, 'total_prods'=>$total_prods, 'logged_legible'=>$logged_legible, 'pedidosArray' => $pedidosArray));

}

function controlador_pedidos()   
{   $URI = get_URI();
    $usuario = checkSession();
    //Con las siguientes 2 lineas restrinjo el acceso a partes solo destinadas a empleados
    $base = checkDomain($usuario);   
    if ($base != 'base_empl') exit(header('location:iniciar_sesion'));
    $empleado = datos_empleado($usuario);
    $funcion_admon = '';
    if ($empleado->tipo_cuenta =='almacen')$funcion_admon = 'hidden';
    $lista_pedidos = lista_pedidos();
    $checked = isset($_POST['pedido_select']) ? $_POST['pedido_select'] : [];
    $id_pedido = isset($_POST['id_pedido']) ? htmlentities($_POST['id_pedido'],  ENT_QUOTES, "UTF-8") : '';
    $nif = isset($_POST['nif']) ? htmlentities($_POST['nif'],  ENT_QUOTES, "UTF-8") : '';  //en este caso el DNI es otro campo de búsqueda porque lo utiliza el administrador
    $total_kg = isset($_POST['total_kg']) ? htmlentities($_POST['total_kg'],  ENT_QUOTES, "UTF-8") : '';
    $coste_envio = isset($_POST['coste_envio']) ? htmlentities($_POST['coste_envio'],  ENT_QUOTES, "UTF-8") : '';
    $total_pedido = isset($_POST['total_pedido']) ? htmlentities($_POST['total_pedido'],  ENT_QUOTES, "UTF-8") : '';
    $forma_pago = isset($_POST['forma_pago']) ? $_POST['forma_pago'] : '';
    $estado_pago = isset($_POST['estado_pago']) ? htmlentities($_POST['estado_pago'],  ENT_QUOTES, "UTF-8") : '';
    $estado_pedido = isset($_POST['estado_pedido']) ? htmlentities($_POST['estado_pedido'],  ENT_QUOTES, "UTF-8") :'';
    $creado_fecha = isset($_POST['creado_fecha']) ? htmlentities($_POST['creado_fecha'],  ENT_QUOTES, "UTF-8") : '';
    $pagado_fecha = isset($_POST['pagado_fecha']) ? htmlentities($_POST['pagado_fecha'],  ENT_QUOTES, "UTF-8") : '';
    $enviado_fecha = isset($_POST['enviado_fecha']) ? htmlentities($_POST['enviado_fecha'],  ENT_QUOTES, "UTF-8") : '';
    $entregado_fecha = isset($_POST['entregado_fecha']) ? htmlentities($_POST['entregado_fecha'],  ENT_QUOTES, "UTF-8") : '';
    $cancelado_fecha = isset($_POST['cancelado_fecha']) ? htmlentities($_POST['cancelado_fecha'],  ENT_QUOTES, "UTF-8") : '';
    $notas = isset($_POST['notas']) ? htmlentities($_POST['notas'],  ENT_QUOTES, "UTF-8") : '';
    //$orden = isset($_POST['orden']) ? $_POST['orden'] : '';
   // $orden = isset($_POST['orden']) ? htmlentities($_POST['orden'],  ENT_QUOTES, "UTF-8") : '';
    $mensaje='';
    //$where será un string modificable al que iré anexando cada campo en caso de que no esté vacío. Es decir, tendrá una longitud variable.
    $where = "WHERE id_pedido LIKE '%$id_pedido%' ";
    $selected_est_pago = '';
    $id_factura = '';
    $id_albaran = '';
    $rectif = '';
    $nif_cliente = '';

    //PENDIENTE QUE EL FILTRO DE PEDIDOS FUNCIONE
    $filtro = isset($_POST['filtro']) ? $_POST['cancelado_fecha'] : 0;

 

    if (isset($_POST["buscar"])){ 
        //$where = "WHERE id_pedido LIKE '%$id_pedido%' ";
        if($nif != '') $where .= "AND nif_cliente LIKE '%$nif%'";
        if($total_kg != '')$where .= "AND total_kg LIKE '%$total_kg%'";
        if($coste_envio != '')$where .= "AND coste_envio LIKE '%$coste_envio%'";
        if($total_pedido != '')$where .= "AND total_pedido LIKE '%$total_pedido%'";
        if($forma_pago != ''){$where .= "AND forma_pago LIKE '%$forma_pago%'"; $selected_est_pago = 'selected';}
        if($estado_pago != ''){$where .= "AND estado_pago LIKE '%$estado_pago%'"; $selected_est_pago = 'selected';}
        if($estado_pedido != ''){$where .= "AND estado_pedido LIKE '%$estado_pedido%'";$selected_est_pago = 'selected';}
        if($creado_fecha != '')$where .= "AND creado_fecha LIKE '%$creado_fecha%'";
        if($pagado_fecha != '')$where .= "AND pagado_fecha LIKE '%$pagado_fecha%'";
        if($enviado_fecha != '')$where .= "AND enviado_fecha LIKE '%$enviado_fecha%'";
        if($entregado_fecha != '')$where .= "AND entregado_fecha LIKE '%$entregado_fecha%'";
        if($cancelado_fecha != '')$where .= "AND cancelado_fecha LIKE '%$cancelado_fecha%'";
        if($notas != '')$where .= "AND notas LIKE '%$notas%'";

        $lista_pedidos = pedidos_busqueda($where); } 
                         //Genero lista de pedidos con el where según criterios de búsqueda combinados
        $mensaje = "Encontrados ".count($lista_pedidos). " pedidos";

    if (isset($_POST["marcar_pagado"])) {
            $contador = 0;
            $contador1 = 0;
            $contador2 = 0;
            //If intval($checked == 1 significa que el array "checked" tiene al menos un valor dentro, con lo que hay algún pedido seleccionado)
            if(intval($checked) == 1){
                foreach($checked as $id_pedido){
                    $contenido = [];
                    $situacion_pedido = situacion_pedido($id_pedido); 
                    //solo podrá anularse el pedido, si está procesandose o devuelto (una vez se haya recibido de vuelta la mercancia)
                    if($situacion_pedido->estado_pago == 'pendiente'){                     
                        $pagado_fecha = date('Y-m-d H:i:s');
                        $fecha = date('dmY_his');
                        //obtengo datos del pedido para la factura, de la BBDD
                        $pedido = datos_pedido($id_pedido);
                        $nif_cliente = $pedido->nif_cliente; //NECESITO ESTE DATO PARA INCORPORARLO A LAS FACTURAS al marcar como pagado
                        $id_factura = 'FAC_'.$fecha.'/'.$contador; //Así aseguro que, aunque marque como pagados 2 pedidos a la vez, el ID factura y albaran sean unicos.
                        $id_albaran = 'ALB_'.$fecha.'/'.$contador; 
                        $cuenta = pedido_pagado($id_pedido, $pagado_fecha);
                        $contador +=$cuenta; 
                        //obtengo datos del cliente para la factura, de la BBDD
                        $cliente = datos_cliente_nif($nif_cliente);
                        $nombre = $cliente->nombre ." ". $cliente->apellidos;    
                        $total_mercancia = $pedido->total_mercancia;
                        $coste_envio = $pedido->coste_envio;
                        $total_pedido = $pedido->total_pedido;
                        $iva = $total_pedido*0.21;
                        $base_imponible = $total_pedido-$iva; 
    
                        //Obtengo detalles de cada pedido para ahondar en los productos que lo componen, y modificar su stock, en el momento de marcar como pagado
                        $pedido_items = items_pedido($id_pedido);               
                        foreach($pedido_items as $prod){
                            $contenido[] = $prod; 
                            $cuenta2 = actualizar_stock($prod->id_prod, $prod->cantidad, 'restar');
                            $contador2 +=$cuenta2;  
                            }
                        $contenido = serialize($contenido); //CONVIERTO EL ARRAY EN TEXTO PARA PODER GUARDARLO EN BBDD
                        $cuenta1 = factura_creada($id_factura, $id_pedido, $nif_cliente, $nombre, $cliente->direccion, $cliente->localidad, $cliente->cod_postal, $cliente->provincia,
                        $total_mercancia, $coste_envio, $base_imponible, $iva, $total_pedido, $pedido->forma_pago, $pagado_fecha, $contenido);    
                        $contador1 +=$cuenta1; 
                        //Debe actualizarse facturacion tras factura para ir de acuerdo al FK_facturacion_factura
                        facturacion_creada($id_pedido, $id_factura, $id_albaran,$nif_cliente);  

                    } else $mensaje = "Alguno de los pedidos seleccionados no puede marcarse como pagado por el estado en que se encuentra";
                   
                }   
                $mensaje = $contador. " Pedidos Pagados - ".$contador2. " Stock Actualizados -". $contador1." Facturas Generadas";  
                $delay=3;
                header("Refresh:$delay");

            }else $mensaje = "Por favor, seleccione al menos un pedido para modificar";   


        }        

    if (isset($_POST["marcar_enviado"])) {
            $contador = 0;
            //If intval($checked == 1 significa que el array "checked" tiene al menos un valor dentro, con lo que hay algún pedido seleccionado)
            if(intval($checked) == 1){                
                foreach($checked as $id_pedido){
                    $situacion_pedido = situacion_pedido($id_pedido); 
                    //solo podrá anularse el pedido, si está procesandose o devuelto (una vez se haya recibido de vuelta la mercancia)
                    if($situacion_pedido->estado_pago =='pagado' && $situacion_pedido->estado_pedido =='procesando'){
                    $enviado_fecha = date('Y-m-d H:i:s');
                    $cuenta = pedido_enviado($id_pedido, $enviado_fecha);
                    $contador +=$cuenta;
                    $mensaje = $contador. " Pedidos marcados como 'Enviado'";
                    }else $mensaje = "Alguno de los pedidos no puede marcarse como enviado debido al estado en que se encuentra";
                }
                $delay=3;
                header("Refresh:$delay");

            }
    else $mensaje = "Por favor, seleccione al menos un pedido para modificar";

        }

    if (isset($_POST["marcar_entregado"])) {
        $contador = 0;
        //If intval($checked == 1 significa que el array "checked" tiene al menos un valor dentro, con lo que hay algún pedido seleccionado)
        if(intval($checked) == 1){            
            foreach($checked as $id_pedido){
                $situacion_pedido = situacion_pedido($id_pedido); 
                if($situacion_pedido->estado_pedido =='enviado'){
                $entregado_fecha = date('Y-m-d H:i:s');
                $cuenta = pedido_entregado($id_pedido, $entregado_fecha);
                $contador +=$cuenta;
                $mensaje = $contador. " Pedidos marcados como 'Entregado'";
            }else $mensaje = "Alguno de los pedidos no puede marcarse como entregado, ya que se encuentra cancelado o pendiente de envío/pago";
        }
            $delay=3;
            header("Refresh:$delay");

        }
    else $mensaje = "Por favor, seleccione al menos un pedido para modificar";
    }

    if (isset($_POST["mercancia_devuelta"])) {
        $contador = 0;
        $contador1 = 0;
        $contador2 = 0;
        $contador3= 0;
        //If intval($checked == 1 significa que el array "checked" tiene al menos un valor dentro, con lo que hay algún pedido seleccionado)
        if(intval($checked) == 1){            
            foreach($checked as $val){
                $situacion_pedido = situacion_pedido($val); 
                //solo podrá anularse el pedido, si está procesandose o devuelto (una vez se haya recibido de vuelta la mercancia)
                if($situacion_pedido->estado_pedido =='solicitud_devolucion'){
                    $cancelado_fecha = date('Y-m-d H:i:s');
                    $cuenta = mercancia_devuelta($val);
                    $contador +=$cuenta;
                    //Recorremos cada pedido modificado, para actualizar el stock de cada producto que lo compone
                    $pedido = items_pedido($val);
                        foreach($pedido as $prod){
                        $cuenta2 = actualizar_stock($prod->id_prod, $prod->cantidad, 'sumar');
                        $contador2 +=$cuenta2;     
                        $mensaje = $contador. " Pedidos marcados como Devueltos ".$contador2. " Stock Actualizados ";        
                            }                

                    //solo podrá anularse la factura, si esta existe, osea está marcado como pagado y además se encuentre como procesando o como devuelto(la mercancia)
                    if($situacion_pedido->estado_pago == 'pagado'){
                        $id_fact_activa = factura_activa($val)->id_factura; //para obtener la ID de la factura cuyo estado es activo, y asi solo cancelar dicha fact.
                        $rectif = 'RECTIF_'.$id_fact_activa;
                        $cuenta1 = factura_cancelada($id_fact_activa, $rectif, $cancelado_fecha); 
                        facturacion_cancelada($id_fact_activa, $rectif); 
                        $contador1 += $cuenta1;
                        $mensaje .= $contador1. " Facturas Canceladas";
                        $fact_activa = datos_factura($id_fact_activa);
                        //En esta factura, el argumento "creado_fecha" se le dará el valor de la fecha actual, que en este caso está guardado en "$cancelado_fecha"
                        $cuenta3 = factura_rectif($rectif, $fact_activa->id_pedido, $fact_activa->nif, $fact_activa->nombre, $fact_activa->direccion, $fact_activa->localidad, 
                                                    $fact_activa->cod_postal, $fact_activa->provincia, (-$fact_activa->total_mercancia), (-$fact_activa->coste_envio), 
                                                    (-$fact_activa->base_imponible), (-$fact_activa->iva), (-$fact_activa->total_pedido), $fact_activa->forma_pago, 
                                                    $cancelado_fecha, $fact_activa->contenido); 
                        $contador3 += $cuenta3;
                        $mensaje .= $contador3. " Facturas Rectificativas Creadas ";
                    }
                } else $mensaje = "Alguno de los pedidos seleccionados no puede marcarse como devuelto debido al estado en que se encuentra";
            }

                $delay=3;
                header("Refresh:$delay");
                
            }else $mensaje = "Por favor, seleccione al menos un pedido para modificar";   

    }
    //Para uso en caso de pedidos pendientes de pago y/o envio.
    if (isset($_POST["cancelar_pedido"])) {
        $contador = 0;
        $contador1 = 0;
        $contador2 = 0;
        $contador3= 0;
        //If intval($checked) == 1 significa que el array "checked" tiene al menos un valor dentro, con lo que hay algún pedido seleccionado)
        if(intval($checked) == 1){            
            foreach($checked as $val){
                $situacion_pedido = situacion_pedido($val); 
                //solo podrá anularse el pedido, si está procesandose o devuelto (una vez se haya recibido de vuelta la mercancia)
                if($situacion_pedido->estado_pedido =='procesando'){
                    $cancelado_fecha = date('Y-m-d H:i:s');
                    if($situacion_pedido->estado_pago =='pendiente'){
                    $estado_pago_nuevo = 'pendiente';
                    $cuenta = pedido_cancelado($val, $cancelado_fecha, $estado_pago_nuevo);
                    $contador +=$cuenta;
                    $mensaje = $contador. " Pedidos Cancelados "; }
                        
                //solo podrá anularse la factura, si esta existe, osea está marcado como pagado y además se encuentre como procesando o como devuelto(la mercancia)
                //Aunque es a priori extraño que un pedido se haya entregado sin pagarse, podrían ser pagos a 90 días por parte de empresas
                    elseif($situacion_pedido->estado_pago == 'pagado'){
                    $estado_pago_nuevo = 'devolución';
                    $cuenta = pedido_cancelado($val, $cancelado_fecha, $estado_pago_nuevo);
                    $contador +=$cuenta;
                    $mensaje = $contador. " Pedidos Cancelados "; 
                    $id_fact_activa = factura_activa($val)->id_factura; //para obtener la ID de la factura cuyo estado es activo, y asi solo cancelar dicha fact.
                    $rectif = 'RECTIF_'.$id_fact_activa;
                    $cuenta1 = factura_cancelada($id_fact_activa, $rectif, $cancelado_fecha); 
                    $contador1 += $cuenta1;
                    facturacion_cancelada($id_fact_activa, $rectif); 
                    $mensaje .= $contador1. " Facturas Canceladas ";
                    $fact_activa = datos_factura($id_fact_activa);
                    //En esta factura, el argumento "creado_fecha" se le dará el valor de la fecha actual, que en este caso está guardado en "$cancelado_fecha"
                    $cuenta3 = factura_rectif($rectif, $fact_activa->id_pedido, $fact_activa->nif, $fact_activa->nombre, $fact_activa->direccion, $fact_activa->localidad, 
                                                $fact_activa->cod_postal, $fact_activa->provincia, (-$fact_activa->total_mercancia), (-$fact_activa->coste_envio), 
                                                (-$fact_activa->base_imponible), (-$fact_activa->iva), (-$fact_activa->total_pedido), $fact_activa->forma_pago, 
                                                $cancelado_fecha, $fact_activa->contenido); 
                    $contador3 += $cuenta3;
                    $mensaje .= $contador3. " Facturas Rectificativas Creadas";

                    $pedido = items_pedido($val);
                        foreach($pedido as $prod){
                        $cuenta2 = actualizar_stock($prod->id_prod, $prod->cantidad, 'sumar');
                        $contador2 +=$cuenta2;     
                        $mensaje .= $contador2. " Stock Actualizados ";        
                            }                
                }
            } else $mensaje = "Alguno de los pedidos seleccionados no puede marcarse como devuelto debido al estado en que se encuentra";
        }
            $delay=3;
            header("Refresh:$delay");
            
        }else $mensaje = "Por favor, seleccione al menos un pedido para modificar";   

    }
    if (isset($_POST["reactivar_pedido"])) {
        $contador = 0;
        //If intval($checked == 1 significa que el array "checked" tiene al menos un valor dentro, con lo que hay algún pedido seleccionado)
        if(intval($checked) == 1){            
            foreach($checked as $id_pedido){
                $situacion_pedido = situacion_pedido($id_pedido); 
                if($situacion_pedido->estado_pedido =='cancelado'){
                $cuenta = reactivar_pedido($id_pedido);
                $contador +=$cuenta;
                $mensaje = $contador. " Pedidos Reactivados";
            }else $mensaje = "Alguno de los pedidos no puede reactivarse debido al estado en que se encuentra";
        }
            $delay=3;
            header("Refresh:$delay");

        }
    else $mensaje = "Por favor, seleccione al menos un pedido para modificar";
    }


    if (isset($_POST["borrar_cancelados"])) {
        $contador = borrar_cancelados();
        $delay=3;
        header("Refresh:$delay");
        $mensaje = $contador. " pedidos borrados";
    }
    
    global $twig;
    $template = $twig->load('control_pedidos.html');
	echo $template->render(array ('URI'=>$URI, 'usuario'=>$usuario, 'empleado'=>$empleado, 'funcion_admon'=>$funcion_admon,'factura'=>$id_factura, 'id_albaran'=>$id_albaran, 'rectif'=>$rectif, 'where'=>$where, 'id_pedido'=>$id_pedido, 'nif'=> $nif, 'total_pedido'=>$total_pedido, 'total_kg'=>$total_kg, 'coste_envio'=>$coste_envio, 
    'forma_pago'=>$forma_pago,'estado_pago'=>$estado_pago, 'estado_pedido'=>$estado_pedido, 'creado_fecha'=>$creado_fecha, 'pagado_fecha'=>$pagado_fecha, 'enviado_fecha'=>$enviado_fecha,
    'entregado_fecha'=>$entregado_fecha, 'cancelado_fecha'=>$cancelado_fecha, 'notas'=>$notas,'lista_pedidos'=>$lista_pedidos, 'mensaje'=>$mensaje));

}

function controlador_cuentas()  
{   $URI = get_URI();
    $usuario = checkSession();
    $empleado = datos_empleado($usuario);
    if ($empleado->tipo_cuenta != 'admon'){exit(header('location:iniciar_sesion'));} //solo ADMON puede manipular cuentas de usuario
    $lista_cuentas = lista_cuentas();
    $checked = isset($_POST['cuenta_select']) ? $_POST['cuenta_select'] : [];

    $email= isset($_POST['email']) ? $_POST['email'] : '';
    $nif= isset($_POST['nif']) ? $_POST['nif'] : '';
    $nombre= isset($_POST['nombre']) ? $_POST['nombre'] : '';
    $apellidos= isset($_POST['apellidos']) ? $_POST['apellidos'] : '';
    
    $creado_fecha = isset($_POST['creado_fecha']) ? $_POST['creado_fecha'] : '';
    $orden = isset($_POST['orden']) ? $_POST['orden'] : '';
    $nuevo_estado='';
   // $orden = isset($_POST['orden']) ? htmlentities($_POST['orden'],  ENT_QUOTES, "UTF-8") : '';
    $mensaje = "";

    if (isset($_POST["buscar"]) && $_POST['email']!='') {
        $lista_cuentas = lista_cuentas_email($_POST['email']);
        $mensaje = "Encontrados ". count($lista_cuentas). " cuentas cuyo EMAIL es '$email'";}  


    if (isset($_POST["activar_cuenta"])) {
        $contador = 0;
        $nuevo_estado = 'activo';
        if(intval($checked) == 1){
            foreach($checked as $cuenta){
                $cuenta = modificar_cuenta($cuenta, $nuevo_estado);
                $contador +=$cuenta;
            }
            $delay=3;
            header("Refresh:$delay");
            $mensaje = $contador. " cuentass ACTIVADAS";
        }
    else $mensaje = "Por favor, seleccione al menos una cuenta para activar";

        }        

    if (isset($_POST["desactivar_cuenta"])) {
            $contador = 0;
            $nuevo_estado = 'inactivo';
            if(intval($checked) == 1){
               foreach($checked as $cuenta){
                    $cuenta = modificar_cuenta($cuenta, $nuevo_estado);
                    $contador +=$cuenta;
               }
                $delay=3;
                header("Refresh:$delay");
                $mensaje = $contador. " cuentass DESACTIVADAS";
            }
    else $mensaje = "Por favor, seleccione al menos una cuenta para desactivar";

        }        

    if (isset($_POST["bloquear_cuenta"])) {
            $contador = 0;
            $nuevo_estado = 'bloqueado';
            if(intval($checked) == 1){
                foreach($checked as $cuenta){
                    $cuenta = modificar_cuenta($cuenta, $nuevo_estado);
                    $contador +=$cuenta;
                }
                $delay=3;
                header("Refresh:$delay");
                $mensaje = $contador. " cuentass BLOQUEADAS";
            }
            else $mensaje = "Por favor, seleccione al menos una cuenta para bloquear";

        }

    if (isset($_POST["eliminar_cuenta"])) {
        $contador = 0;
        if(intval($checked) == 1){
            foreach($checked as $cuenta){
                $cuenta = eliminar_cuenta($cuenta);
                $contador +=$cuenta;
            }
            $delay=3;
            header("Refresh:$delay");
            $mensaje = $contador. " cuentas eliminadas";
        }
    else $mensaje = "Por favor, seleccione al menos una cuenta para eliminar";

    }

    if (isset($_POST["alta_empleados"])) exit(header('location:alta_empleado'));

    global $twig;
    $template = $twig->load('control_cuentas.html');
	echo $template->render(array ('URI'=>$URI, 'usuario' =>$usuario, 'empleado'=>$empleado, 'email'=> $email, 'lista_cuentas'=>$lista_cuentas, 'mensaje'=>$mensaje));

}

function controlador_iniciar_sesion(){
    session_start();
    $URI = get_URI();
    $lista_users = lista_users();// extraigo datos de la BBDD
    $lista_users = array_column(lista_users(), 'email'); //extraigo solo el dato "email" de array multidimensional
    $estado = "";
    $pass="";
    $mensaje = "";
    $usuario = isset($_POST["usuario"]) ? htmlspecialchars(strtolower($_POST["usuario"])) : ""; //normalizo en minusculas el email
    $contrasena = isset($_POST["contrasena"]) ? htmlspecialchars($_POST["contrasena"]) : "";
    $dominio_empleado = 'frutasdelvalle.com';

    if (isset($_POST["entrar"])) {
        //Procesar formulario
         //Si no están vacíos, se llama a Base de Datos para obtener contraseña, estado de la cuenta y tipo de cuenta
        if (($usuario != "") && ($contrasena != "")) {

            $dominio = ltrim(strstr($usuario, '@'), '@');    //con esto obtengo lo que va tras la @ para conocer si es un mail de empleado o no
                //Si el usuario está en la lista de usuarios (tanto cliente como empleado):
                if(in_array($usuario, $lista_users)){
                    if ($dominio === $dominio_empleado) {$user = datos_empleado($usuario);}
                    else {$user = datos_cliente($usuario);}  
                       // echo 'USUARIO EN LISTADO'; 
                        $pass = $user->contrasena;
                        $estado = $user->estado_cuenta;
                        $tipo_cuenta = $user->tipo_cuenta;

                    if ($estado === 'activo' ){
                    if(password_verify($contrasena, $pass)){ 
                        //Login correcto
                        //Establecer sesion autentificada
                        $_SESSION["usuario"] = $usuario;    
                        //$_SESSION["contrasena"] = $contrasena;
                        session_regenerate_id(); //para evitar ataque de fijacion de sesion (en redes compartidas)

                        //Según tipo de cuenta, se redirecciona a una página u otra
                        switch($tipo_cuenta){
                            case 'cliente': exit(header("location:mi_cuenta"));
                            case 'admon': exit(header("location:home_admon"));
                            case 'almacen': exit(header("location:home_almacen"));
                            }
                  } else $mensaje = "Usuario y/o contraseña incorrectos";   
                    
                } elseif ($estado === 'pendiente') {
                                $mensaje = "Por favor, confirme su cuenta de usuario a través del email que recibió";
                                
                } elseif ($estado === 'bloqueado') {
                                $mensaje = "Su cuenta ha sido bloqueada - por favor, contacte con nosotros";
                }               
            }else {$mensaje = "Usuario y/o contraseña incorrectos";}  //$mensaje = "Usuario no está en BD";
                    
        } else $mensaje = "Los campos usuario y contraseña son obligatorios";  // si uno de los 2 campos o ambos están vacíos                              
        

    } else if (isset($_SESSION["usuario"])) {
        //Sesión ya iniciada
        $usuario = $_SESSION["usuario"];
        //$contrasena = $_SESSION["contrasena"];
        $mensaje = "";
    } else {
        //Carga en vacío sin sesión iniciada
        $usuario = "";
        //$contrasena = "";
        $mensaje = "";
        $pass = '';
        $estado = '';
        $tipo_cuenta = '';

    }    

	global $twig;
    $template = $twig->load('iniciar_sesion.html');
    echo $template->render(array ('URI'=>$URI, 'usuario' => $usuario, 'mensaje' =>$mensaje));
}

function controlador_mi_cuenta()
{   $URI = get_URI();
    $usuario = checkSession();
    $cliente = datos_cliente($usuario);
    $logged = isset($_SESSION['usuario']) ? "cerrar_sesion" : "iniciar_sesion";
    $logged_legible = isset($_SESSION['usuario']) ? "Cerrar Sesión" : "Iniciar Sesión";
    $total_prods = isset($_SESSION['cesta']) ? count($_SESSION['cesta']) : 0;



    if (isset($_POST["cerrar_sesion"])){
        exit(header('location:cerrar_sesion'));
    }
    global $twig;
    $template = $twig->load('mi_cuenta.html');
	echo $template->render(array ('URI'=>$URI, 'usuario' =>$usuario, 'cliente'=>$cliente, 'logged'=>$logged, 'logged_legible'=>$logged_legible, 'total_prods'=>$total_prods));

}


function controlador_home_admon()
{   $URI = get_URI();
    $usuario = checkSession();
    $empleado = datos_empleado($usuario);
    if ($empleado->tipo_cuenta != 'admon'){exit(header('location:iniciar_sesion'));}
    $mensaje = "";


    if (isset($_POST['backup'])){

        $tablas = ['cliente, empleado, producto, productos_pedido, facturacion, factura'];

        foreach ($tablas as $tabla){
            backup_bbdd($tabla);
        }
    }
    global $twig;
    $template = $twig->load('home_admon.html');
	echo $template->render(array ('URI'=>$URI, 'usuario' =>$usuario, 'empleado'=>$empleado, 'mensaje'=>$mensaje));

}


function controlador_home_almacen()
{   $URI = get_URI();
    $usuario = checkSession();
    $empleado = datos_empleado($usuario);
    if ($empleado->tipo_cuenta != 'almacen'){exit(header('location:iniciar_sesion'));}
    $mensaje = "";


    if (isset($_POST["cerrar_sesion"])){
        exit(header('location:cerrar_sesion'));
    }

    global $twig;
    $template = $twig->load('home_almacen.html');
	echo $template->render(array ('URI'=>$URI, 'usuario' =>$usuario, 'empleado'=>$empleado));

}

function controlador_cerrar_sesion()
{

    // Carga la plantilla que se mostrará al usuario con los datos recuperados del modelo
	global $twig;
    closeSession();
    killCookie();
 
    $template = $twig->load('cerrar_sesion.html');
    echo $template->render(array());  //array vacio obligatorio (aunque no se mande info)

}


function controlador_crear_cuenta()
{   $URI = get_URI();
    $mensaje = "";
    $resultado = "";
    $lista_users = lista_users();
    $lista_emails = array_column(lista_users(), 'email'); //extraigo solo el dato "email" de array multidimensional (todos los emails registrados)
    $dominio_empleado = 'frutasdelvalle.com';
    $total_prods ='';
    $validaciones = array('val_nif'=> '', 'val_nom'=> '', 'val_ape'=>'', 'val_tel'=>'', 'val_email_existe'=>'', 'val_email'=>'', 'val_email_hack'=>'', 
                        'val_dir'=>'','val_loc'=>'','val_postal'=>'', 'val_prov'=>'','val_contrasena'=>'');  
    $datos = [];
    foreach($_POST as $key => $value){
        if(!isset($datos[$key])){
            $datos[$key] = htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
        }
}


    $validaciones= (object) $validaciones; //CONVIERTO A OBJETOS PARA MAYOR FACILIDAD DE USO EN LA PLANTILLA TWIG
    $datos = (object)$datos;

   
    if(isset($_POST['crear_cuenta'])){   
        $dominio = ltrim(strstr($_POST['email'], '@'), '@'); 

        if((val_nif($datos->nif))&&(es_texto($_POST['nombre'])) && (es_texto($_POST['apellidos'])) && 
        (valid_direccion($_POST['direccion'])) && (es_texto($_POST['localidad'])) && (valid_postal($_POST['cod_postal'])) && (es_texto($_POST['provincia'])) 
        && (valid_tel($_POST['telefono'])) && email_existe($_POST['email'], $lista_emails) && (valid_email(strtolower($_POST['email'])))
        && ($dominio != $dominio_empleado) && (valid_contrasena($_POST['contrasena'])) && ($_POST['contrasena'] === $_POST['rep_contrasena'])) 
            {
                    //Con esto consigo que todos los input se filtren para evitar inyeccion de codigo malicioso.

            $creado_fecha = date('Y-m-d H:i:s');
            $resultado = insert_cliente($_POST['nif'], $_POST['nombre'], $_POST['apellidos'], 
            $_POST['email'], $_POST['telefono'], $_POST['direccion'], $_POST['localidad'], $_POST['cod_postal'], $_POST['provincia'],
            $_POST['contrasena'], $creado_fecha); 
            if ($resultado != false) { $mensaje = 'Se ha creado ' . $resultado .' cuenta con éxito'; exit(header('location:registro_correcto')); }     
                
            else $mensaje= "Error al grabar el pedido - por favor, repita el proceso de nuevo";
          
            }
        else{  //reviso los posibles errores de 1 en 1, para poder modificar su validacion individualmente (ya que pueden darse varios fallos simultaneos)
            
            if (!val_nif($datos->nif)){
                $validaciones->val_nif = "NIF - Revise que no haya espacios entre caracteres, o que omitió el cero (0) al inicio";
            }
    
            if (!es_texto($datos->nombre)){
                $validaciones->val_nom = "NOMBRE - Sólo puede incluir caracteres del alfabeto";
            }
            if (!es_texto($datos->apellidos)){
                $validaciones->val_ape="APELLIDOS - Sólo puede incluir caracteres del alfabeto";
            }
            if (!valid_tel($datos->telefono)){ 
                $validaciones->val_tel ="TELEFONO - Debe tener una longitud de 9 cifras, sin prefijo internacional ni separaciones";
            }
    
            if (!valid_direccion($datos->direccion)){
                $validaciones->val_dir="DIRECCION - Sólo puede incluir caracteres del alfabeto y numeros";
            }
            if (!es_texto($datos->localidad)){
                $validaciones->val_loc="LOCALIDAD - Sólo puede incluir caracteres del alfabeto";
            }
    
            if (!valid_postal($datos->cod_postal)){
                $validaciones->val_postal="CÓDIGO POSTAL - Debe tener una longitud de 5 cifras";
            }
    
            if (!es_texto($datos->provincia)){
                $validaciones->val_prov="PROVINCIA - Debe intdroducir una provincia española";
            }

            if (!email_existe($datos->email, $lista_emails)){
                $validaciones->val_email_existe= "EMAIL - El email introducido ya tiene una cuenta registrada";
            }

            if (!valid_email($datos->email)){
                $validaciones->val_email="EMAIL - Debe incluir una dirección de email válida";
            }

            if ($dominio == $dominio_empleado){
                $validaciones->val_email_hack="EMAIL - Debe incluir una dirección de email válida";;  //NO INDICAMOS QUE SE TRATA DEL DOMINIO EMPLEADO NO AYUDAR AL HACKEO
            }

            if (!valid_contrasena($datos->contrasena)){
                $validaciones->val_contrasena ="CONTRASEÑA- Debe tener al menos 8 caracteres, incluyendo mayúsculas, minúsculas, cifras y signos especiales.";
            }
            if($_POST['contrasena'] != $_POST['rep_contrasena']){
                $validaciones->val_contrasena ="Las CONTRASEÑAS no coinciden - por favor, revíselo";
            }

        }
   
    }

    if(isset($_POST['volver_tienda'])){  exit(header('location:index.php'));}

    global $twig;
    $template = $twig->load('crear_cuenta.html');
    echo $template->render(array ('URI'=>$URI, 'validaciones'=>$validaciones, 'datos'=>$datos, 'mensaje'=>$mensaje));	
}


function controlador_alta_empleado()
{   $URI = get_URI();
    $usuario = checkSession();
    $empleado = datos_empleado($usuario);
    $lista_users = lista_users();
    $lista_emails = array_column(lista_users(), 'email'); //extraigo solo el dato "email" de array multidimensional
    if ($empleado->tipo_cuenta != 'admon'){exit(header('location:iniciar_sesion'));}
    $logged = isset($_SESSION['usuario']) ? "cerrar_sesion" : "iniciar_sesion";
    $logged_legible = isset($_SESSION['usuario']) ? "Cerrar Sesión" : "Iniciar Sesión";
    $dominio_empleado = 'frutasdelvalle.com';
    $mensaje = "";
    $resultado = "";


    $validaciones = array('val_nif'=> '', 'val_nom'=> '', 'val_ape'=>'', 'val_email_existe'=>'', 'val_email_empleado'=>'', 'val_tel'=>'', 'val_dir'=>'',
                        'val_loc'=>'','val_postal'=>'', 'val_prov'=>'','val_contrasena'=>'', 'val_tipo'=>'');  
    $datos = [];    
    foreach($_POST as $key => $value){
        if(!isset($datos[$key])){
            $datos[$key] = htmlspecialchars($value, ENT_COMPAT, 'UTF-8'); //aplico htmlspecialchars a cada elemento del array 
        }
    }


    $validaciones= (object) $validaciones; //CONVIERTO A OBJETOS PARA MAYOR FACILIDAD DE USO EN LA PLANTILLA TWIG
    $datos = (object)$datos;

    
    if(isset($_POST['confirmar_alta'])){ 
            //valid_nif($_POST['nif']) && 
        if((val_nif($datos->nif))&&(es_texto($_POST['nombre'])) && (es_texto($_POST['apellidos'])) && 
        (valid_direccion($_POST['direccion'])) && (es_texto($_POST['localidad'])) &&(valid_postal($_POST['cod_postal'])) &&
        (es_texto($_POST['provincia'])) && (valid_tel($_POST['telefono'])) && email_existe($_POST['email'], $lista_emails) && (valid_email_empleado(strtolower($_POST['email']))) && 
        (valid_contrasena($_POST['contrasena'])) && ($_POST['contrasena'] === $_POST['rep_contrasena']) && (!empty($_POST['tipo_cuenta']))){
              
        $creado_fecha = date('Y-m-d H:i:s');

        $resultado = alta_empleado($_POST['nif'], $_POST['nombre'], $_POST['apellidos'], 
        $_POST['email'], $_POST['telefono'], $_POST['direccion'], $_POST['localidad'], $_POST['cod_postal'], $_POST['provincia'],
        $_POST['contrasena'], $creado_fecha, $_POST['tipo_cuenta']);

        if ($resultado != null) { $mensaje = 'Se ha registrado ' . $resultado .' empleado con éxito'; exit(header('location:alta_correcta')); }     
                
        else $mensaje= "Error al grabar el pedido - por favor, repita el proceso de nuevo";
      
    
        }
        //reviso los posibles errores de 1 en 1, para poder modificar su validacion individualmente (ya que pueden darse varios fallos simultaneos)
        //PENDIENTE VALIDADOR DE NIF
    else{  
        if (!val_nif($datos->nif)){
            $validaciones->val_nif = "NIF - Por favor revise que no contenga espacios y la letra sea correcta";
        }

        if (!es_texto($datos->nombre)){
            $validaciones->val_nom = "NOMBRE - Sólo puede incluir caracteres del alfabeto";
        }
        if (!es_texto($datos->apellidos)){
            $validaciones->val_ape="APELLIDOS - Sólo puede incluir caracteres del alfabeto";
        }
        if (!valid_tel($datos->telefono)){ 
            $validaciones->val_tel ="TELEFONO - Debe tener una longitud de 9 cifras, sin prefijo internacional ni separaciones";
        }

        if (!es_texto($datos->direccion)){
            $validaciones->val_dir="DIRECCION - Sólo puede incluir caracteres alfanuméricos";
        }
        if (!es_texto($datos->localidad)){
            $validaciones->val_loc="LOCALIDAD - Sólo puede incluir caracteres del alfabeto";
        }

        if (!valid_postal($datos->cod_postal)){
            $validaciones->val_postal="CÓDIGO POSTAL - Debe tener una longitud de 5 cifras";
        }

        if (!es_texto($datos->provincia)){
            $validaciones->val_prov= "PROVINCIA - Debe seleccionar una provincia española";
        }
        if (!email_existe($datos->email, $lista_emails)){
            $validaciones->val_email_existe= "EMAIL - El email introducido ya tiene una cuenta registrada";
        }

        if (!valid_email_empleado($datos->email)){
            $datos->email = $dominio_empleado; //por defecto sale indicado el dominio de la empresa, para mayor ayuda
            $validaciones->val_email_empleado="EMAIL - Debe incluir una dirección de email válida con extension @frutasdelvalle.com";
        }

        if (!valid_contrasena($datos->contrasena)){
            $validaciones->val_contrasena ="CONTRASEÑA - Debe tener al menos 8 caracteres, incluyendo mayúsculas, minúsculas, cifras y signos especiales.";
        }
        if($_POST['contrasena'] != $_POST['rep_contrasena']){
            $validaciones->val_contrasena ="Las CONTRASEÑAS no coinciden - por favor, revíselo";
        }
        if(empty($_POST['tipo_cuenta'])){
            $validaciones->val_tipo ="Debe elegir un TIPO DE CUENTA (admon / almacén)";
        }
        
        }
    }
    if(isset($_POST['volver_home'])){  exit(header('location:home_admon'));}
    global $twig;
    $template = $twig->load('alta_empleado.html');
    echo $template->render(array ('URI'=>$URI, 'usuario' =>$usuario, 'empleado'=>$empleado, 'validaciones'=>$validaciones, 'datos'=>$datos, 'logged'=>$logged, 'logged_legible'=>$logged_legible, 'mensaje'=>$mensaje));	
}


function controlador_registro_correcto(){

    global $twig;
    $template = $twig->load('registro_correcto.html');
    echo $template->render(array ());               //obligatorio incluso aunque esté vacío.
    
}

function controlador_alta_correcta(){
    $URI = get_URI();
    $usuario = checkSession();
    $empleado = datos_empleado($usuario);
    if ($empleado->tipo_cuenta != 'admon'){exit(header('location:iniciar_sesion'));}
    $mensaje="";
    global $twig;
    $template = $twig->load('alta_correcta.html');
    echo $template->render(array ('mensaje'=>$mensaje, 'usuario' =>$usuario, 'empleado'=>$empleado));               //obligatorio incluso aunque esté vacío.
    
}
function controlador_contacto()
{
    // Carga la plantilla que se mostrará al usuario con los datos recuperados del modelo
	global $twig;
    // Carga la plantilla que se mostrará al usuario con los datos recuperados 
    // del modelo

    $template = $twig->load('contacto.html');
	echo $template->render(array ());
}


function controlador_busqueda()
{
    // Petición al modelo para que retorne la lista de productos de la BD
    $resultado = buscar_producto();
    // Carga la plantilla que se mostrará al usuario con los datos recuperados del modelo
	global $twig;
    // Carga la plantilla que se mostrará al usuario con los datos recuperados 
    // del modelo

    $template = $twig->load('busqueda.html');
	echo $template->render(array ( 'resultado' => $resultado));
}

?>

        
    
 
    
