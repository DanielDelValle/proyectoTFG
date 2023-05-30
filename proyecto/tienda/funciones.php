<?php
function checkSession(){
session_start(); //crear sesión, y si hay usuario logeado, recoge el usuario en una variable.
    if (!isset($_SESSION["usuario"])) {
        exit(header("location:frutasdelvalle.php"));   // si no hay un usuario correctamente identificado, se redirige al login.
        //exit;     // evita seguir ejecutando código de ésta página        
}
}
function closeSession() //elimina la sesión
{
    if (isset($_SESSION["usuario"])) {
        //echo "<p>Cerrando sesión</p>";
        //Si hay sesión abierta, se define como array vacío el contenido de la misma
        //$_SESSION['usuario'] = "";
        $_SESSION = array();        
        //Se limpian las cookies estableciendo tiempo de duración negativo, entre otras cosas
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