<?php
/**
* Comprueba que una cadena contenga exclusivamente caracteres, y espacios en blanco con \s.
*
* @param string $cadena Cadena que se va a comprobar.
*
* @return bool Retorna true si es texto.
*/
function es_texto($cadena) {
    //definimos el patrón
    $patron = '/^[a-zA-Zá-úÁ-ÚñÑ\s?]+$/';
	//$patron = '/^[a-zA-Z]+(\s*[a-zA-Z]*)*[a-zA-Z]+$/';
     
    return (preg_match($patron, $cadena));	
}



/**
* Comprueba que el teléfono tenga 9 cifras y comience por 6, 7, 8 ó 9.
*
* @param string $tel Cadena que se va a comprobar.
*
* @return bool Retorna true si es correcto.
*/

function valid_tel($tel){
    $patron = '/^(6|7|8|9)+([0-9]){8}/';
     //este patrón permite que el número empiece por 6 o 7 para móviles, y por 8 o 9 si se tratase de un fijo (el mismo campo admite ambos valores)
    return (preg_match($patron, $tel));
 } 


/**
* Comprueba que una cadena tenga formato de email.
*
* @param string $email Cadena que se va a comprobar.
*
* @return bool Retorna true si tiene forma de email.
*/

function valid_email($email) {
    $email = strtolower($email);
    //definimos el patrón
    $patron = '/^[a-zA-Z0-9.!#$%&\'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/i';
 
    return (preg_match($patron, $email));
}


/**
* Comprueba que una contraseña (cadena) tenga el formato exigido: 8 caracteres incluyendo mayusc., minusc., cifras y signos especiales.
*
* @param string $clave Cadena que se va a comprobar.
*
* @return bool Retorna true si cumple con los requisitos: 6 caracteres incluyendo mayusc., minusc., cifras y signos especiales
*/

function valid_contrasena($contrasena) {

    //definimos el patrón
    $patron = '/^.*(?=.{8,})(?=.*\d)(?=.*[A-Z])(?=.*[a-z]).*$/';
 
    return (preg_match($patron, $contrasena));
}




/** Valida un email usando filter_var (una forma genérica de php de validar emails)
*  Devuelve true si es correcto o false en caso contrario
* @param    string  $email la dirección email a validar
* @return   bool retorna true si el mail cumple con el patrón
*/

function valid_email2($email)
{
  return (false !== filter_var($email, FILTER_VALIDATE_EMAIL));
}





/*

	$valida_nm = $_POST["nombre"];
	if (preg_match("/^[aA-zZ]{3,29}$/", $valida_nm)) {
		echo '<p style="color:green">Nombre correcto</p>'; 
	}else{
		echo '<p style="color:red">Nombre incorrecto</p>'; 
	}





    $valida_ed = $_POST["edad"];
	if (preg_match("/^[0-9]{1,2}$/", $valida_ed)) {
		echo '<p style="color:green">Edad correcta</p>'; 
	}else{
		echo '<p style="color:red">Edad incorrecta</p>'; 
	}

    $valida_tlf = $_POST["telefono"];
	if (preg_match("/^[9|8|6|7][0-9]{8}$/", $valida_tlf)) {
		echo '<p style="color:green">Teléfono correcto</p>'; 
	}else{
		echo '<p style="color:red">Teléfono incorrecto</p>'; 
	}


    $valida_mv = $_POST["movil"];
	if (preg_match("/^6[0-9]{8}$/", $valida_mv)) {
		echo '<p style="color:green">Movil correcto</p>'; 
	}else{
		echo '<p style="color:red">Movil incorrecto</p>'; 
	}*/
?>