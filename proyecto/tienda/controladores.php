<?php
// controladores.php
require_once '../vendor/autoload.php';



$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

require_once 'funciones_sesion.php';
require_once "modelo.php";
require_once 'validadores.php';

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

function controlador_index()
{ $URI = get_URI();
    session_start();
    //estas variables son necesarias para variar el texto de los links, dependiendo de si está el usuario logeado o no
    $logged = isset($_SESSION['usuario']) ? "cerrar_sesion" : "iniciar_sesion";
    $logged_legible = isset($_SESSION['usuario']) ? "Cerrar Sesión" : "Iniciar Sesión";
   /* $crear_cuenta = isset($_SESSION['usuario']) ? "" : "crear_cuenta";
    $crear_cuenta_legible = isset($_SESSION['usuario']) ? "" : "Crear Cuenta";*/


    

    // Petición al modelo para que retorne la lista de productos de la BD
    $productos = lista_productos();
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
    // Petición al modelo para que retorne la lista de productos de la BD
    //$productos = lista_productos();
    $_SESSION['cesta'] = checkCesta();
    $logged = isset($_SESSION['usuario']) ? "cerrar_sesion" : "iniciar_sesion";
    $logged_legible = isset($_SESSION['usuario']) ? "Cerrar Sesión" : "Iniciar Sesión";
    $cesta = $_SESSION['cesta'];
    $total_prods = (isset($_SESSION['usuario']) && isset($_SESSION['cesta'])) ? count($_SESSION['cesta']) : 0;
   
    $producto = get_object_vars(detalle_producto($id));  //transformo el objeto que devuelve el modelo en array asociativo
    $mensaje = "";

    if (isset($_POST["anadir_producto"])) {
       // session_start();    PARA PERMITIR AÑADIR PRODUCTOS SIN HABER INICIADO SESION (CESTA SOLO ACCESIBLE TRAS LOGIN)
        $usuario = checkSession();
        $_SESSION['cesta'] = checkCesta();
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
                header("Refresh:1");
            }
        
        }else{
            // Si la cesta esta vacia se introduce directamente el producto, dandole como indice la ID del mismo para mas facil identificacion.
            $_SESSION['cesta'][$prod_add['id_prod']] = $prod_add;
            header("Refresh:1");
        }

        }
    
    global $twig;
    $template = $twig->load('detalle_producto.html');  
	echo $template->render(array ('URI'=>$URI, 'producto' => $producto, 'total_prods'=>$total_prods, 'mensaje'=> $mensaje, 'logged'=>$logged, 'logged_legible'=>$logged_legible));
    
}

function controlador_detalle_mercancia($id)
{   $URI = get_URI();    
    $usuario = checkSession();
    $empleado = datos_empleado($usuario);

    // Petición al modelo para que retorne la lista de productos de la BD
   
    $producto = get_object_vars(detalle_producto($id));  //transformo el objeto que devuelve el modelo en array asociativo
    $mensaje = "";

    
    global $twig;
    $template = $twig->load('detalle_mercancia.html');  
	echo $template->render(array ('URI'=>$URI, 'empleado'=>$empleado, 'producto' => $producto, 'mensaje'=> $mensaje));
    
}

function controlador_detalle_cliente($email)
{   $URI = get_URI();
    $usuario = checkSession();
    $empleado = datos_empleado($usuario);
    $cliente= datos_cliente($email); 
    $mensaje = "";

    
    global $twig;
    $template = $twig->load('detalle_cliente.html');  
	echo $template->render(array ('URI'=>$URI, 'empleado'=>$empleado, 'cliente'=>$cliente, 'mensaje'=> $mensaje));
    
}


function controlador_mis_datos()   /// PENDIENTE REASIGNAR A OTRA VISTA, OBSOLETO (SUSTITUIDO POR "CUENTA")
{   $URI = get_URI();
    $usuario = checkSession();
    $cliente = datos_cliente($usuario);
    $logged = isset($_SESSION['usuario']) ? "cerrar_sesion" : "iniciar_sesion";
    $logged_legible = isset($_SESSION['usuario']) ? "Cerrar Sesión" : "Iniciar Sesión";
    $total_prods = isset($_SESSION['cesta']) ? count($_SESSION['cesta']) : 0;

    if (isset($_POST["volver_cuenta"])) exit(header("location:mi_cuenta"));
    if (isset($_POST["mis_pedidos"])) exit(header("location:mis_pedidos"));

    if (isset($_POST["eliminar_cuenta"])) exit(header("location:eliminar_cuenta"));
   
    if (isset($_POST["cerrar_sesion"])){
        exit(header('location:cerrar_sesion'));
    }
    global $twig;
    $template = $twig->load('mis_datos.html');
	echo $template->render(array ('URI'=>$URI, 'productos' => $productos, 'usuario' =>$usuario, 'cliente'=> $cliente, 'logged'=>$logged, 'logged_legible'=>$logged_legible, 'total_prods'=>$total_prods));

}


function controlador_mi_cesta()
{   $URI = get_URI();
    $usuario = checkSession();
    $cliente = datos_cliente($usuario);
    $logged = isset($_SESSION['usuario']) ? "cerrar_sesion" : "iniciar_sesion";
    $logged_legible = isset($_SESSION['usuario']) ? "Cerrar Sesión" : "Iniciar Sesión";
    $_SESSION['cesta'] = checkCesta();
    $cesta = $_SESSION['cesta'];
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
        $delay=1;
        header("Refresh:$delay");
        $mensaje = "Producto eliminado con Éxito";
    }
}else $mensaje = "Por favor, seleccione al menos un producto para eliminar";
return $mensaje;
}
    
    
function vaciar_cesta(){
        unset($_SESSION['cesta']);
        $mensaje = "Cesta Vaciada con Éxito";
        $delay=1;
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
   
    if (isset($_POST["cerrar_sesion"])){
        exit(header('location:cerrar_sesion'));
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


    if (isset($_POST["cerrar_sesion"])){
        exit(header('location:cerrar_sesion'));
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
    $id_pedido=$_POST['id_pedido'];
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
// cuidado con '' o "" - para SQL conviene usar ''
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

    if (isset($_POST["cerrar_sesion"])){
        exit(header('location:cerrar_sesion'));
    }

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
    // unset($_SESSION['cesta']);     // DESCOMENTAR CUANDO YA NO NECESITE HACER PRUEBAS  //eliminamos cesta pues ya se ha transformado en pedido
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
    $logged = isset($_SESSION['usuario']) ? "cerrar_sesion" : "iniciar_sesion";
    $logged_legible = isset($_SESSION['usuario']) ? "Cerrar Sesión" : "Iniciar Sesión";
    // Petición al modelo para que retorne la lista de productos de la BD
    $_SESSION['cesta'] = checkCesta();
    
    $total_prods = (isset($_SESSION['usuario']) && isset($_SESSION['cesta'])) ? count($_SESSION['cesta']) : 0;
   
    $pedido = detalle_pedido($id_pedido);  //transformo el objeto que devuelve el modelo en array asociativo
    
    $mensaje = "";

    global $twig;
    $template = $twig->load('detalle_pedido.html');  
	echo $template->render(array ('URI'=>$URI, 'pedido' => $pedido, 'total_prods'=>$total_prods, 'mensaje'=> $mensaje, 'logged'=>$logged, 'logged_legible'=>$logged_legible));
    return $pedido;


}

function controlador_mis_pedidos()   /// PENDIENTE REASIGNAR A OTRA VISTA, OBSOLETO (SUSTITUIDO POR "CUENTA")
{   $URI = get_URI();
    $usuario = checkSession();
    $cliente = datos_cliente($usuario);
    $logged = isset($_SESSION['usuario']) ? "cerrar_sesion" : "iniciar_sesion";
    $logged_legible = isset($_SESSION['usuario']) ? "Cerrar Sesión" : "Iniciar Sesión";
    $nif = $cliente->nif;
    $total_prods = isset($_SESSION['cesta']) ? count($_SESSION['cesta']) : 0;
 
    $pedidosArray = pedidos_usuario($nif);
  
    // Petición al modelo para que retorne la lista de productos de la BD
    $productos = lista_productos();
    // Carga la plantilla que se mostrará al usuario con los datos recuperados del modelo
	global $twig;
    // Carga la plantilla que se mostrará al usuario con los datos recuperados 
    // del modelo

    if (isset($_POST["volver_cuenta"])) exit(header("location:mi_cuenta"));
   
    if (isset($_POST["cerrar_sesion"])){
        exit(header('location:cerrar_sesion'));
    }
    $template = $twig->load('mis_pedidos.html');
	echo $template->render(array ('URI'=>$URI, 'productos' => $productos, 'usuario' =>$usuario, 'cliente'=> $cliente, 'logged'=>$logged, 'total_prods'=>$total_prods, 'logged_legible'=>$logged_legible, 'pedidosArray' => $pedidosArray));

}

function controlador_pedidos()   /// PENDIENTE REASIGNAR A OTRA VISTA, OBSOLETO (SUSTITUIDO POR "CUENTA")
{   $URI = get_URI();
    $usuario = checkSession();
    $lista_pedidos = lista_pedidos();
    $cliente= isset($_POST['nif']) ? datos_cliente($cliente->email) : array();
    $pedidosArray = isset($_POST['nif']) ? pedidos_usuario($nif) : array();
    $empleado = datos_empleado($usuario);

 


    if (isset($_POST["volver_home"])) exit(header("location:home_".$empleado->tipo_cuenta));
   
    if (isset($_POST["cerrar_sesion"])){
        exit(header('location:cerrar_sesion'));
    }
    global $twig;
    $template = $twig->load('pedidos.html');
	echo $template->render(array ('URI'=>$URI, 'usuario' =>$usuario, 'empleado'=>$empleado, 'cliente'=> $cliente, 'lista_pedidos'=>$lista_pedidos, 'pedidosArray' => $pedidosArray));

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

                if(in_array($usuario, $lista_users)){
                    if ($dominio === 'frutasdelvalle.com') {$user = datos_empleado($usuario);}
                    else {$user = datos_cliente($usuario);}  
                       // echo 'USUARIO EN LISTADO'; 
                        $pass = $user->contrasena;
                        $estado = $user->estado_cuenta;
                        $tipo_cuenta = $user->tipo_cuenta;        
                                
                            if (($estado === 'activo')  && (password_verify($contrasena, $pass))){
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
                            }
                            elseif (!password_verify($contrasena, $pass)) {
                                $mensaje = "Usuario y/o contraseña incorrectos";
                            } elseif ($estado != 'activo') {
                                $mensaje = "Por favor, confirme su cuenta de usuario a través del email que recibió";
                
                            }               
                        }elseif (!in_array($usuario, $lista_users)) {
                           //$mensaje = "Usuario no está en BD";
                        }  $mensaje = "Usuario y/o contraseña incorrectos";
                    
                    } 
                               
        else $mensaje = "Los campos usuario y contraseña son obligatorios";

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





 

    if (isset($_POST["cerrar_sesion"])){
        exit(header('location:cerrar_sesion'));
    }



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
    $logged = isset($_SESSION['usuario']) ? "cerrar_sesion" : "iniciar_sesion";
    $logged_legible = isset($_SESSION['usuario']) ? "Cerrar Sesión" : "Iniciar Sesión";
    if ($empleado->tipo_cuenta != 'admon'){exit(header('location:iniciar_sesion'));}
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

        if (!valid_email($datos->email)){
            $validaciones->val_email="ERROR en EMAIL - Debe incluir una dirección de email válida";
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



        
    
 
    
