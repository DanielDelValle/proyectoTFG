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



//include 'Cesta.php';
//include 'cartAction.php';

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
    $logged = isset($_SESSION['usuario']) ? "cerrar_sesion" : "iniciar_sesion";
    $logged_legible = isset($_SESSION['usuario']) ? "Cerrar Sesión" : "Iniciar Sesión";
    $crear_cuenta = isset($_SESSION['usuario']) ? "" : "crear_cuenta";
    $crear_cuenta_legible = isset($_SESSION['usuario']) ? "" : "Crear Cuenta";


    

    // Petición al modelo para que retorne la lista de productos de la BD
    $productos = lista_productos();
    $total_prods = (isset($_SESSION['usuario']) && isset($_SESSION['cesta'])) ? count($_SESSION['cesta']) : 0;
    
    // Carga la plantilla que se mostrará al usuario con los datos recuperados del modelo
	global $twig;
    // Carga la plantilla que se mostrará al usuario con los datos recuperados 
    // del modelo
    var_dump($_SESSION);
    $template = $twig->load('productos.html');
	echo $template->render(array ('URI'=>$URI, 'logged'=>$logged, 'logged_legible'=>$logged_legible, 'crear_cuenta' => $crear_cuenta, 'crear_cuenta_legible'=> $crear_cuenta_legible, 'productos' => $productos, 'total_prods'=>$total_prods));
}


function controlador_detalle($id)
{   
     $URI = get_URI();    
    // Petición al modelo para que retorne la lista de productos de la BD
    $productos = lista_productos();
    $_SESSION['cesta'] = checkCesta();
    $cesta = $_SESSION['cesta'];
    $total_prods = (isset($_SESSION['usuario']) && isset($_SESSION['cesta'])) ? count($_SESSION['cesta']) : 0;
   
    $producto = get_object_vars(detalle_producto($id));  //transformo el objeto que devuelve el modelo en array asociativo
    $mensaje = "";

    if (isset($_POST["anadir_producto"])) {
        $URI = get_URI();
        $usuario = checkSession();
        $_SESSION['cesta'] = checkCesta();
        $total_prods = (isset($_SESSION['usuario']) && isset($_SESSION['cesta'])) ? count($_SESSION['cesta']) : 0;

        $prod_add = array();  //$prod_add = new ArrayObject(); 
        $mensaje = 'Producto añadido a la cesta';
        //header("Refresh:1");
        
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
        echo session_status();
    global $twig;
    $template = $twig->load('detalle_producto.html');  
	echo $template->render(array ('URI'=>$URI, 'producto' => $producto, 'total_prods'=>$total_prods, 'mensaje'=> $mensaje));
    return $producto;
    
}

function controlador_mi_cesta()
{   $URI = get_URI();
    $usuario = checkSession();
    $cliente = datos_cliente($usuario);
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
	echo $template->render(array ('URI'=>$URI, 'usuario' =>$usuario, 'cliente'=> $cliente, 'cesta' => $cesta, 'mensaje' =>$mensaje, 'total_kg'=> $total_kg, 'total_prods'=> $total_prods, 'total_precio' => $total_precio));


}

function controlador_datos_envio(){
    $URI = get_URI();
    $usuario = checkSession();
    $cliente = datos_cliente($usuario);
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
	echo $template->render(array ('URI'=>$URI, 'usuario' =>$usuario, 'cliente'=>$cliente, 'mensaje' => $mensaje, 'total_prods'=>$total_prods, 'total_kg'=> $total_kg, 'total_precio'=>$total_precio));

}

function controlador_confirmar_pedido(){ 
    $URI = get_URI();
    $usuario = checkSession();
    $cliente = datos_cliente($usuario);
    $_SESSION['cesta'] = checkCesta();
    $cesta = $_SESSION['cesta'];
    $total_precio = $_SESSION['total_precio'];
    $total_kg = $_SESSION['total_kg'];
    $total_prods = $_SESSION['total_prods'];
    $mensaje="";
    $id_pedido="";
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


            $fecha = date('dm_his');
            //El ID_PEDIDO contiene información relevante: el COD_POSTAL para facilitar agrupación de pedidos en almacén y transporte eficiente.
                                                        // el NIF para agrupar pedidos por empresa o titular 
                                                        // la fecha y hora en que se hizo para obtener siempre un número único (necesario para que sea PRIMARY KEY).

            $id_pedido = $cliente->cod_postal."/".$cliente->nif."/".($fecha); 
            $notas = $_POST['notas'];

          if(insert_pedido($id_pedido, $cliente->nif, $total_precio, $total_kg, $forma_pago, $notas) && (insert_productos_pedido($id_pedido, $cesta))){

            exit(header("location:pedido_realizado"));
          } else $mensaje= "Error al grabar el pedido - por favor, repita el proceso de nuevo";
              

        } else $mensaje = "Por favor, seleccione una forma de pago";

    if (isset($_POST["cerrar_sesion"])){
        exit(header('location:cerrar_sesion'));
    }

    global $twig;
    $template = $twig->load('confirmar_pedido.html');
    echo $template->render(array ('URI'=>$URI, 'usuario' =>$usuario, 'cliente'=>$cliente, 'cesta' => $cesta,  'mensaje' => $mensaje, 'id_pedido'=>$id_pedido, 'total_prods'=>$total_prods, 'total_kg'=> $total_kg, 'total_precio'=>$total_precio));
}



function controlador_pedido_realizado(){
    $URI = get_URI();
    $usuario = checkSession();
    $cliente = datos_cliente($usuario);
    $cesta = checkCesta();
    $pedido = datos_pedido($usuario);
    $mensaje="";
    $total_precio = $_SESSION['total_precio'];
    $total_kg = $_SESSION['total_kg'];
    $total_prods = $_SESSION['total_prods'];
    // unset($_SESSION['cesta']);     // DESCOMENTAR CUANDO YA NO NECESITE HACER PRUEBAS  //eliminamos cesta pues ya se ha transformado en pedido
    if(isset($_POST["ver_productos"])){
        exit(header("location:index.php"));
    }

    if(isset($_POST["volver_home"])){
        exit(header("location:mi_cuenta"));
    }

    if (isset($_POST["cerrar_sesion"])){
        exit(header('location:cerrar_sesion'));
    }


    global $twig;
    $template = $twig->load('pedido_realizado.html');
	echo $template->render(array ('URI'=>$URI, 'usuario' =>$usuario, 'cliente'=>$cliente, 'cesta' => $cesta, 'pedido'=> $pedido, 'mensaje' => $mensaje, 'total_prods'=>$total_prods, 'total_kg'=> $total_kg, 'total_precio'=>$total_precio));

}


function controlador_iniciar_sesion(){
    session_start();
    $URI = get_URI();
    if (isset($_POST["entrar"])) {
        //Procesar formulario
        //Obtener contraseña codificada de bd, por ejemplo:
        $user = "danimolar@hotmail.com";
        $pass = crypt('daniel', '$1$somethin$');
        //&pass = md5('daniel')
        $mensaje = "";
        //Comprobar que el nombre y contrasena no están vacíos
        if (isset($_POST["usuario"])) $usuario = $_POST["usuario"];
        else $usuario = "";
    
        if (isset($_POST["contrasena"])) $contrasena = $_POST["contrasena"];
        else $contrasena = "";
    
        if ($usuario != "" && $contrasena != "") {
            //Comprobar credenciales
            //if ($usuario == "admin" && md5($contrasena) == $pass)
            if (($usuario == $user) && (crypt($contrasena, '$1$somethin$')) == $pass ) {  //&& $estado== 'activo'
                //Login correcto
                //Establecer sesion autentificada
                $_SESSION["usuario"] = $usuario;    
                //$_SESSION["contrasena"] = $contrasena;  
                session_regenerate_id(); //para evitar ataque de fijacion de sesion (en redes compartidas)
                $previa = $_SERVER['HTTP_REFERER'];
                exit(header("location:mi_cuenta"));
            } else {
                $mensaje = "Usuario y/o contraseña incorrectos";                
        //      $delay=2;
        //      header("Refresh:$delay");
            } 
        } else $mensaje = "Los campos usuario y contraseña son obligatorios";
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
    }    

	global $twig;
    $template = $twig->load('iniciar_sesion.html');
    echo $template->render(array ('URI'=>$URI, 'usuario' => $usuario, 'mensaje' =>$mensaje));
}



function controlador_mis_datos()   /// PENDIENTE REASIGNAR A OTRA VISTA, OBSOLETO (SUSTITUIDO POR "CUENTA")
{   $URI = get_URI();
    $usuario = checkSession();
    $cliente = datos_cliente($usuario);
    $total_prods = isset($_SESSION['cesta']) ? count($_SESSION['cesta']) : 0;
    // Petición al modelo para que retorne la lista de productos de la BD
    $productos = lista_productos();
    // Carga la plantilla que se mostrará al usuario con los datos recuperados del modelo
	global $twig;
    // Carga la plantilla que se mostrará al usuario con los datos recuperados 
    // del modelo

    if (isset($_POST["volver_cuenta"])) exit(header("location:mi_cuenta"));
    if (isset($_POST["ver_pedidos"])) exit(header("location:mis_pedidos"));

    if (isset($_POST["eliminar_cuenta"])) exit(header("location:eliminar_cuenta"));
   
    if (isset($_POST["cerrar_sesion"])){
        exit(header('location:cerrar_sesion'));
    }
    
    $template = $twig->load('mis_datos.html');
	echo $template->render(array ('URI'=>$URI, 'productos' => $productos, 'usuario' =>$usuario, 'cliente'=> $cliente, 'total_prods'=>$total_prods));



}



function controlador_mi_cuenta()
{   $URI = get_URI();
    $usuario = checkSession();
    $cliente = datos_cliente($usuario);
    $total_prods = isset($_SESSION['cesta']) ? count($_SESSION['cesta']) : 0;
    // Petición al modelo para que retorne la lista de productos de la BD
    // Carga la plantilla que se mostrará al usuario con los datos recuperados del modelo
	global $twig;
    // Carga la plantilla que se mostrará al usuario con los datos recuperados 
    // del modelo



    if (isset($_POST["cerrar_sesion"])){
        exit(header('location:cerrar_sesion'));
    }
    
    $template = $twig->load('mi_cuenta.html');
	echo $template->render(array ('URI'=>$URI, 'usuario' =>$usuario, 'cliente'=>$cliente, 'total_prods'=>$total_prods));



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

function controlador_registro()
{
	require 'validadores.php';
	
    $formulario = array(
        array('Nombre: ', 'text', 'nombre', ''),
        array('Apellidos: ', 'text', 'apellidos', ''),
        array('Direccion: ', 'text', 'direccion', ''),
        array('Email: ', 'email', 'email', ''),
        array('Contraseña: ', 'password', 'contrasena', ''),
        array('', 'submit', 'registrar', 'Registrarme')
    );
    if(isset($_POST['registrar']))
    {
        if( es_texto($_POST['nombre']) && es_texto($_POST['apellidos']) &&                  
            es_texto($_POST['direccion']) && es_email($_POST['email']) && 
            es_texto($_POST['contrasena']))
        {
            //Envío de datos al modelo y redirección
            registrar($_POST['nombre'], $_POST['apellidos'], 
                      $_POST['direccion'], $_POST['email'], 
                      $_POST['contrasena']);
			
			global $twig;
			$template = $twig->load('registro_correcto.twig');
			echo $template->render();	
        }
		else 
		{
			global $twig;
			$template = $twig->load('registro_no_correcto.twig');
			echo $template->render();	
		}
    }
	else
    {
		global $twig;
		$template = $twig->load('registro.twig');
		echo $template->render(array ('formulario' => $formulario));	
	}

    
}