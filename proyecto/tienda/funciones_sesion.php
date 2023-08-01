<?php
function checkSession(){
    if(session_status() !== PHP_SESSION_ACTIVE)
    session_start(); //crear sesión, y si hay usuario logeado, recoge el usuario en una variable.
        if (!isset($_SESSION["usuario"])) {
            exit(header("location:login"));   // si no hay un usuario correctamente identificado, se redirige al login.
            //exit;     // evita seguir ejecutando código de ésta página 
        }else $usuario = ($_SESSION["usuario"]);
    }



function checkCesta(){    ///pendiente revision///
session_start();
   if (empty($_SESSION["cesta"])) $cesta = array();
   else{$cesta = $_SESSION["cesta"];}

   return $cesta;

  
}

function login_control()
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
                exit(header("location:home"));
                session_regenerate_id();  //para evitar ataque de fijacion de sesion (en redes compartidas)
            } else {
                $mensaje = "<h2>Usuario y/o contraseña incorrectos</h2>";
                $delay=2;
                header("Refresh:$delay");}
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
}

function closeSession() //elimina la sesión
{
    if ((session_status() === PHP_SESSION_ACTIVE)) {
        //echo "<p>Cerrando sesión</p>";
        //Si hay sesión abierta, se define como array vacío el contenido de la misma
        //$_SESSION['usuario'] = "";
        $_SESSION = array();        
        //Se limpia la cookie de sesion estableciendo tiempo de duración negativo, entre otras medidas
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        session_destroy();  //destruye sesion
    }
}

function killCookie() {
    if (isset($_COOKIE["horario"])) {
        setcookie("horario", "", time() - 42000);
    }  //elimina la cookie horario

}
function checkCookie(){
        global $choice;   ///declaro variale global para ser accesible en todo el documento - la usaré para el preset de la selección
        if (isset($_COOKIE["horario"])) {
            $choice = $_COOKIE["horario"];
    }else $choice ="";
}
?>