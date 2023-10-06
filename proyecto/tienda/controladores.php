<?php
// controladores.php
require_once '../vendor/autoload.php';

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

function controlador_detalle_cliente($nif)
{   $URI = get_URI();
    $usuario = checkSession();
    //Con las siguientes 2 lineas restrinjo el acceso a partes solo destinadas a empleados
    $base = checkDomain($usuario);  
    if ($base !== 'base_empl') exit(header('location:iniciar_sesion'));

    $empleado = datos_empleado($usuario); 
   // if ($empleado->tipo_cuenta != 'admon'){exit(header('location:pedidos'));}  // sólo el administrativo puede ver los datos del cliente
    $cliente = datos_cliente_nif($nif);
    $email = $cliente->email;    
    $total_pedidos = count(pedidos_usuario($nif)); //EL NÚM DE ELEMENTOS DEL ARRAY DE LOS PEDIDOS DEL USUARIO
    $total_gasto = array_sum(array_column(pedidos_usuario($nif), 'total_precio')); // SUMO LOS ELEMENTOS DEL ARRAY QUE CONTIENE EL TOTAL DE PRECIO DE TODOS LOS PEDIDOS DEL USUARIO
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

    if (isset($_POST["volver_cuenta"])) exit(header("location:mi_cuenta"));
    if (isset($_POST["mis_pedidos"])) exit(header("location:mis_pedidos"));

    if (isset($_POST["eliminar_cuenta"])) {        
        $contador = eliminar_cuenta($usuario);
        $mensaje = $contador. " cuenta(s) eliminada(s)";
        $delay=2;
        header("Refresh:$delay");}
   
    global $twig;
    $template = $twig->load('mis_datos.html');
	echo $template->render(array ('URI'=>$URI, 'usuario' =>$usuario, 'cliente'=> $cliente, 'logged'=>$logged, 'logged_legible'=>$logged_legible));

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


function total_precio(){
    $total= 0;
    $total_precio = 0;
    $cesta = checkCesta();
    foreach((($cesta)) as $i){
        $total = (($i['precio']) * ($i['cantidad']));
            $total_precio += $total;
            
        }$_SESSION['total_precio'] = $total_precio;

return $total_precio;
}
$total_precio = total_precio();

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
    return (int)$total_kg;
    }
$total_kg = total_kg();

function borrar_producto(){
    $mensaje="";
    if(isset($_POST['productos_borrar'])) {
    $checked = $_POST['productos_borrar'];
    if(intval($checked) == 1){
        foreach($_POST['productos_borrar'] as $val){
            unset($_SESSION['cesta'][intval($val)]);
        }
        $delay=2;
        header("Refresh:$delay");
        $mensaje = "Producto eliminado con Éxito";
    }
}else $mensaje = "Por favor, seleccione al menos un producto para eliminar";
return $mensaje;
}
    
    
function vaciar_cesta(){
        unset($_SESSION['cesta']);
        $mensaje = "Cesta Vaciada con Éxito";
        $delay=2;
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
	echo $template->render(array ('URI'=>$URI, 'usuario' =>$usuario, 'cliente'=> $cliente, 'cesta' => $cesta, 'mensaje' =>$mensaje, 'logged'=>$logged, 'logged_legible'=>$logged_legible, 'total_kg'=> $total_kg, 'total_prods'=> $total_prods, 'total_precio' => $total_precio));


}

function controlador_datos_envio(){
    $URI = get_URI();
    $usuario = checkSession();
    $cliente = datos_cliente($usuario);
    $logged = isset($_SESSION['usuario']) ? "cerrar_sesion" : "iniciar_sesion";
    $logged_legible = isset($_SESSION['usuario']) ? "Cerrar Sesión" : "Iniciar Sesión";
    $mensaje="";
    $total_precio = $_SESSION['total_precio'];
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
	echo $template->render(array ('URI'=>$URI, 'usuario' =>$usuario, 'cliente'=>$cliente, 'mensaje' => $mensaje, 'logged'=>$logged, 'logged_legible'=>$logged_legible, 'total_prods'=>$total_prods, 'total_kg'=> $total_kg, 'total_precio'=>$total_precio));

}

function controlador_confirmar_pedido(){ 
    $URI = get_URI();
    $usuario = checkSession();
    $cliente = datos_cliente($usuario);
    $logged = isset($_SESSION['usuario']) ? "cerrar_sesion" : "iniciar_sesion";
    $logged_legible = isset($_SESSION['usuario']) ? "Cerrar Sesión" : "Iniciar Sesión";
    $_SESSION['cesta'] = checkCesta();
    $cesta = $_SESSION['cesta'];
    $total_precio = $_SESSION['total_precio'];
    $total_kg = $_SESSION['total_kg'];
    $total_prods = $_SESSION['total_prods'];
    $mensaje="";
    $id_pedido= isset($_POST['id_pedido'])? $_POST['id_pedido']: '';
    $forma_pago='';
    
    if(isset($_POST["volver_cesta"])){
        exit(header("location:mi_cesta"));
    }

    if(isset($_POST["volver_datos"])){
        exit(header("location:datos_envio"));
    }

    if(isset($_POST['forma_pago'])){
        switch(($_POST['forma_pago'])){
            case "bizum":
                $forma_pago = 'bizum';
// cuidado con '' y "" - para SQL conviene usar ''
            case "transferencia_bancaria":
                $forma_pago = 'transferencia bancaria';                 
        }
    }
        if(isset($_POST["confirmar_pedido"]) && ($forma_pago !='')){

            $creado_fecha = date('Y-m-d H:i:s'); //hora y fecha actuales
            $fecha = date('dm_his');
            //El ID_PEDIDO contiene información relevante: el COD_POSTAL para facilitar agrupación de pedidos en almacén y transporte eficiente.
                                                        // el NIF para agrupar pedidos por empresa o titular 
                                                        // la fecha y hora en que se hizo para obtener siempre un número único (necesario para que sea PRIMARY KEY).

            $id_pedido = $cliente->cod_postal."-".$cliente->nif."-".$fecha; 
            $notas = $_POST['notas'];
            //si ambas operaciones insert retornan TRUE
            if(insert_pedido($id_pedido, $cliente->nif, $total_precio, $total_kg, $forma_pago, $creado_fecha, $notas) && (insert_productos_pedido($id_pedido, $cesta))){

            exit(header("location:pedido_realizado?id_pedido=$id_pedido"));
          } else $mensaje= "Error al grabar el pedido - por favor, repita el proceso de nuevo";
              

        } else $mensaje = "Por favor, seleccione una forma de pago";

    global $twig;
    $template = $twig->load('confirmar_pedido.html');
    echo $template->render(array ('URI'=>$URI, 'usuario' =>$usuario, 'cliente'=>$cliente, 'cesta' => $cesta, 'logged'=>$logged, 'logged_legible'=>$logged_legible, 'mensaje' => $mensaje, 'id_pedido'=>$id_pedido, 'total_prods'=>$total_prods, 'total_kg'=> $total_kg, 'total_precio'=>$total_precio));
}


function controlador_pedido_realizado($id_pedido){
    $URI = get_URI();
    $usuario = checkSession();
    $cesta = checkCesta();
    $cliente = datos_cliente($usuario);
    $id_pedido= $_GET['id_pedido'];
    $nif = $cliente->nif;
    $mensaje="";
    $total_precio = $_SESSION['total_precio'];
    $total_kg = $_SESSION['total_kg'];
    $total_prods = $_SESSION['total_prods'];
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
	echo $template->render(array ('URI'=>$URI, 'usuario' =>$usuario, 'nif'=>$nif,'cliente'=>$cliente, 'cesta' => $cesta, 'id_pedido'=> $id_pedido, 'mensaje' => $mensaje, 'total_prods'=>$total_prods, 'total_precio'=>$total_precio, 'total_kg'=>$total_kg));

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
    $pedido = detalle_pedido($id_pedido);  //transformo el objeto que devuelve el modelo en array de objetos
    $facturas = facturacion_pedido($id_pedido);
    $mensaje = "";
    global $twig;
    $template = $twig->load('detalle_pedido.html');  
	echo $template->render(array ('URI'=>$URI, 'base'=>$base, 'empleado'=>$empleado,'pedido' => $pedido, 'facturas'=>$facturas, 'total_prods'=>$total_prods, 'mensaje'=> $mensaje, 'logged'=>$logged, 'logged_legible'=>$logged_legible));
    //return $pedido;


}

function controlador_mis_pedidos()   
{   $URI = get_URI();
    $usuario = checkSession();
    $cliente = datos_cliente($usuario);
    $logged = isset($_SESSION['usuario']) ? "cerrar_sesion" : "iniciar_sesion";
    $logged_legible = isset($_SESSION['usuario']) ? "Cerrar Sesión" : "Iniciar Sesión";
    $checked = isset($_POST['pedido_select']) ? $_POST['pedido_select'] : [];
    $creado_fecha = isset($_POST['creado_fecha']) ? $_POST['creado_fecha'] : '';
    $nif = $cliente->nif;  //en este caso se define el nif como el nif del cliente, con lo que al hacer el JOIN en la consulta a BD, sólo sus pedidos aparecen, acotando la busqueda.
    //$orden = isset($_POST['orden']) ? htmlentities($_POST['orden'], ENT_QUOTES,'utf-8') : 'sin criterio';
    $total_prods = isset($_SESSION['cesta']) ? count($_SESSION['cesta']) : 0;
    $mensaje='';
    $pedidosArray = pedidos_usuario($nif);

	global $twig;

    if (isset($_POST["buscar"])){ 
 
       if($_POST['creado_fecha']!='') {
         $pedidosArray = pedidos_usuario_fecha($nif, $_POST['creado_fecha']); 
         $mensaje = "Encontrado(s) ".count($pedidosArray). " pedido(s) en la fecha ".$creado_fecha;}  
        }
        
    
    if (isset($_POST["marcar_recibido"])) {
        $contador = 0;
        if(intval($checked) == 1){
            $recibido_fecha = date('Y-m-d H:i:s');
            foreach($checked as $val){
                $cuenta = pedido_entregado($val, $recibido_fecha);
                $contador +=$cuenta;
            }
            $delay=2;
            header("Refresh:$delay");
            $mensaje = $contador. " pedido(s) marcado(s) como 'Recibido'";
        }
    else $mensaje = "Por favor, seleccione al menos un pedido para modificar";
    }

    if (isset($_POST["cancelar_pedido"])) {
        $activo = '';
        $contador = 0;
        $contador2 = 0;
        if(intval($checked) == 1){
            foreach($checked as $id_pedido){
                $cancelado_fecha = date('Y-m-d H:i:s');
                $cuenta = pedido_cancelado($id_pedido, $cancelado_fecha);
                $contador +=$cuenta;
                $pedido = detalle_pedido($id_pedido);
                foreach($pedido as $prod){
                $cuenta2 = actualizar_stock($prod->id_prod, $prod->cantidad, 'sumar');
                $contador2 +=$cuenta2;
                }
            }
            $delay=2;
            header("Refresh:$delay");
            $mensaje = $contador. " pedido(s) marcado(s) como Pagado(s) - ".$contador2. " ACTUALIZACIONES DE STOCK";
        } else $mensaje = "Por favor, seleccione al menos un pedido para cancelar";
    }

    
    if (isset($_POST["borrar_cancelados"])) {
        $contador = borrar_cancelados_cliente($nif);
        $delay=2;
        header("Refresh:$delay");
        $mensaje = $contador. " pedido(s) cancelado(s) borrado(s)";
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
    $lista_pedidos = lista_pedidos();
    $checked = isset($_POST['pedido_select']) ? $_POST['pedido_select'] : [];
    $id_pedido = isset($_POST['id_pedido']) ? $_POST['id_pedido'] : '';
    $nif = isset($_POST['nif']) ? $_POST['nif'] : '';  //en este caso el DNI es otro campo de búsqueda porque lo utiliza el administrador
    $total_precio = isset($_POST['total_precio']) ? $_POST['total_precio'] : '';
    $total_kg = isset($_POST['total_kg']) ? $_POST['total_kg'] : '';
    $forma_pago = isset($_POST['forma_pago']) ? $_POST['forma_pago'] : '';
    $estado_pago = isset($_POST['estado_pago']) ? $_POST['estado_pago'] : '';
    $estado_pedido = isset($_POST['estado_pedido']) ? $_POST['estado_pedido'] :'';
    $creado_fecha = isset($_POST['creado_fecha']) ? $_POST['creado_fecha'] : '';
    $pagado_fecha = isset($_POST['pagado_fecha']) ? $_POST['pagado_fecha'] : '';
    $enviado_fecha = isset($_POST['enviado_fecha']) ? $_POST['enviado_fecha'] : '';
    $entregado_fecha = isset($_POST['entregado_fecha']) ? $_POST['entregado_fecha'] : '';
    $cancelado_fecha = isset($_POST['cancelado_fecha']) ? $_POST['cancelado_fecha'] : '';
    $notas = isset($_POST['notas']) ? $_POST['notas'] : '';
    //$orden = isset($_POST['orden']) ? $_POST['orden'] : '';
   // $orden = isset($_POST['orden']) ? htmlentities($_POST['orden'],  ENT_QUOTES, "UTF-8") : '';
    $mensaje='';
    //$where será un string modificable al que iré anexando cada campo en caso de que no esté vacío. Es decir, tendrá una longitud variable.
    $where = "WHERE id_pedido LIKE '%$id_pedido%' ";
    $selected_est_pago = '';
    $factura = '';
    $albaran = '';
    $nif_cliente = '';



    if (isset($_POST["buscar"])){ 
        //$where = "WHERE id_pedido LIKE '%$id_pedido%' ";
        if($nif != '') $where .= "AND nif_cliente LIKE '%$nif%'";
        if($total_precio != '')$where .= "AND total_precio LIKE '%$total_precio%'";
        if($total_kg != '')$where .= "AND total_kg LIKE '%$total_kg%'";
        if($forma_pago != ''){$where .= "AND forma_pago LIKE '%$forma_pago%'"; $selected_est_pago = 'selected';}
        if($estado_pago != ''){$where .= "AND estado_pago LIKE '%$estado_pago%'"; $selected_est_pago = 'selected';}
        if($estado_pedido != ''){$where .= "AND estado_pedido LIKE '%$estado_pedido%'";$selected_est_pago = 'selected';}
        if($creado_fecha != '')$where .= "AND creado_fecha LIKE '%$creado_fecha%'";
        if($pagado_fecha != '')$where .= "AND pagado_fecha LIKE '%$pagado_fecha%'";
        if($enviado_fecha != '')$where .= "AND enviado_fecha LIKE '%$enviado_fecha%'";
        if($entregado_fecha != '')$where .= "AND entregado_fecha LIKE '%$entregado_fecha%'";
        if($cancelado_fecha != '')$where .= "AND cancelado_fecha LIKE '%$cancelado_fecha%'";
        if($notas != '')$where .= "AND notas LIKE '%$notas%'";

        $lista_pedidos = pedidos_busqueda($where, $id_pedido, $nif, $total_precio, $total_kg, $forma_pago, $estado_pago, $estado_pedido, 
                         $creado_fecha, $pagado_fecha, $enviado_fecha, $entregado_fecha, $cancelado_fecha, $notas); } 
                         //Genero lista de pedidos con el where según criterios de búsqueda combinados
        $mensaje = "Encontrado(s) ".count($lista_pedidos). " pedido(s)";

    if (isset($_POST["marcar_pagado"])) {
            $contador = 0;
            $contador1 = 0;
            $contador2 = 0;
            //If intval($checked == 1 significa que el array "checked" tiene al menos un valor dentro, con lo que hay algún pedido seleccionado)
            if(intval($checked) == 1){
                foreach($checked as $id_pedido){
                    $pagado_fecha = date('Y-m-d H:i:s');
                    $nif_cliente = $_POST ['nif_cliente']; //NECESITO ESTE DATO PARA INCORPORARLO A LAS FACTURAS al marcar como pagado
                    $factura = 'FAC_'.$pagado_fecha.'/'.$contador; //Así aseguro que, aunque marque como pagados 2 pedidos a la vez, el ID factura y albaran sean unicos.
                    $albaran = 'ALB_'.$pagado_fecha.'/'.$contador; 
                    $cuenta = pedido_pagado($id_pedido, $pagado_fecha);
                    $contador +=$cuenta; 
                    $cuenta1 = factura_creada($factura, $albaran, $id_pedido, $nif_cliente);
                    $contador1 +=$cuenta1;    
                    //Obtengo detalles de cada pedido para ahondar en los productos que lo componen, y modificar su stock
                    $pedido = detalle_pedido($id_pedido);               
                    foreach($pedido as $prod){
                        $cuenta2 = actualizar_stock($prod->id_prod, $prod->cantidad, 'restar');
                        $contador2 +=$cuenta2;
                        }
                }
                $delay=2;
                header("Refresh:$delay");
                $mensaje = $contador. " pedido(s) marcado(s) como Pagado(s) - ".$contador2. " Stock actualizado(s) - ".PHP_EOL. $contador1." Factura(s) Generada(s)";
            }
    else $mensaje = "Por favor, seleccione al menos un producto para modificar";

        }        

    if (isset($_POST["marcar_enviado"])) {
            $contador = 0;
            //If intval($checked == 1 significa que el array "checked" tiene al menos un valor dentro, con lo que hay algún pedido seleccionado)
            if(intval($checked) == 1){                
                foreach($checked as $id_pedido){
                    $enviado_fecha = date('Y-m-d H:i:s');
                    $cuenta = pedido_enviado($id_pedido, $enviado_fecha);
                    $contador +=$cuenta;
                }
                $delay=2;
                header("Refresh:$delay");
                $mensaje = $contador. " pedido(s) marcado(s) como 'Enviado'";
            }
    else $mensaje = "Por favor, seleccione al menos un producto para modificar";

        }

    if (isset($_POST["marcar_entregado"])) {
        $contador = 0;
        //If intval($checked == 1 significa que el array "checked" tiene al menos un valor dentro, con lo que hay algún pedido seleccionado)
        if(intval($checked) == 1){            
            foreach($checked as $ide_){
                $entregado_fecha = date('Y-m-d H:i:s');
                $cuenta = pedido_entregado($ide_, $entregado_fecha);
                $contador +=$cuenta;
            }
            $delay=2;
            header("Refresh:$delay");
            $mensaje = $contador. " pedido(s) marcado(s) como 'Entregado'";
        }
    else $mensaje = "Por favor, seleccione al menos un producto para modificar";
    }
    //Para uso en caso de que se haya pagado pero aun no enviado. El stock descontado se vuelve a añadir.
    if (isset($_POST["cancelar_pedido"])) {
        $contador = 0;
        $contador1 = 0;
        $contador2 = 0;
        //If intval($checked == 1 significa que el array "checked" tiene al menos un valor dentro, con lo que hay algún pedido seleccionado)
        if(intval($checked) == 1){            
            foreach($checked as $val){
                $cancelado_fecha = date('Y-m-d H:i:s');
                $cuenta = pedido_cancelado($val, $cancelado_fecha);
                $contador +=$cuenta;
                $cuenta1 = factura_cancelada($val, $cancelado_fecha);
                $contador1 += $cuenta1;
                $pedido = detalle_pedido($val);
                foreach($pedido as $prod){
                $cuenta2 = actualizar_stock($prod->id_prod, $prod->cantidad, 'sumar');
                $contador2 +=$cuenta2;
                }
            }
            $delay=2;
            header("Refresh:$delay");
            $mensaje = $contador. " pedido(s) Cancelado(s) - ".$contador2. " actualizaciones de stock- ".$contador1. " Facturas Cancelada(s)";
        }
    else $mensaje = "Por favor, seleccione al menos un producto para modificar";

    }

    if (isset($_POST["borrar_cancelados"])) {
        $contador = borrar_cancelados();
        $delay=2;
        header("Refresh:$delay");
        $mensaje = $contador. " pedido(s) cancelado(s) borrado(s)";
    }
    
    global $twig;
    $template = $twig->load('control_pedidos.html');
	echo $template->render(array ('URI'=>$URI, 'usuario'=>$usuario, 'empleado'=>$empleado, 'factura'=>$factura, 'albaran'=>$albaran, 'where'=>$where, 'id_pedido'=>$id_pedido, 'nif'=> $nif, 'total_precio'=>$total_precio, 'total_kg'=>$total_kg, 
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
            foreach($checked as $val){
                $cuenta = modificar_cuenta($val, $nuevo_estado);
                $contador +=$cuenta;
            }
            $delay=2;
            header("Refresh:$delay");
            $mensaje = $contador. " cuentas(s) ACTIVADAS";
        }
    else $mensaje = "Por favor, seleccione al menos una cuenta para activar";

        }        

    if (isset($_POST["desactivar_cuenta"])) {
            $contador = 0;
            $nuevo_estado = 'inactivo';
            if(intval($checked) == 1){
               foreach($checked as $val){
                    $cuenta = modificar_cuenta($val, $nuevo_estado);
                    $contador +=$cuenta;
               }
                $delay=2;
                header("Refresh:$delay");
                $mensaje = $contador. " cuentas(s) DESACTIVADAS";
            }
    else $mensaje = "Por favor, seleccione al menos una cuenta para desactivar";

        }        

    if (isset($_POST["bloquear_cuenta"])) {
            $contador = 0;
            $nuevo_estado = 'bloqueado';
            if(intval($checked) == 1){
                foreach($checked as $val){
                    $cuenta = modificar_cuenta($val, $nuevo_estado);
                    $contador +=$cuenta;
                }
                $delay=2;
                header("Refresh:$delay");
                $mensaje = $contador. " cuentas(s) BLOQUEADAS";
            }
            else $mensaje = "Por favor, seleccione al menos una cuenta para bloquear";

        }

    if (isset($_POST["eliminar_cuenta"])) {
        $contador = 0;
        if(intval($checked) == 1){
            foreach($checked as $val){
                $cuenta = eliminar_cuenta($val, $cancelado_fecha);
                $contador +=$cuenta;
            }
            $delay=2;
            header("Refresh:$delay");
            $mensaje = $contador. " cuenta(s) eliminada(s)";
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
    $lista_users = lista_users();
    $lista_users = array_column(lista_users(), 'email'); //extraigo solo el dato "email" de array multidimensional
    $estado = "";
    $pass="";
    $mensaje = "";
    $usuario = isset($_POST["usuario"]) ? strtolower(htmlspecialchars($_POST["usuario"])) : ""; //normalizo en minusculas el email
    $contrasena = isset($_POST["contrasena"]) ? $_POST["contrasena"] : "";

    if (isset($_POST["entrar"])) {
        //Procesar formulario
         //Si no están vacíos, se llama a Base de Datos para obtener contraseña, estado de la cuenta y tipo de cuenta
        if (($usuario != "") && ($contrasena != "")) {

            $dominio = ltrim(strstr($usuario, '@'), '@');    //con esto obtengo lo que va tras la @ para conocer si es un mail de empleado o no
                //Si el usuario está en la lista de usuarios (tanto cliente como empleado):
                if(in_array($usuario, $lista_users)){
                    if ($dominio === 'frutasdelvalle.com') {$user = datos_empleado($usuario);}
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

    global $twig;
    $template = $twig->load('home_admon.html');
	echo $template->render(array ('URI'=>$URI, 'usuario' =>$usuario, 'empleado'=>$empleado));

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

    $validaciones = array('val_nif'=> '', 'val_nom'=> '', 'val_ape'=>'', 'val_tel'=>'', 'val_email'=>'', 'val_dir'=>'',
                        'val_loc'=>'','val_postal'=>'', 'val_prov'=>'','val_contrasena'=>'');  
    $datos = [];
    
    foreach($_POST as $key => $value){
        if(!isset($datos[$key])){
            $datos[$key] = htmlspecialchars($value);
        }
    }

    $validaciones= (object) $validaciones; //CONVIERTO A OBJETOS PARA MAYOR FACILIDAD DE USO EN LA PLANTILLA TWIG
    $datos = (object)$datos;

   
    if(isset($_POST['crear_cuenta'])){   

        if(//valid_nif($_POST['nif']) && 
        (es_texto($_POST['nombre'])) && (es_texto($_POST['apellidos'])) && 
        (!empty($_POST['direccion'])) && (es_texto($_POST['localidad'])) &&(!empty($_POST['cod_postal'])) &&
        (es_texto($_POST['provincia'])) && (valid_tel($_POST['telefono'])) && (valid_email($_POST['email'])) && 
        (valid_contrasena($_POST['contrasena'])) && ($_POST['contrasena'] === $_POST['rep_contrasena'])) 
            {
            $creado_fecha = date('Y-m-d H:i:s');
            //añadir htmlentities delante de cada entrada
            $resultado = insert_cliente($_POST['nif'], $_POST['nombre'], $_POST['apellidos'], 
            $_POST['email'], $_POST['telefono'], $_POST['direccion'], $_POST['localidad'], $_POST['cod_postal'], $_POST['provincia'],
            $_POST['contrasena'], $creado_fecha); 
            if ($resultado != false) { $mensaje = 'Se ha creado ' . $resultado .' cuenta con éxito'; exit(header('location:registro_correcto')); }     
                
            else $mensaje= "Error al grabar el pedido - por favor, repita el proceso de nuevo";
          
            }
        else{  //reviso los posibles errores de 1 en 1, para poder modificar su validacion individualmente (ya que pueden darse varios fallos simultaneos)
            //PENDIENTE VALIDADOR DE NIF
      /*      if (!es_texto($datos->nif)){
                $validaciones->val_nif = "ERROR en NIF - Debe incluir 8 cifras + 1 letra, sin espacios";
            }*/

            if (!es_texto($datos->nombre)){
                $validaciones->val_nom = "ERROR en NOMBRE - Sólo puede incluir caracteres del alfabeto";
            }
            if (!es_texto($datos->apellidos)){
                $validaciones->val_ape="ERROR en APELLIDOS - Sólo puede incluir caracteres del alfabeto";
            }
            if (!valid_tel($datos->telefono)){ 
                $validaciones->val_tel ="ERROR en TELEFONO - Debe tener una longitud de 9 cifras, sin prefijo internacional ni separaciones";
            }
            //PENDIENTE VALIDADOR DE cod_postal
      /*      if (!valid_cod_postal($datos->cod_postal)){
                $validaciones->val_cod="ERROR en CÓDIGO POSTAL - Debe tener una longitud de 5 cifras";
            }*/

          /*if (!valid_prov($datos['provincia'])){
                $validaciones->$val_prov="ERROR en PROVINCIA - Debe seleccionar una del menú desplegable";
            }*/

            if (!valid_email($datos->email)){
                $validaciones->val_email="ERROR en EMAIL - Debe incluir una dirección de email válida";
            }

            if (!valid_contrasena($datos->contrasena)){
                $validaciones->val_contrasena ="ERROR en CONTRASEÑA- Debe tener al menos 8 caracteres, incluyendo mayúsculas, minúsculas, cifras y signos especiales.";
            }
            if($_POST['contrasena'] != $_POST['rep_contrasena']){
                $validaciones->val_contrasena ="Las contraseñas no coinciden - por favor, revíselo";
            }

        }
   
    }

    if(isset($_POST['volver_productos'])){  exit(header('location:index.php'));}

    global $twig;
    $template = $twig->load('crear_cuenta.html');
    echo $template->render(array ('URI'=>$URI, 'validaciones'=>$validaciones, 'datos'=>$datos, 'mensaje'=>$mensaje));	
}


function controlador_alta_empleado()
{   $URI = get_URI();
    $usuario = checkSession();
    $empleado = datos_empleado($usuario);
    if ($empleado->tipo_cuenta != 'admon'){exit(header('location:iniciar_sesion'));}
    $logged = isset($_SESSION['usuario']) ? "cerrar_sesion" : "iniciar_sesion";
    $logged_legible = isset($_SESSION['usuario']) ? "Cerrar Sesión" : "Iniciar Sesión";
    $mensaje = "";
    $resultado = "";


    $validaciones = array('val_nif'=> '', 'val_nom'=> '', 'val_ape'=>'', 'val_email'=>'', 'val_tel'=>'', 'val_dir'=>'',
                        'val_loc'=>'','val_postal'=>'', 'val_prov'=>'','val_contrasena'=>'', 'val_tipo'=>'');  
    $datos = [];    
    foreach($_POST as $key => $value){
        if(!isset($datos[$key])){
            $datos[$key] = htmlspecialchars($value);
        }
    }

    $validaciones= (object) $validaciones; //CONVIERTO A OBJETOS PARA MAYOR FACILIDAD DE USO EN LA PLANTILLA TWIG
    $datos = (object)$datos;
    
    if(isset($_POST['confirmar_alta'])){ 
            //valid_nif($_POST['nif']) && 
        if((es_texto($_POST['nombre'])) && (es_texto($_POST['apellidos'])) && 
        (!empty($_POST['direccion'])) && (es_texto($_POST['localidad'])) &&(!empty($_POST['cod_postal'])) &&
        (es_texto($_POST['provincia'])) && (valid_tel($_POST['telefono'])) && (valid_email($_POST['email'])) && 
        (valid_contrasena($_POST['contrasena'])) && ($_POST['contrasena'] === $_POST['rep_contrasena']) && (!empty($_POST['tipo_cuenta']))){
              
        $creado_fecha = date('Y-m-d H:i:s');

        $resultado = alta_empleado($_POST['nif'], $_POST['nombre'], $_POST['apellidos'], 
        $_POST['email'], $_POST['telefono'], $_POST['direccion'], $_POST['localidad'], $_POST['cod_postal'], $_POST['provincia'],
        $_POST['contrasena'], $creado_fecha, $_POST['tipo_cuenta']);

        if ($resultado != null) { $mensaje = 'Se ha registrado ' . $resultado .' empleado con éxito'; }//exit(header('location:alta_correcta')); }     
                
        else $mensaje= "Error al grabar el pedido - por favor, repita el proceso de nuevo";
      
        

        }
    else{  //reviso los posibles errores de 1 en 1, para poder modificar su validacion individualmente (ya que pueden darse varios fallos simultaneos)
        //PENDIENTE VALIDADOR DE NIF
    /*      if (!es_texto($datos->nif)){
            $validaciones->val_nif = "ERROR en NIF - Debe incluir 8 cifras + 1 letra, sin espacios";
        }*/

        if (!es_texto($datos->nombre)){
            $validaciones->val_nom = "ERROR en NOMBRE - Sólo puede incluir caracteres del alfabeto";
        }
        if (!es_texto($datos->apellidos)){
            $validaciones->val_ape="ERROR en APELLIDOS - Sólo puede incluir caracteres del alfabeto";
        }
        if (!valid_tel($datos->telefono)){ 
            $validaciones->val_tel ="ERROR en TELEFONO - Debe tener una longitud de 9 cifras, sin prefijo internacional ni separaciones";
        }
        //PENDIENTE VALIDADOR DE cod_postal
    /*      if (!valid_cod_postal($datos->cod_postal)){
            $validaciones->val_postal="ERROR en CÓDIGO POSTAL - Debe tener una longitud de 5 cifras";
        }*/

        /*if (!valid_prov($datos['provincia'])){
            $validaciones->$val_prov="ERROR en PROVINCIA - Debe seleccionar una del menú desplegable";
        }*/

        if (!valid_email_empleado($datos->email)){
            $validaciones->val_email="ERROR en EMAIL - Debe incluir una dirección de email válida con extension @frutasdelvalle.com";
        }

        if (!valid_contrasena($datos->contrasena)){
            $validaciones->val_contrasena ="ERROR en CONTRASEÑA- Debe tener al menos 8 caracteres, incluyendo mayúsculas, minúsculas, cifras y signos especiales.";
        }
        if($_POST['contrasena'] != $_POST['rep_contrasena']){
            $validaciones->val_contrasena ="Las contraseñas no coinciden - por favor, revíselo";
        }
        if(empty($_POST['tipo_cuenta'])){
            $validaciones->val_tipo ="Debe escoger un tipo de cuenta (admon / almacén)";
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
	echo $template->render(array ( 'productos' => $productos));
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

        
    
 
    
