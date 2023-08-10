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
    $pdo = new PDO($cadenaConexion, 'daniel', 'Daniel88!',    
	array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)); 
	//PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ) para devolver objetos, pero fetchObject ya lo hace
    return $pdo;
    }
	
catch (PDOException $e) {
    return null;
	die(json_encode(array('outcome' => false, 'message' => 'Conexión a BD no conseguida')));
    }
}

/**
* Consulta a la BD "TIENDA" y busca titulos de libro en función del input del usuario

* @param string $busqueda, que es el input que el usuario escribe en el formulario

* @return array Retorna $coincidencias(una cadena de caracteres) si encuentra coincidencias con la búsqueda en mysql
* @return null retorna null si hay algún error
*/




function get_sugerencias_ajax($busqueda){
	$pdo = conexion();
	if($pdo){
		$producto = [];
		$coincidenciasArray=array($producto);
		
		$coincidencias = "";
		//$coincidencias = "";
		try{
		//La búsqueda se realiza en mysql con el comando LIKE
		$sql = "SELECT nombre, id_prod FROM producto WHERE nombre LIKE '%$busqueda%'";
	
		$resultado = $pdo->query($sql);
		
		//Mientras el resultado de la query tenga líneas afectadas, convertidas a objeto con fetchObject
			while($producto = $resultado->fetchObject())
			{
				//La variable coincidencias se concatenará con un salto de línea mas el título del libro
				//$coincidencias .= "<br>".$producto->nombre;

				$coincidencias .= "<br><a href=http://localhost/proyecto/tienda/index.php/detalle_producto?id=". $producto->id_prod .">$producto->nombre</a>";
				/*	<a href="<?php echo $row['page_link'] ?>"><?php echo $row['page_title'] ?></a>*/
				
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
		$sugerencias = get_sugerencias_ajax($q);
		}
		//Si el input NO son caracteres del alfabeto
		else $sugerencias = "Por favor, introduzca sólo caracteres del alfabeto";
	}
	
	// Salida: "no se encuentran sugerencias" si no hay sugerencias
	//echo $sugerencias === "" ? "<br> no se encuentran sugerencias" : $sugerencias;
	echo $sugerencias;


	

function lista_productos()
{	
		$pdo = conexion();
		if($pdo){
			try{
			//La búsqueda se realiza en mysql con el comando LIKE
			$sql = ("SELECT * FROM producto");		
			$lectura = $pdo->query($sql);
			$lista_productos= $lectura->fetchAll(PDO::FETCH_OBJ);
			$pdo = null;

		}	
		
		catch(PDOException $e){
			echo 'Excepción: ', $e->getMessage();
			return null;
		  }
		}  
		
	return $lista_productos;
			
		
}


function detalle_producto($id)
{	
	$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : "";
		$pdo = conexion();
		if($pdo){
			try{
			//La búsqueda se realiza en mysql con el comando LIKE
			$sql = "SELECT p.id_prod, p.nombre, p.precio, p.stock, p.descripcion FROM producto p WHERE id_prod ='$id'";		
			$lectura = $pdo->query($sql);
			$producto= $lectura->fetchObject();
			$pdo = null;
		}	
		
		catch(PDOException $e){
			echo 'Excepción: ', $e->getMessage();
			return null;
		  }
		}  
		
	return $producto;
			
		
}


function datos_cliente($email)
{	
	$email = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : "";
		$pdo = conexion();
		if($pdo){
			try{
			//La búsqueda se realiza en mysql con el comando LIKE
			$sql = "SELECT c.nif, c.nombre, c.apellidos, c.telefono, c.direccion, c.localidad, c.cod_postal, c.provincia FROM cliente c WHERE email ='$email'";		
			$lectura = $pdo->query($sql);
			$producto= $lectura->fetchObject();
			$pdo = null;
		}	
		
		catch(PDOException $e){
			echo 'Excepción: ', $e->getMessage();
			return null;
		  }
		}  
		
	return $producto;
			
		
}

























function opiniones()
{
	//Obtener usuario y sugerencia
		$listaOpiniones = array(
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
	
    return $listaOpiniones;
}

function registrar($nombre, $apellidos, $direccion, $email, $contrasena)
{
	//Registrar el nuevo usuario en BD
}

?>
		