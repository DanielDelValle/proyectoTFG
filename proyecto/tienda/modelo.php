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
	array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");  
	//PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ) para devolver objetos, pero fetchObject ya lo hace
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



function buscar_producto(){

	$pdo = conexion();
    if($pdo){
            try{				
				$sql= "SELECT * FROM producto WHERE id_prod = '$id'";  // QUERY ESTANDAR

				/*$sql= $pdo->prepare("SELECT * FROM producto WHERE id_prod = ?");  //QUERY PREPARADA
				$sql->execute([$id]);
				$resultado = $sql->fetchColumn();*/

                $resultado = $pdo->query($sql);
                while($fila= $resultado->fetchAll(PDO::FETCH_ASSOC)){

                }
                $mensaje = "Se han encontrado <b>" . $resultado->rowCount() . "</b> producto(s) <br><br>";
                if ($resultado->rowCount()==0)  return null; 
				else return $producto;

                 }
    
            catch(PDOException $e){
                echo 'Excepción: ', $e->getMessage();
                return null;
            }
           
        }else{    return null; die("error en la conexión a la BD"); //en caso de no haber conexión, directamente se para el proceso
            
            
            
        }  return $producto;
        
    }






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

				$coincidencias .= "<br><a href=http://localhost/proyecto/tienda/index.php/detalle_articulo?id=". $producto->id_prod .">$producto->nombre</a>";
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


	

function cargar_datos()
{	
		$pdo = conexion();
		if($pdo){
			try{
			//La búsqueda se realiza en mysql con el comando LIKE
			$sql = ("SELECT * FROM producto");		
			$lectura = $pdo->query($sql);
			$articulos= $lectura->fetchAll(PDO::FETCH_OBJ);

		}	
		
		catch(PDOException $e){
			echo 'Excepción: ', $e->getMessage();
			return null;
		  }
		}  
		
	return $articulos;
			
		
}


function lista_articulos()
{
    $articulos = cargar_datos();
    return $articulos;
}


function detalle_articulo($id)
{
	$articulos = cargar_datos();
	$detalles = $articulos[$id-1];  //HAY QUE CAMBIAR ESTE METODO DE ACCESO PARA QUE SEA EXACTO (SOLO ESTA MOSTRANDO EL ORDEN DEL ARRAY -1 PARA CONTRARRESTAR LA POSICION "0")
	//echo serialize($detalles);   //convierte en string el resultado (array) como string

    return $detalles;
}

/*function detalle_articulo($id)
{	
	$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : "";
	echo $id;
		$pdo = conexion();
		if($pdo){
			try{
			//La búsqueda se realiza en mysql con el comando LIKE
			$sql = "SELECT p.id_prod, p.nombre, p.precio, p.unidad, p.stock, p.descripcion FROM producto p WHERE id_prod ='$id'";		
			$lectura = $pdo->query($sql);
			$articulo= $lectura->fetchAll(PDO::FETCH_ASSOC); //------- ERROR POSIBLE - INVESTIGAR FETCH OBJETO // ARRAY ------- fijarse que mvca del profe carga array
		echo serialize($articulo);  //PARA MOSTRAR EL ARRAY "ARTICULO" COMO STRING

		}	
		
		catch(PDOException $e){
			echo 'Excepción: ', $e->getMessage();
			return null;
		  }
		}  
		
	return $articulo;
			
		
}*/


function opiniones()
{
	//Obtener usuario y sugerencia
		$listaCpiniones = array(
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
		