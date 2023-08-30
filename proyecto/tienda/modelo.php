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
	array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_FOUND_ROWS => true)); 
	//PDO::ATTR_EMULATE_PREPARES, false   desactivar emulacion
	//PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ) para devolver objetos, pero fetchObject ya lo hace
    return $pdo;
    }
	
catch (PDOException $e) {
    return null;
	die(json_encode(array('resultado' => false, 'mensaje' => 'Conexión a BD no conseguida')));
    }return $pdo;
}

/**
* Consulta a la BD "TIENDA" y busca titulos de libro en función del input del usuario

* @param string $busqueda, que es el input que el usuario escribe en el formulario

* @return array Retorna $coincidencias(una cadena de caracteres) si encuentra coincidencias con la búsqueda en mysql
* @return null retorna null si hay algún error
*/

function conexion_mysqli(){

$servername = "localhost";
$username = 'daniel';
$password = 'Daniel88!';
$DB = 'tienda';

// Create connection

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = new mysqli($servername, $username, $password, $DB);

// Check connection
if (!$mysqli) {
	die("Fallo en la conexión a BD: " . mysqli_connect_error());
}
else echo "Conectado con éxito a BD";

return $mysqli;
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
		$sql = "SELECT nombre, id_prod 
				FROM producto 
				WHERE nombre LIKE '%$busqueda%'";
	
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

	function lista_users()
	{	
			$pdo = conexion();
			if($pdo){
				try{
				$pdo->beginTransaction();
				//con esta query recupero todos los mails de ambas tablas, osea todos los usuarios existentes
				$sql = ("SELECT email FROM cliente UNION ALL
						SELECT email FROM empleado
				 ");	
	
				$lectura = $pdo->prepare($sql);
	
				if(($lectura->execute())>0){ //si la consulta ha retornado algún resultado ---          
					$pdo->commit();
					$resultado = $lectura->rowCount(); // --- entonces retorno el número de clientes encontrados)
					$lista_users= $lectura->fetchAll(PDO::FETCH_OBJ);
				}
	
				else {
					$resultado=false;
					echo "No se encontraron usuarios";
					$pdo->rollback();		
					}	
				}	
		catch(PDOException $e){
			echo 'Excepción: ', $e->getMessage();
			$resultado = false;				
			}
			$pdo = null;
			return $lista_users;
	
	 }else{  return null; die("error en la conexión a la BD");} //en caso de no haber conexión, directamente se para el proceso
				
			
	}

	function lista_pedidos(){

		$pdo = conexion();
		if($pdo){
			try{
			$sql = "SELECT *
					FROM pedido
					ORDER BY creado_fecha DESC";
	
	
			$resultado = $pdo->query($sql);
			$pedidosArray = $resultado->fetchAll(PDO::FETCH_OBJ);
	
		/*	foreach($pedidosArray as $i => $pedido) {   
			}*/
			
			$mensaje = "Se han encontrado <b>" . $resultado->rowCount() . "</b> pedido(s) <br><br>"; 
			if ($resultado->rowCount()==0) $mensaje = "No se han encontrado pedidos";
			$pdo = null;  //cierro conexion para no mantener BD en espera
			//c.total_pedidos, c.total_gasto SI SE LLEGAN A INCORPORAR DICHAS COLUMNAS
		}	
		
		catch(PDOException $e){
			echo 'Excepción: ', $e->getMessage();
			return null;
		  }
		}  
		
	return $pedidosArray;
	}

	

function lista_productos()
{	
		$pdo = conexion();
		if($pdo){
			try{
			$pdo->beginTransaction();
			$sql = ("SELECT * FROM producto 
					 ORDER BY nombre");	

			$lectura = $pdo->prepare($sql);

			if(($lectura->execute())>0){ //si la consulta ha retornado algún resultado ---          
				$pdo->commit();
				$resultado = $lectura->rowCount(); // --- entonces retorno el número de autores afectados (borrados)

				$lista_productos= $lectura->fetchAll(PDO::FETCH_OBJ);
			//	echo 'Encontrados '. $resultado. ' productos';
			}

			else {
				$resultado=false;
				echo "No se encontraron productos - por favor, intentelo de nuevo";
				$pdo->rollback();		
				}	
			}	
	catch(PDOException $e){
		echo 'Excepción: ', $e->getMessage();
		$resultado = false;				
		}
		$pdo = null;
		return $lista_productos;

 }else{  return null; die("error en la conexión a la BD");} //en caso de no haber conexión, directamente se para el proceso
			
		
}


function detalle_producto($id)
{	
	$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : "";
		$pdo = conexion();
		if($pdo){
			try{
			$sql = "SELECT id_prod, nombre, precio, stock, descripcion 
					FROM producto
					WHERE id_prod ='$id'";		
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
function insert_cliente($nif, $nombre, $apellidos, $email, $telefono, $direccion, $localidad, $cod_postal, $provincia, $contrasena, $creado_fecha){
	$resultado = false;
	$pdo = conexion();
	if($pdo){
		try{//normalizo datos a minisculas para homogeneizar posibles errores al insertar datos.
		/*	$nif = strtolower($nif); $nombre = strtolower($nombre); $apellidos = strtolower($apellidos); $direccion = strtolower($direccion);
			$localidad = strtolower($localidad); $provincia = strtolower($provincia);*/
			$email = strtolower($email);
			$telefono = intval($telefono);
			$cod_postal = intval($cod_postal);
			$hash_passwd = password_hash($contrasena, PASSWORD_ARGON2ID);

			$pdo->beginTransaction();

			$sql = "INSERT INTO cliente(nif, nombre, apellidos, email, telefono, direccion, localidad, cod_postal, provincia, contrasena, creado_fecha) 
					VALUES ('".$nif."', '".$nombre."', '".$apellidos."', '".$email."', '".$telefono."', '".$direccion."', '".$localidad."', '".$cod_postal."', '".$provincia."', '".$hash_passwd."', '".$creado_fecha."');";


			$insertarCliente = $pdo->prepare($sql);

			if(($insertarCliente->execute())>0){ //si la consulta ha retornado algún resultado ---          
				$pdo->commit();
				$resultado = $insertarCliente->rowCount(); // --- entonces retorno el número de autores afectados (borrados)
			}

			else {
				$resultado=false;
				echo "No pudo crearse su cuenta - por favor, intentelo de nuevo";
				$pdo->rollback();		
				}	
			}	
	catch(PDOException $e){
		echo 'Excepción: ', $e->getMessage();
		$resultado = false;				
		}
	$pdo = null;
	return $resultado;

 }else{  return null; die("error en la conexión a la BD");} //en caso de no haber conexión, directamente se para el proceso


}


function alta_empleado($nif, $nombre, $apellidos, $email, $telefono, $direccion, $localidad, $cod_postal, $provincia, $contrasena, $creado_fecha, $tipo_cuenta){
	// select* from empleado para comparar por si duplicamos un DNI
	$pdo = conexion();
	if($pdo){
		$resultado = false;
		try{
			$email = strtolower($email);
			$telefono = intval($telefono);
			$cod_postal = intval($cod_postal);
			$hash_passwd = password_hash($contrasena, PASSWORD_ARGON2ID);
			$tipo_cuenta = strtolower($tipo_cuenta);

			$pdo->beginTransaction();

			$sql = "INSERT INTO empleado(nif, nombre, apellidos, email, telefono, direccion, localidad, cod_postal, provincia, contrasena, creado_fecha, tipo_cuenta) 
					VALUES ('".$nif."', '".$nombre."', '".$apellidos."', '".$email."', '".$telefono."', '".$direccion."', '".$localidad."', '".$cod_postal."', '".$provincia."', '".$hash_passwd."', '".$creado_fecha."', '".$tipo_cuenta."');";
			$insertarEmpleado = $pdo->prepare($sql);

			if(($insertarEmpleado->execute())>0){ //si la consulta ha retornado algún resultado ---          
				$pdo->commit();
				$resultado = $insertarEmpleado->rowCount(); // --- entonces retorno el número de autores afectados (borrados)
			}

			else {
				$resultado=false;
				echo "Ningún empleado dado de alta - intentelo de nuevo";
				$pdo->rollback();		
				}	
			}	
	catch(PDOException $e){
		echo 'Excepción: ', $e->getMessage();
		$resultado = false;				
		}
	$pdo = null;
	return $resultado;

 }else{  return null; die("error en la conexión a la BD");} //en caso de no haber conexión, directamente se para el proceso


}


		/*	try {
			$email = strtolower($email);
			$telefono = intval($telefono);
			$cod_postal = intval($cod_postal);
			$hash_passwd = password_hash($contrasena, PASSWORD_ARGON2ID);
			$tipo_cuenta = strtolower($tipo_cuenta);

			$sql="INSERT INTO empleado (nif, nombre, apellidos, email, telefono, direccion, localidad, cod_postal, provincia, contrasena, creado_fecha, tipo_cuenta) 
			VALUES (:nif, :nombre, :apellidos, :email, :telefono, :direccion, :localidad, :cod_postal, :provincia, :contrasena, :creado_fecha, :tipo_cuenta)";


		----	$dato = '';	
			$datos = [$nif, $nombre, $apellidos, $email, $telefono, $direccion, $localidad, $cod_postal, $provincia, $hash_passwd, $creado_fecha, $tipo_cuenta];								
			$stmt->bindParam(':?', $dato); 
			foreach($datos as $key=>$dato){  				(OTRA OPCION)
				$stmt->execute();
			}	-----

			$sentencia = $pdo->prepare($sql);     
			$sentencia ->bindParam(":nif", $nif);
			$sentencia ->bindParam(":nombre",$nombre);
			$sentencia ->bindParam(":apellidos",$apellidos);
			$sentencia ->bindParam(":email",$email);
			$sentencia ->bindParam(":telefono",$telefono);
			$sentencia ->bindParam(":direccion",$direccion);
			$sentencia ->bindParam(":localidad",$localidad);
			$sentencia ->bindParam(":cod_postal",$cod_postal);
			$sentencia ->bindParam(":provincia",$provincia);
			$sentencia ->bindParam(":contrasena",$hash_passwd);
			$sentencia ->bindParam(":creado_fecha",$creado_fecha);
			$sentencia ->bindParam(":tipo_cuenta",$tipo_cuenta);
			
			$Exec = $sentencia -> execute();

			if ($Exec) {
				echo "Empleado creado con éxito";
				return true;

			}else{				
				echo "Ocurrio un error en el alta del empleado";

			}
		}
			catch(PDOException $e){
				echo 'Excepción: ', $e->getMessage();				
				}
		
			} 
		$pdo = null;*/



function datos_cliente($email)
{	
		$pdo = conexion();
		if($pdo){
			try{
			//La búsqueda se realiza en mysql con el comando LIKE
			$sql = "SELECT nif, nombre, apellidos, email, telefono, direccion, localidad, cod_postal, provincia, contrasena, creado_fecha, estado_cuenta, tipo_cuenta
					FROM cliente WHERE email ='$email'";		
			$lectura = $pdo->query($sql);
			$cliente= $lectura->fetchObject();
			return $cliente;
			//total_pedidos, c.total_gasto SI SE LLEGAN A INCORPORAR DICHAS COLUMNAS
		}	
		
		catch(PDOException $e){
			echo 'Excepción: ', $e->getMessage();
			return null;
		  }
		}  	
		$pdo = null;
	
}

function datos_empleado($email)
{		
		$pdo = conexion();
		if($pdo){
			try{
			//La búsqueda se realiza en mysql con el comando LIKE
			$sql = "SELECT nif, nombre, apellidos, email, telefono, direccion, localidad, cod_postal, provincia, contrasena, creado_fecha, estado_cuenta, tipo_cuenta
					FROM empleado WHERE email ='$email'";		
			$lectura = $pdo->query($sql);
			$empleado= $lectura->fetchObject();
			return $empleado;

		}	
		
		catch(PDOException $e){
			echo 'Excepción: ', $e->getMessage();
			return null;
		  }
		}  	
		$pdo = null;
	
}


function insert_productos_pedido($id_pedido, $cesta){

	//En esta operacion usaré un conector mysqli para poder usar su multy_query y simplificar el proceso (en el resto uso PDO)
		$sql = "";
		$mensaje = "";
		$mysqli = conexion_mysqli();
		if($mysqli){
			try{
				foreach($cesta as $producto){

					//$sql .= "INSERT INTO pedido_productos (id_pedido, id_prod, cantidad) VALUES ('".$id_pedido."', '".$producto['id_prod']."', '".$producto['cantidad']."');";
					$sql .= "INSERT INTO productos_pedido (id_pedido, id_prod, nombre, cantidad) 
					VALUES ('".$id_pedido."', '".$producto['id_prod']."', '".$producto['nombre']."', '".$producto['cantidad']."');";
				}
				
			$insertarProductosPedido = $mysqli->multi_query($sql);
			if($insertarProductosPedido) {echo $mysqli->affected_rows; return true;}
			else {echo 'ERROR AL INSERTAR PRODUCTOS'; return false;}
			
		}	
		
		catch(Exception $e){
			echo 'Excepción: ', $e->getMessage();
			return null;
			}
		} 
		$mysqli.close();
}


function insert_pedido($id_pedido, $nif, $total_precio, $total_kg, $forma_pago, $creado_fecha, $notas){

	$pdo = conexion();
	if($pdo){
		try{

			$sql = "INSERT INTO pedido(id_pedido, nif_cliente, total_precio, total_kg, forma_pago, creado_fecha, enviado_fecha, entregado_fecha, notas) 
					VALUES ('".$id_pedido."', '".$nif."', '".$total_precio."', '".$total_kg."', '".$forma_pago."', '".$creado_fecha."', NULL, NULL, '".$notas."');";


		$insertarPedido = $pdo->query($sql);
		//return 	$insertarPedido->rowCount();
		if($insertarPedido){
		//	else echo 'ERROR AL INSERTAR PEDIDO';
		$pdo = null;
		return true;}
	}	
	
	catch(PDOException $e){
		echo 'Excepción: ', $e->getMessage();
		return null;
		}
		
	}  
	$pdo=null;
}



function pedidos_usuario($nif){ //añadir argumento "orden" para añadir opcion en base a qué ordenarlo

		$pdo = conexion();
		if($pdo){
			try{
			//La búsqueda se realiza en mysql con el comando LIKE
			$sql = "SELECT p.id_pedido, p.nif_cliente, p.total_precio, p.total_kg, p.forma_pago, p.estado_pago, p.estado_pedido, p.creado_fecha, p.enviado_fecha, p.entregado_fecha, p.notas
					FROM pedido p JOIN cliente c ON p.nif_cliente = c.nif
					WHERE p.nif_cliente LIKE '%$nif%'
					ORDER BY p.creado_fecha DESC";
			//USO LIKE PARA BUSCAR POR NIF SIN TENER QUE ESCRIBIRLO ENTERO, PARA MAYOR FACILIDAD

			$resultado = $pdo->query($sql);
			$pedidosArray = $resultado->fetchAll(PDO::FETCH_OBJ);

		/*	foreach($pedidosArray as $i => $pedido) {   
			}*/
			
			if($resultado->rowCount()>0) $mensaje = "Se han encontrado <b>" . $resultado->rowCount() . "</b> pedido(s) <br><br>"; 
			else $mensaje = "No se han encontrado pedidos";
			//c.total_pedidos, c.total_gasto SI SE LLEGAN A INCORPORAR DICHAS COLUMNAS
		}	
		
		catch(PDOException $e){
			echo 'Excepción: ', $e->getMessage();
			return null;
		  }
		}  	
		$pdo=null;
		
	return $pedidosArray;
	}
	
function pedido_pagado($id_pedido){
			
		$pdo = conexion();
		if($pdo){
			try{				
		$pdo->beginTransaction();

		$sql = "UPDATE pedido 
				SET estado_pago = 'pagado'
				WHERE id_pedido = '$id_pedido'" ;


		$pedido_pagado = $pdo->prepare($sql);

		if(($pedido_pagado->execute())>0){ //si la consulta ha retornado algún resultado ---          
			$pdo->commit();
			$resultado = $pedido_pagado->rowCount(); // --- entonces retorno el número de autores afectados (borrados)
		}

		else {
			$resultado=false;
			echo "Ningún pedido modificado - intentelo de nuevo";
			$pdo->rollback();		
			}	
		}	
catch(PDOException $e){
	echo 'Excepción: ', $e->getMessage();
	$resultado = false;				
	}
$pdo = null;
return $resultado;

}else{  return null; die("error en la conexión a la BD");} //en caso de no haber conexión, directamente se para el proceso


	}

	function detalle_pedido($id_pedido)
	{	
		$id_pedido = isset($_GET["id_pedido"]) ? $_GET["id_pedido"] : "";
			$pdo = conexion();
			if($pdo){
				try{
				//La búsqueda se realiza en mysql con el comando LIKE
				$sql = "SELECT * FROM productos_pedido p WHERE id_pedido ='$id_pedido'";		

				$resultado = $pdo->query($sql);
				$productosArray = $resultado->fetchAll(PDO::FETCH_OBJ);
				$pdo = null;
			}	
			
			catch(PDOException $e){
				echo 'Excepción: ', $e->getMessage();
				return null;
			  }
			}  
			
		return $productosArray;
							
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
		