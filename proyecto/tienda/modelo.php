<?php
// modelo.php

/**
* Crea una conexión PDO directa a la BD 2libros"

* @return object Retorna $pdo (un objeto de conexión pdo) si todo va bien
* @return null retorna null si hay algún error
*/


function conexion()
{
try {    
//Conexión PDO
    $cadenaConexion="mysql:host=localhost;dbname=tienda;charset=utf8";
    $pdo = new PDO($cadenaConexion, 'daniel', 'Daniel88!');     
    return $pdo;
    }
	
catch (PDOException $e) {
    return null;
	die('Conexión a base de datos no conseguida');
    }
}

/**
* Consulta a la BD "TIENDA" y busca titulos de libro en función del input del usuario

* @param string $busqueda, que es el input que el usuario escribe en el formulario

* @return array Retorna $coincidencias(una cadena de caracteres) si encuentra coincidencias con la búsqueda en mysql
* @return null retorna null si hay algún error
*/



function get_sugerencias($busqueda){
	$pdo = conexion();
	if($pdo){
		$coincidencias = "";
		try{
		//La búsqueda se realiza en mysql con el comando LIKE
		$sql = "SELECT nombre FROM producto WHERE nombre LIKE '%$busqueda%'";
	
		$resultado = $pdo->query($sql);
		//Mientras el resultado de la query tenga líneas afectadas, convertidas a objeto con fetchObject
			while($producto = $resultado->fetchObject())
			{
				//La variable coincidencias se concatenará con un salto de línea mas el título del libro
				$coincidencias .= "<br>".$producto->nombre;
				
			}
		
		$pdo=null; 
		return $coincidencias;
		
	}	
	
	catch(PDOException $e){
		echo 'Excepción: ', $e->getMessage();
		return null;
	  }
	}  
	}
	
	// obtenemos el parámetro GET de la URL (Ej: "sugerenciasPHP.php?q=Anna")
	$q = isset($_REQUEST["q"]) ? $_REQUEST["q"] : "";
	//$q = $_REQUEST["q"];

	// Inicializo la variable que contendrá las coincidencias
	$sugerencias ="";
	
	
	//Entra en el bucle si el parámetro obtenido del GET ($q) es diferente a ""
	if ($q !== "") 
	{
		//Comprubeo que el input sólo consta de caracteres del alfabeto, tras sustituir los espacios en blanco por '' (a efectos, anularlos)
		if(ctype_alpha(str_replace(' ', '', $q)) === true){
		//Si el usuario ha insertado datos se pasan a minúscula
		$q = strtolower($q);
		//Almacenamos la longitud de la palabra
		$len=strlen($q);
		//Ahora vamos a buscar coincidencias en la base de datos
		$sugerencias = get_sugerencias($q);
		}
		//Si el input NO son caracteres del alfabeto
		else $sugerencias = "Por favor, introduzca sólo caracteres del alfabeto";
	}
	
	// Salida: "no se encuentran sugerencias" si no hay sugerencias
	//echo $sugerencias === "" ? "<br> no se encuentran sugerencias" : $sugerencias;
	echo $sugerencias;
	
	


function cargar_datos()
{
	//Carga de los datos

	$articulos = array(
		0 => array(
			"id" => 0,
			"titulo" => "Altavoz Phoenix",
			"a_imagen" => "altavoz_phoenix.jpg"),	
		1 => array(
			"id" => 1,
			"titulo" => "Auriculares Urbanista",
			"a_imagen" => "auriculares_urbanista.jpg"),
		2 => array(
			"id" => 2,
			"titulo" => "Ratón Logitech",
			"a_imagen" => "	raton_logitech.jpg") 
	);
	
	return $articulos;
}

function lista_articulos()
{
    $articulos = cargar_datos();
    return $articulos;
}

function articulo($id)
{
	$articulos = cargar_datos();
	$detalles = $articulos[$id];

    return $detalles;
}



function sugerencias()
{
	//Obtener usuario y sugerencia
		$listaSugerencias = array(
		array(
			"usuario" => "Pepe23",
			"sugerencia" => "Quiero precios más baratos"),	
		array(
			"usuario" => "jjabrahms",
			"sugerencia" => "Mejoren la parte gráfica"),
		array(
			"usuario" => "plopez",
			"sugerencia" => "Poca variedad de ratones") 
	);
	
    return $listaSugerencias;
}

function registrar($nombre, $apellidos, $direccion, $email, $contrasena)
{
	//Registrar el nuevo usuario en BD
}

?>
		