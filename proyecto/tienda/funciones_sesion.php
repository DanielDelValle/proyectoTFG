<?php
function checkSession(){
    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
     //crear sesión, y si hay usuario logeado, recoge el usuario en una variable.
      if (!isset($_SESSION["usuario"])) {
         // $usuario = "";
            exit(header("location:iniciar_sesion"));   // si no hay un usuario correctamente identificado, se redirige al login.
            //exit;     // evita seguir ejecutando código de ésta página 
        }else{ 
        echo session_id();
        $usuario = $_SESSION["usuario"];
        return $usuario;}
    }
    }
/*ACLARACIONES DE $_SESSION:
    - session_write_close() = session_commit() (SINONIMOS)  */

function closeSession() //elimina la sesión sin borrar los datos de la cesta
    {  
            session_start();
            unset($_SESSION['usuario']);
            unset($_SESSION['pedido']);
            session_write_close();
           // session_destroy();  //destruye sesion
        }
   
    


function deleteSession() //elimina la sesión totalmente
{
    if ((session_status() === PHP_SESSION_ACTIVE)) {
        //echo "<p>Cerrando sesión</p>";
        //Si hay sesión abierta, se define como array vacío el contenido de la misma
        //$_SESSION['usuario'] = "";
        session_unset();  //EQUIVALE A->   $_SESSION = array();                
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


function checkCesta(){    

    $cesta = isset($_SESSION['cesta']) ? $_SESSION['cesta'] : array();
       return $cesta;
    
    }


?>