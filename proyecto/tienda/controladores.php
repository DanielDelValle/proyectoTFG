<?php
// controladores.php
require_once '../vendor/autoload.php';



$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

require_once 'funciones_sesion.php';
require_once "modelo.php";
require_once 'validadores.php';
//include 'Cart.php';
//include 'cartAction.php';

//session_destroy();
$tel_ok = false;
$email_ok = false;
$usuario = "";
$contrasena = "";
$mensaje = "";
$cesta = array();
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


// Controlador específico de artículo
function controlador_detalle($id)
{
    $cesta = checkCesta();
    $producto = detalle_producto($id);
// Carga la plantilla que se mostrará al usuario con los datos recuperados 
    // del modelo
	global $twig;
    // Carga la plantilla que se mostrará al usuario con los datos recuperados 
    // del modelo
    
    $template = $twig->load('detalle_producto.html');  
	echo $template->render(array ( 'producto' => $producto, 'cesta' => $cesta));
}



function controlador_login()
{session_start();


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
    
        if (isset($_POST["contrasena"])) $contrasena = $_POST["contrasena"]; //en caso de que queramos guardar la contreaseña
        else $contrasena = "";
    
        if ($usuario != "" && $contrasena != "") {
            //Comprobar credenciales
            //if ($usuario == "admin" && md5($contrasena) == $pass)
            if (($usuario == $user) && (crypt($contrasena, '$1$somethin$')) == $pass) {
                //Login correcto
                //Establecer sesion autentificada
                $_SESSION["usuario"] = $usuario; 

                if (empty($_SESSION["cesta"])) $_SESSION["cesta"] = array();
                else $cesta = $_SESSION["cesta"];

                exit(header("location:home"));
            } else {
                $mensaje = "<h2>Usuario y/o contraseña incorrectos</h2>";

            }
        } else $mensaje = "<h2>Los campos usuario y contraseña son obligatorios<h2>";
    } else if (isset($_SESSION["usuario"])) {
        //Sesión ya iniciada
        $usuario = $_SESSION["usuario"];
        $mensaje = "";
    } else {
        //Carga en vacío sin sesión iniciada
        $usuario = "";
        $mensaje = "";
    }

    // Petición al modelo para que retorne la lista de productos de la BD
    // Carga la plantilla que se mostrará al usuario con los datos recuperados del modelo
	global $twig;
    $template = $twig->load('login.html');
    echo $template->render(array ( 'usuario' => $usuario, 'mensaje' =>$mensaje));
}


function controlador_home()
{checkSession();
    // Petición al modelo para que retorne la lista de productos de la BD
    $productos = lista_productos();
    // Carga la plantilla que se mostrará al usuario con los datos recuperados del modelo
	global $twig;
    // Carga la plantilla que se mostrará al usuario con los datos recuperados 
    // del modelo
    $usuario = $_SESSION["usuario"];
    if (isset($_POST["perfil"])) controlador_perfil();
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
{checkSession();
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





function controlador_cesta()
{checkSession();
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
    
    $template = $twig->load('cesta.html');
	echo $template->render(array ( 'usuario' =>$usuario ));



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