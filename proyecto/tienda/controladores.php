<?php
// controladores.php
require_once '../vendor/autoload.php';



$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

require_once 'funciones_sesion.php';
require_once "modelo.php";
require_once 'validadores.php';
//include 'Cesta.php';
//include 'cartAction.php';

//session_destroy();
$tel_ok = false;
$email_ok = false;
$usuario = "";
$contrasena = "";
$mensaje = "";
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
{
    // Petición al modelo para que retorne la lista de productos de la BD
    $productos = lista_productos();

    // Carga la plantilla que se mostrará al usuario con los datos recuperados del modelo
	global $twig;
    // Carga la plantilla que se mostrará al usuario con los datos recuperados 
    // del modelo
    $template = $twig->load('productos.html');
	echo $template->render(array ( 'productos' => $productos));
}

function controlador_detalle($id)
{   session_start();
    $_SESSION['cesta'] = checkCesta();
    $producto = get_object_vars(detalle_producto($id));  //transformo el objeto que devuelve el modelo en array asociativo
// Carga la plantilla que se mostrará al usuario con los datos recuperados 
    // del modelo
	
    // Carga la plantilla que se mostrará al usuario con los datos recuperados 
    // del modelo
    if (isset($_POST["anadir_producto"])) {

        $usuario = checkSession();
        $_SESSION['cesta'] = checkCesta();
        $prod_add = array();  //$prod_add = new ArrayObject(); 
        
        $prod_add['id'] = (int)$producto['id_prod'];
        $prod_add['nombre'] = $producto['nombre'];
        $prod_add['precio'] = (float)$producto['precio'];
        $prod_add['cantidad'] = (float)1.0;
        
        //si el array "cesta" dentro de SESSION tiene alguna entrada, se compara la id del producto a introductir con los KEYS .
        //Si ya está dentro, sólo se actualiza la cantidad.
        if(count($_SESSION['cesta']) != 0){
            if(in_array($prod_add['id'], array_keys($_SESSION['cesta']))){
                $_SESSION['cesta'][$prod_add['id']]['cantidad'] +=1;
            } 
            else {
                //Si ninguno de los productos de la cesta es el que vamos a introducir, se introduce el mismo
                $_SESSION['cesta'][$prod_add['id']] = $prod_add;
            }
        
        }else{
            // Si la cesta esta vacia se introduce directamente el producto, dandole como indice la ID del mismo para mas facil identificacion.
            $_SESSION['cesta'][$prod_add['id']] = $prod_add;
        }

        }
        
        global $twig;
    $template = $twig->load('detalle_producto.html');  
	echo $template->render(array ( 'producto' => $producto));
    return $producto;
    
}

function controlador_cesta()
{$usuario = checkSession();
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
    if(isset($_POST['checkbox'])) {
    $checked = $_POST['checkbox'];
    if(intval($checked) == 1){
        foreach($_POST['checkbox'] as $val){
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

    if(isset($_POST["confirmar_pedido"])){
        exit(header("location:confirmar_pedido"));}


    if(isset($_POST["borrar_producto"])){
        $mensaje = borrar_producto();
    }


    if (isset($_POST["vaciar_cesta"])) {
        unset($_SESSION['cesta']);
        $mensaje = "Cesta Vaciada con Éxito";
        $delay=1;
        header("refresh:$delay");
    }
   
    if (isset($_POST["cerrar_sesion"])){
        controlador_adios();
        exit;
    }
    
    $template = $twig->load('cesta.html');
	echo $template->render(array ( 'usuario' =>$usuario, 'cesta' => $cesta, 'mensaje' =>$mensaje, 'total_kg'=> $total_kg, 'total_prods'=> $total_prods, 'total_precio' => $total_precio));
    var_dump($cesta);

}

function controlador_confirmar_pedido(){
    $usuario = checkSession();
    $_SESSION['cesta'] = checkCesta();
    $cesta = $_SESSION['cesta'];
    $mensaje="";
    $total_precio = $_SESSION['total_precio'];
    $total_kg = $_SESSION['total_kg'];
    $total_prods = $_SESSION['total_prods'];

    global $twig;
    $template = $twig->load('confirmar_pedido.html');
	echo $template->render(array ( 'usuario' =>$usuario, 'cesta' => $cesta, 'mensaje' => $mensaje, 'total_prods'=>$total_prods, 'total_kg'=> $total_kg, 'total_precio'=>$total_precio));

}



function controlador_login(){

{   session_start();
    if (isset($_POST["entrar"])) {
        //Procesar formulario
        //Obtener contraseña codificada de bd, por ejemplo:
        $user = "daniel";
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
            if (($usuario == $user) && (crypt($contrasena, '$1$somethin$')) == $pass) {
                //Login correcto
                //Establecer sesion autentificada
                $_SESSION["usuario"] = $usuario;    
                //$_SESSION["contrasena"] = $contrasena;                
                exit(header("location:home"));
                //session_regenerate_id();  //para evitar ataque de fijacion de sesion (en redes compartidas)
            } else {
                $mensaje = "Usuario y/o contraseña incorrectos";
                $delay=2;
                header("Refresh:$delay");}  //PENDIENTE AÑADIR AQUI CONTADOR DE INTENTOS
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


}
        
    // Petición al modelo para que retorne la lista de productos de la BD
    // Carga la plantilla que se mostrará al usuario con los datos recuperados del modelo
	global $twig;
    $template = $twig->load('login.html');
    echo $template->render(array ( 'usuario' => $usuario, 'mensaje' =>$mensaje));
}



function controlador_home()
{$usuario = checkSession();
    // Petición al modelo para que retorne la lista de productos de la BD
    $productos = lista_productos();
    // Carga la plantilla que se mostrará al usuario con los datos recuperados del modelo
	global $twig;
    // Carga la plantilla que se mostrará al usuario con los datos recuperados 
    // del modelo
    $usuario = $_SESSION["usuario"];
    if (isset($_POST["perfil"])) exit(header("location:perfil"));
    if (isset($_POST["cesta"])) exit(header("location:cesta"));
    if (isset($_POST["ver_productos"])) exit(header("location:index.php"));
   
   
    if (isset($_POST["cerrar_sesion"])){
        controlador_adios();
        exit;
    }
    
    
    $template = $twig->load('home.html');
	echo $template->render(array ( 'productos' => $productos, 'usuario' =>$usuario));



}



function controlador_perfil()
{$usuario = checkSession();
    // Petición al modelo para que retorne la lista de productos de la BD
    // Carga la plantilla que se mostrará al usuario con los datos recuperados del modelo
	global $twig;
    // Carga la plantilla que se mostrará al usuario con los datos recuperados 
    // del modelo
    $usuario = $_SESSION["usuario"];


    if (isset($_POST["cerrar_sesion"])){
        controlador_adios();
        exit;
    }
    
    $template = $twig->load('perfil.html');
	echo $template->render(array ( 'usuario' =>$usuario));



}





function controlador_adios()
{

    // Carga la plantilla que se mostrará al usuario con los datos recuperados del modelo
	global $twig;
    closeSession();
    killCookie();
 
    $template = $twig->load('adios.html');
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