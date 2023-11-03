<?php
// modelo.php


/**
* Crea una conexión PDO directa a la BD tienda"
* @return true si la conexion se establece
* @return null retorna null si hay algún error
*/

function conexion()
{

	$servidor = 'localhost';
	$usuario = 'daniel';
	$contraseña = 'Daniel88!';
	$bd = 'tienda';

//Conexión PDO
try {
    
    $cadenaConexion = "mysql:dbname=$bd;host=$servidor";
    $pdo = new PDO($cadenaConexion, $usuario, $contraseña,
    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_FOUND_ROWS => true)) ;
	//PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ) para devolver objetos, pero fetchObject ya lo hace
	//PDO::ATTR_EMULATE_PREPARES, false   desactivar emulacion
	//PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ) para devolver array objetos // fetchObject devuelve objeto 

    return $pdo;

    }
catch (PDOException $e) {
    return null;
	die('Conexión a base de datos no conseguida');
    }
}


/**
* Crea una conexión mysqli directa a la BD tienda"
* @return true si la conexion se establece
* @return null retorna null si hay algún error
*/

function conexion_mysqli(){
	$servidor = 'localhost';
	$usuario = 'daniel';
	$contraseña = 'Daniel88!';
	$bd = 'tienda';
try {
	$cadenaConexion = "mysql:dbname=$bd;host=$servidor";

	// Create connection 
	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	$mysqli = new mysqli($cadenaConexion, $usuario, $contraseña);

	return $mysqli;
}
catch (PDOException $e) {
    return null;
	die('Conexión a base de datos no conseguida');
    }
}


function backup_bbdd($tabla){

	$pdo = conexion();
	if($pdo){$resultado = 'Conectado a BBDD' ;
		try{
//opcion 1 

/*$tablas = ['cliente, empleado, producto, productos_pedido, facturacion, factura'];

		foreach ($tablas as $tabla){
				$backupFile = 'tienda.'.$tabla.date("d-m-Y_-H-i-s").'.sql';
				$query      = "SELECT * INTO OUTFILE '$backupFile' FROM $tabla";
				$resultado = $pdo->query($query);*/


//opcion 2		(devuelve archivo vacio)
		exec("cd /usr/bin/");

		$backupFile = 'tienda'.date("d-m-Y_-H-i-s").".rar";
			$command = "C:/xampp/mysql/bin/mysqldump.exe --host=localhost --user=daniel --password='Daniel88' tienda > $backupFile 2>&1"; //por cuestion de permisos, no funciona
			exec($command);
		}		
		catch(PDOException $e){
			echo 'Excepción: ', $e->getMessage();
			$resultado = false;				
	}
		$pdo = null;
		return $resultado;

	}else{  return null; die("error en la conexión a la BD");

	}
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


//Unifico ambas listas de usuarios(empleado + cliente) para un unico controlador de autenticacion
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
	//DEVUELVE LISTA DE TODOS PEDIDOS ORDENADOS DE MÁS RECIENTE FECHA CREACIÓN A MÁS ANTIGUAS
	//function lista_pedidos($intervalo){ WHERE create_date BETWEEN NOW() - INTERVAL '$intervalo' DAY AND NOW()  - AÑADIR INTERVALO SI QUEREMOS ACOTAR

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

	
//DEVUELVE LISTA DE TODAS LAS CUENTAS (USUARIO Y EMPLEADO) ORDENADAS DE MÁS RECIENTE FECHA CREACIÓN A MÁS ANTIGUAS
function lista_cuentas(){

	$pdo = conexion();
	if($pdo){
		try{
	$sql = "SELECT * FROM cliente 
				UNION ALL SELECT * FROM empleado
				ORDER BY creado_fecha DESC";

		$resultado = $pdo->query($sql);
		$lista_cuentas = $resultado->fetchAll(PDO::FETCH_OBJ);
		$mensaje = "Se han encontrado <b>" . $resultado->rowCount() . "</b> pedido(s) <br><br>"; 
		if ($resultado->rowCount()==0) $mensaje = "No se han encontrado pedidos";
		$pdo = null;  //cierro conexion para no mantener BD en espera
	}		
	catch(PDOException $e){
		echo 'Excepción: ', $e->getMessage();
		return null;
		}
	}  
	
return $lista_cuentas;
}

//RETORNA LISTA DE CUENTAS EMPLEADO Y USUARIO CUYO MAIL ESTÁ CONTENIDO O COINCIDE CON BUSQUEDA 
function lista_cuentas_email($email){

	$pdo = conexion();
	if($pdo){
		try{
		$sql = "SELECT * FROM cliente 
				WHERE cliente.email LIKE '%$email%'
				UNION SELECT * FROM empleado
				WHERE empleado.email LIKE '%$email%'
				ORDER BY creado_fecha DESC";

		$resultado = $pdo->query($sql);
		$lista_cuentas = $resultado->fetchAll(PDO::FETCH_OBJ);		
		$mensaje = "Se han encontrado <b>" . $resultado->rowCount() . "</b> pedido(s) <br><br>"; 
		if ($resultado->rowCount()==0) $mensaje = "No se han encontrado pedidos";
		$pdo = null;  //cierro conexion para no mantener BD en espera

	}		
	catch(PDOException $e){
		echo 'Excepción: ', $e->getMessage();
		return null;
		}
	}  
	
return $lista_cuentas;
}


function facturacion_pedido($id_pedido){

	$pdo = conexion();
	if($pdo){
		try{		
		$sql = "SELECT * FROM facturacion 
				WHERE id_pedido = '$id_pedido'
				ORDER BY id_factura DESC
				";

		$resultado = $pdo->query($sql);
		$facturas_pedido = $resultado->fetchObject();		
		$mensaje = "Se han encontrado <b>" . $resultado->rowCount() . "</b> facturas(s) <br><br>"; 
		if ($resultado->rowCount()==0) $mensaje = "No se han encontrado facturas";
		$pdo = null;  //cierro conexion para no mantener BD en espera

	}		
	catch(PDOException $e){
		echo 'Excepción: ', $e->getMessage();
		return null;
		}
	}  
	
return $facturas_pedido;
}

function lista_facturas()   
	{	
			$pdo = conexion();
			if($pdo){
				try{
				$pdo->beginTransaction();
				//con esta query recupero todos los mails de ambas tablas, osea todos los usuarios existentes
				$sql = ("SELECT * FROM factura
						ORDER BY creado_fecha DESC");	
	
				$lectura = $pdo->prepare($sql);
	
				if(($lectura->execute())>0){ //si la consulta ha retornado algún resultado ---          
					$pdo->commit();
					$resultado = $lectura->rowCount(); // --- entonces retorno el número de clientes encontrados)
					$lista_facturas= $lectura->fetchAll(PDO::FETCH_OBJ);
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
	return $lista_facturas;
	
	 }else{  return null; die("error en la conexión a la BD");} //en caso de no haber conexión, directamente se para el proceso
				
			
	}	

	function facturas_busqueda($where){ 

		$pdo = conexion();
		if($pdo){
			try{

				$sql = "SELECT *
					FROM factura $where 
					ORDER BY creado_fecha DESC";

				
			$resultado = $pdo->query($sql);
			$pedidosArray = $resultado->fetchAll(PDO::FETCH_OBJ);

		/*	foreach($pedidosArray as $i => $pedido) {   
			}*/
			
			if($resultado->rowCount()>0) $mensaje = "Se han encontrado <b>" . $resultado->rowCount() . "</b> pedido(s) <br><br>"; 
			else $mensaje = "No se han encontrado pedidos";
	}	
		
		catch(PDOException $e){
			echo 'Excepción: ', $e->getMessage();
			return null;
		  }
		}  	
		$pdo=null;

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


function insert_cliente($nif, $nombre, $apellidos, $email, $telefono, $direccion, $localidad, $cod_postal, $provincia, $contrasena, $contrasena_fecha, $creado_fecha){
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

			$sql = "INSERT INTO cliente(nif, nombre, apellidos, email, telefono, direccion, localidad, cod_postal, provincia, contrasena, contrasena_fecha, creado_fecha) 
					VALUES ('".$nif."', '".$nombre."', '".$apellidos."', '".$email."', '".$telefono."', '".$direccion."', '".$localidad."', '".$cod_postal."', '".$provincia."','".$hash_passwd."', '".$contrasena_fecha."', '".$creado_fecha."');";


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


function alta_empleado($nif, $nombre, $apellidos, $email, $telefono, $direccion, $localidad, $cod_postal, $provincia, $contrasena, $contrasena_fecha, $creado_fecha, $tipo_cuenta){
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

			$sql = "INSERT INTO empleado(nif, nombre, apellidos, email, telefono, direccion, localidad, cod_postal, provincia, contrasena, contrasena_fecha, creado_fecha, tipo_cuenta) 
					VALUES ('".$nif."', '".$nombre."', '".$apellidos."', '".$email."', '".$telefono."', '".$direccion."', '".$localidad."', '".$cod_postal."', '".$provincia."', '".$hash_passwd."', '".$contrasena_fecha."','".$creado_fecha."', '".$tipo_cuenta."');";
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

		
function datos_cliente_nif($nif)   //BUSCA CLIENTE POR NIF
{	
		$pdo = conexion();
		if($pdo){
			try{
			//La búsqueda se realiza en mysql con el comando LIKE
			$sql = "SELECT nif, nombre, apellidos, email, telefono, direccion, localidad, cod_postal, provincia, contrasena, contrasena_fecha, creado_fecha, estado_cuenta, tipo_cuenta
					FROM cliente WHERE nif ='$nif'";		
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

function datos_cliente($email)  // BUSCA CLIENTE POR MAIL
{	
		$pdo = conexion();
		if($pdo){
			try{
			//La búsqueda se realiza en mysql con el comando LIKE
			$sql = "SELECT nif, nombre, apellidos, email, telefono, direccion, localidad, cod_postal, provincia, contrasena, contrasena_fecha, creado_fecha, estado_cuenta, tipo_cuenta
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
			$sql = "SELECT nif, nombre, apellidos, email, telefono, direccion, localidad, cod_postal, provincia, contrasena, contrasena_fecha, creado_fecha, estado_cuenta, tipo_cuenta
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

function cambiar_contrasena($email, $nueva_contrasena, $contrasena_fecha){  ///PENDIENTE QUERY QUE FUNCIONE (PROBABLEMENTE SUBQUERY Y SINO  1 POR CADA TABLA )
	$resultado = 0;
	$tipo='cliente';
	$pdo = conexion();
	if($pdo){
		//Para discernir si tengo que modificar la tabla empleado o la tabla cliente
		$dominio = ltrim(strstr($email, '@'), '@');
		if ($dominio === 'frutasdelvalle.com')
		$tipo = 'empleado';
		$hash_passwd = password_hash($nueva_contrasena, PASSWORD_ARGON2ID);

		try{				
			$pdo->beginTransaction();			

			$sql = "UPDATE $tipo
					SET contrasena = '$hash_passwd'
					WHERE email = '$email'";


			$contrasena_modificada = $pdo->prepare($sql);

		if(($contrasena_modificada->execute())>0){ //si la consulta ha retornado algún resultado ---          
			$pdo->commit();
			$resultado = true; // --- entonces retorno el número de autores afectados (borrados)
		}

	else {
		$resultado=false;
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

function renovar_contrasena($email)   //BUSCA CLIENTE POR NIF
{	
		$pdo = conexion();
		if($pdo){
			try{
			//La búsqueda se realiza en mysql con el comando LIKE
			$sql = "SELECT contrasena_fecha, creado_fecha, estado_cuenta, tipo_cuenta
					FROM cliente WHERE nif ='$nif'";		
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

function modificar_cuenta($email, $nuevo_estado){  ///PENDIENTE QUERY QUE FUNCIONE (PROBABLEMENTE SUBQUERY Y SINO  1 POR CADA TABLA )
	$resultado = 0;
	$tipo='cliente';
	$pdo = conexion();
	if($pdo){
		//Para discernir si tengo que modificar la tabla empleado o la tabla cliente
		$dominio = ltrim(strstr($email, '@'), '@');
		if ($dominio === 'frutasdelvalle.com')
		$tipo = 'empleado';

		try{				
			$pdo->beginTransaction();			

			$sql = "UPDATE $tipo
					SET estado_cuenta = '$nuevo_estado'
					WHERE email = '$email'";


			$cuenta_modificada = $pdo->prepare($sql);

		if(($cuenta_modificada->execute())>0){ //si la consulta ha retornado algún resultado ---          
			$pdo->commit();
			$resultado = $resultado + $cuenta_modificada->rowCount(); // --- entonces retorno el número de autores afectados (borrados)
		}

	else {
		//$resultado=false;
		echo "Ninguna cuenta modificada - intentelo de nuevo";
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

function eliminar_cuenta($email){  ///PENDIENTE QUERY QUE FUNCIONE (PROBABLEMENTE SUBQUERY Y SINO  1 POR CADA TABLA )
	$resultado = 0;
	$tipo='cliente';
	$pdo = conexion();
	if($pdo){
		//Para discernir si tengo que modificar la tabla empleado o la tabla cliente
		$dominio = ltrim(strstr($email, '@'), '@');
		if ($dominio === 'frutasdelvalle.com')
		$tipo = 'empleado';

		try{				
			$pdo->beginTransaction();			

			$sql = "DELETE
					FROM $tipo
					WHERE email = '$email'"
					;

			$cuenta_eliminada = $pdo->prepare($sql);

		if(($cuenta_eliminada->execute())>0){ //si la consulta ha retornado algún resultado ---          
			$pdo->commit();
			$resultado = $resultado + $cuenta_eliminada->rowCount(); // --- entonces retorno el número de autores afectados (borrados)
		}

	else {
		//$resultado=false;
		echo "Ninguna cuenta eliminada - intentelo de nuevo";
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

function producto_actualizado($id_prod, $nombre, $stock, $precio, $descripcion){
			
	$pdo = conexion();
	if($pdo){
		try{				
	$pdo->beginTransaction();
			//Actualizo el estado del pago y el del pedido, y establezco fecha de cancelacion a NULL por si hubiese sido cancelado y reactivado. 
	$sql = "UPDATE producto 
			SET nombre = '$nombre', stock = '$stock', precio = '$precio', 'descripcion = '$descripcion'
			WHERE id_prod= '$id_prod'" ;


	$producto_actualizado = $pdo->prepare($sql);

	if(($producto_actualizado->execute())>0){ //si la consulta ha retornado algún resultado ---          
		$pdo->commit();
		$resultado = $producto_actualizado->rowCount(); // --- entonces retorno el número de autores afectados (borrados)
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




function insert_productos_pedido($id_pedido, $cesta){

	//En esta operacion usaré un conector mysqli para poder usar su multy_query y simplificar el proceso (en el resto uso PDO)
		$sql = "";
		$mensaje = "";
		$mysqli = conexion_mysqli();
		if($mysqli){
			try{
				foreach($cesta as $producto){

					//$sql .= "INSERT INTO pedido_productos (id_pedido, id_prod, cantidad) VALUES ('".$id_pedido."', '".$producto['id_prod']."', '".$producto['cantidad']."');";
					$sql .= "INSERT INTO productos_pedido (id_pedido, id_prod, nombre, cantidad, precio) 
					VALUES ('".$id_pedido."', '".$producto['id_prod']."', '".$producto['nombre']."', '".$producto['cantidad']."' , '".$producto['precio']."');";
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


function insert_pedido($id_pedido, $nif, $total_mercancia, $total_kg, $coste_envio, $total_pedido, $forma_pago, $creado_fecha, $notas){

	$pdo = conexion();
	if($pdo){
		try{
			$notas=utf8_decode($notas);//para evitar errores con los caracteres especiales (ñ, Ñ, vocales acentuadas, etcf)
			$sql = "INSERT INTO pedido(id_pedido, nif_cliente, total_mercancia, total_kg, coste_envio, total_pedido, forma_pago, creado_fecha, enviado_fecha, entregado_fecha, notas) 
					VALUES ('".$id_pedido."', '".$nif."', '".$total_mercancia."', '".$total_kg."', '".$coste_envio."', '".$total_pedido."', '".$forma_pago."', '".$creado_fecha."', NULL, NULL, '".$notas."');";


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

function ventas_producto_kg($id_producto){
	$pdo = conexion();
	if($pdo){
		try{
		$sql = "SELECT SUM(cantidad)
				FROM productos_pedido
				WHERE id_prod = $id_producto
				";


		$resultado = $pdo->query($sql);
		$ventas_producto_kg = $resultado->fetchAll(PDO::FETCH_OBJ);
		
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
	
return $ventas_producto_kg;
}

function ventas_producto_eur($id_producto){
	$pdo = conexion();
	if($pdo){
		try{
		$sql = "SELECT SUM(cantidad)
				FROM productos_pedido
				WHERE id_prod = $id_producto
				";


		$resultado = $pdo->query($sql);
		$ventas_producto_eur = $resultado->fetchAll(PDO::FETCH_OBJ);
		
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
	
return $ventas_producto_eur;
}
function situacion_pedido($id_pedido){ 

		$pdo = conexion();
		if($pdo){
			try{

				$sql = "SELECT estado_pago, estado_pedido
					FROM pedido WHERE id_pedido = '$id_pedido'";

				
			$resultado = $pdo->query($sql);
			$situacion_pedido = $resultado->fetchObject();

			
			if($resultado->rowCount()>0) $mensaje = "Se han encontrado <b>" . $resultado->rowCount() . "</b> pedido(s) <br><br>"; 
			else $mensaje = "No se han encontrado pedidos";
	}	
		
		catch(PDOException $e){
			echo 'Excepción: ', $e->getMessage();
			return null;
		  }
		}  	
		$pdo=null;

	return $situacion_pedido;
	}

function situacion_factura($id_factura){ 

		$pdo = conexion();
		if($pdo){
			try{

				$sql = "SELECT estado_fact
					FROM factura WHERE id_factura = '$id_factura'";

				
			$resultado = $pdo->query($sql);
			$situacion_factura = $resultado->fetchObject();

			
			if($resultado->rowCount()>0) $mensaje = "Se han encontrado <b>" . $resultado->rowCount() . "</b> factura(s) <br><br>"; 
			else $mensaje = "No se han encontrado facturas";
	}	
		
		catch(PDOException $e){
			echo 'Excepción: ', $e->getMessage();
			return null;
		  }
		}  	
		$pdo=null;

	return $situacion_factura;
	}

function pedidos_usuario($nif){ 

		$pdo = conexion();
		if($pdo){
			try{
			//La búsqueda se realiza en mysql con el comando LIKE
			$sql = "SELECT p.id_pedido, p.nif_cliente, p.total_kg, p.coste_envio, p.total_pedido, p.forma_pago, p.estado_pago, p.estado_pedido, p.creado_fecha, p.enviado_fecha, p.entregado_fecha, p.cancelado_fecha, p.notas
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

		}	
		
		catch(PDOException $e){
			echo 'Excepción: ', $e->getMessage();
			return null;
		  }
		}  	
		$pdo=null;
		
	return $pedidosArray;
	}

	function pedidos_busqueda($where){ 

		$pdo = conexion();
		if($pdo){
			try{

				$sql = "SELECT id_pedido, nif_cliente, total_kg, coste_envio, total_pedido, forma_pago, estado_pago, estado_pedido, creado_fecha, pagado_fecha, enviado_fecha, entregado_fecha, cancelado_fecha, notas
					FROM pedido $where 
					ORDER BY creado_fecha DESC";

				
			$resultado = $pdo->query($sql);
			$pedidosArray = $resultado->fetchAll(PDO::FETCH_OBJ);//retorna array de objetos

			if($resultado->rowCount()>0) $mensaje = "Se han encontrado <b>" . $resultado->rowCount() . "</b> pedido(s) <br><br>"; 
			else $mensaje = "No se han encontrado pedidos";
	}	
		
		catch(PDOException $e){
			echo 'Excepción: ', $e->getMessage();
			return null;
		  }
		}  	
		$pdo=null;

	return $pedidosArray;
	}


function pedido_pagado($id_pedido, $pagado_fecha){
			
		$pdo = conexion();
		if($pdo){
			try{				
		$pdo->beginTransaction();
				//Actualizo el estado del pago y el del pedido, y establezco fecha de cancelacion a NULL por si hubiese sido cancelado y reactivado. 
		$sql = "UPDATE pedido 
				SET estado_pago = 'pagado', estado_pedido = 'procesando', pagado_fecha = '$pagado_fecha', cancelado_fecha = NULL
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


function pedido_enviado($id_pedido, $enviado_fecha){
		
	$pdo = conexion();
	if($pdo){
		try{				
	$pdo->beginTransaction();

	$sql = "UPDATE pedido 
			SET estado_pedido = 'enviado', enviado_fecha = '$enviado_fecha'
			WHERE id_pedido = '$id_pedido'" ;


	$pedido_enviado = $pdo->prepare($sql);

	if(($pedido_enviado->execute())>0){ //si la consulta ha retornado algún resultado ---          
		$pdo->commit();
		$resultado = $pedido_enviado->rowCount(); // --- entonces retorno el número de autores afectados (borrados)
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

function pedido_entregado($id_pedido, $entregado_fecha){
		
	$pdo = conexion();
	if($pdo){
		try{				
	$pdo->beginTransaction();

	$sql = "UPDATE pedido 
			SET estado_pedido = 'entregado', entregado_fecha = '$entregado_fecha'
			WHERE id_pedido = '$id_pedido'" ;


	$pedido_entregado = $pdo->prepare($sql);

	if(($pedido_entregado->execute())>0){ //si la consulta ha retornado algún resultado ---          
		$pdo->commit();
		$resultado = $pedido_entregado->rowCount(); // --- entonces retorno el número de autores afectados (borrados)
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

function mercancia_devuelta($id_pedido){
		
	$pdo = conexion();
	if($pdo){
		try{				
	$pdo->beginTransaction();

	$sql = "UPDATE pedido 
			SET estado_pedido = 'devuelto', estado_pago = 'devolución'
			WHERE id_pedido = '$id_pedido'" ;


	$pedido_devuelto = $pdo->prepare($sql);

	if(($pedido_devuelto->execute())>0){ //si la consulta ha retornado algún resultado ---          
		$pdo->commit();
		$resultado = $pedido_devuelto->rowCount(); // --- entonces retorno el número de autores afectados (borrados)
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
	
function pedido_cancelado($id_pedido, $cancelado_fecha, $estado_pago_nuevo){
		
	$pdo = conexion();
	if($pdo){
		try{				
	$pdo->beginTransaction();

	$sql = "UPDATE pedido 
			SET estado_pedido = 'cancelado', estado_pago = '$estado_pago_nuevo', cancelado_fecha = '$cancelado_fecha'
			WHERE id_pedido = '$id_pedido'" ;


	$pedido_cancelado = $pdo->prepare($sql);

	if(($pedido_cancelado->execute())>0){ //si la consulta ha retornado algún resultado ---          
		$pdo->commit();
		$resultado = $pedido_cancelado->rowCount(); // --- entonces retorno el número de autores afectados (borrados)
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

function reactivar_pedido($id_pedido){
		
	$pdo = conexion();
	if($pdo){
		try{				
	$pdo->beginTransaction();

	$sql = "UPDATE pedido 
			SET estado_pago = 'pendiente', estado_pedido='procesando'
			WHERE id_pedido = '$id_pedido'" ;


	$pedido_reactivado = $pdo->prepare($sql);

	if(($pedido_reactivado->execute())>0){ //si la consulta ha retornado algún resultado ---          
		$pdo->commit();
		$resultado = $pedido_reactivado->rowCount(); // --- entonces retorno el número de autores afectados (borrados)
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
function devolver_pedido($id_pedido, $cancelado_fecha){
		
	$pdo = conexion();
	if($pdo){
		try{				
	$pdo->beginTransaction();

	$sql = "UPDATE pedido 
			SET estado_pedido = 'solicitud_devolucion', cancelado_fecha = '$cancelado_fecha'
			WHERE id_pedido = '$id_pedido'" ;


	$pedido_devolver = $pdo->prepare($sql);

	if(($pedido_devolver->execute())>0){ //si la consulta ha retornado algún resultado ---          
		$pdo->commit();
		$resultado = $pedido_devolver->rowCount(); // --- entonces retorno el número de autores afectados (borrados)
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
//Elimina todos los pedidos cancelados de la BBDD
function borrar_cancelados(){
		
	$pdo = conexion();
	if($pdo){
		try{				
	$pdo->beginTransaction();

	$sql = "DELETE FROM pedido
			WHERE estado_pedido = 'cancelado'" ;


	$pedido_cancelado = $pdo->prepare($sql);

	if(($pedido_cancelado->execute())>0){ //si la consulta ha retornado algún resultado ---          
		$pdo->commit();
		$resultado = $pedido_cancelado->rowCount(); // --- entonces retorno el número de autores afectados (borrados)
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
//Elimina los pedidos cancelados del cliente logeado
function borrar_cancelados_cliente($nif){
		
	$pdo = conexion();
	if($pdo){
		try{				
	$pdo->beginTransaction();

	$sql = "DELETE FROM pedido
			WHERE nif_cliente = '$nif' AND estado_pedido = 'cancelado'" ;


	$pedido_cancelado = $pdo->prepare($sql);

	if(($pedido_cancelado->execute())>0){ //si la consulta ha retornado algún resultado ---          
		$pdo->commit();
		$resultado = $pedido_cancelado->rowCount(); // --- entonces retorno el número de autores afectados (borrados)
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
//Esta funcion encuentra la (única)función activa relativa a cada pedido, es decir la última válida, y la anula.
function factura_activa($id_pedido){

	$pdo = conexion();
	if($pdo){
		try{
		//La búsqueda se realiza en mysql con el comando LIKE
		$sql = "SELECT id_factura FROM facturacion 
				WHERE id_pedido ='$id_pedido' AND estado_fact = 'activa'";		

		$resultado = $pdo->query($sql);
		$id_factura = $resultado->fetchObject();
		$pdo = null;
	}	
	
	catch(PDOException $e){
		echo 'Excepción: ', $e->getMessage();
		return null;
		}
	}  
	
return $id_factura;

	
}	


function facturacion_creada($id_pedido, $id_factura, $id_albaran, $nif_cliente){
			
	$resultado = false;
	$pdo = conexion();
	if($pdo){
		try{$pdo->beginTransaction();

			$sql = "INSERT INTO facturacion (id_factura, id_albaran, id_pedido, nif_cliente) 
					VALUES ('".$id_factura."', '".$id_albaran."', '".$id_pedido."', '".$nif_cliente."');";


			$crearFactura = $pdo->prepare($sql);

			if(($crearFactura->execute())>0){ //si la consulta ha retornado algún resultado ---          
				$pdo->commit();
				$resultado = $crearFactura->rowCount(); // --- entonces retorno el número de autores afectados (borrados)
				$pdo = null;
			}

			else {
				$resultado=false;
				echo "No pudo crearse la factura para el pedido $id_factura - por favor, intentelo de nuevo";
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

function factura_creada($id_factura, $id_pedido, $nif, $nombre, $direccion, $localidad, $cod_postal, $provincia,
						$total_mercancia, $coste_envio, $base_imponible, $iva, $total_pedido, $forma_pago, $creado_fecha, $contenido){
			
	$resultado = false;
	$pdo = conexion();
	if($pdo){
		try{$pdo->beginTransaction();

			$sql = "INSERT INTO factura (id_factura, id_pedido, nif, nombre, direccion, localidad, cod_postal, provincia, total_mercancia, coste_envio, base_imponible,
											iva, total_pedido, forma_pago, creado_fecha, contenido ) 
					VALUES ('".$id_factura."', '".$id_pedido."', '".$nif."', '".$nombre."', '".$direccion."', '".$localidad."', '".$cod_postal."', '".$provincia."',
							'".$total_mercancia."', '".$coste_envio."', '".$base_imponible."', '".$iva."', '".$total_pedido."', '".$forma_pago."', 
							'".$creado_fecha."', '".$contenido."'			
				
				);";


			$crearFactura = $pdo->prepare($sql);

			if(($crearFactura->execute())>0){ //si la consulta ha retornado algún resultado ---          
				$pdo->commit();
				$resultado = $crearFactura->rowCount(); // --- entonces retorno el número de autores afectados (borrados)
				$pdo = null;
			}

			else {
				$resultado=false;
				echo "No pudo crearse la factura para el pedido $id_factura - por favor, intentelo de nuevo";
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
function factura_cancelada($id_factura_activa, $rectif, $cancelado_fecha){
			
	$resultado = false;
	$pdo = conexion();
	if($pdo){
		try{$pdo->beginTransaction();

			$sql = "UPDATE factura 
					SET estado_fact = 'anulada', fact_rectif = '$rectif', cancelado_fecha = '$cancelado_fecha'
					WHERE id_factura = '$id_factura_activa'" ;



			$cancelarFactura = $pdo->prepare($sql);

			if(($cancelarFactura->execute())>0){ //si la consulta ha retornado algún resultado ---          
				$pdo->commit();
				$resultado = $cancelarFactura->rowCount(); // --- entonces retorno el número de facturas afectadas (borradas)
				$pdo = null;
			}

			else {
				$resultado=false;
				echo "No pudo anularse la factura para el pedido $id_factura - por favor, intentelo de nuevo";
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

function factura_rectif($id_factura_rectif, $id_pedido, $nif, $nombre, $direccion, $localidad, $cod_postal, $provincia,
						$total_mercancia, $coste_envio, $base_imponible, $iva, $total_pedido, $forma_pago, $creado_fecha, $contenido){
			
	$resultado = false;
	$pdo = conexion();
	if($pdo){
		try{$pdo->beginTransaction();

			$sql = "INSERT INTO factura (id_factura, id_pedido, nif, nombre, direccion, localidad, cod_postal, provincia, total_mercancia, coste_envio, base_imponible,
											iva, total_pedido, forma_pago, creado_fecha, contenido ) 
					VALUES ('".$id_factura_rectif."', '".$id_pedido."', '".$nif."', '".$nombre."', '".$direccion."', '".$localidad."', '".$cod_postal."', '".$provincia."',
							'".$total_mercancia."', '".$coste_envio."', '".$base_imponible."', '".$iva."', '".$total_pedido."', '".$forma_pago."', 
							'".$creado_fecha."', '".$contenido."'			
				
				);";


			$crearFactura = $pdo->prepare($sql);

			if(($crearFactura->execute())>0){ //si la consulta ha retornado algún resultado ---          
				$pdo->commit();
				$resultado = $crearFactura->rowCount(); // --- entonces retorno el número de autores afectados (borrados)
				$pdo = null;
			}

			else {
				$resultado=false;
				echo "No pudo crearse la factura para el pedido $id_factura - por favor, intentelo de nuevo";
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


function facturacion_cancelada($id_factura_activa, $rectif){
			
	$resultado = false;
	$pdo = conexion();
	if($pdo){
		try{$pdo->beginTransaction();

			$sql = "UPDATE facturacion 
					SET estado_fact = 'anulada', fact_rectif = '$rectif'
					WHERE id_factura = '$id_factura_activa'" ;



			$cancelarFactura = $pdo->prepare($sql);

			if(($cancelarFactura->execute())>0){ //si la consulta ha retornado algún resultado ---          
				$pdo->commit();
				$resultado = $cancelarFactura->rowCount(); // --- entonces retorno el número de facturas afectadas (borradas)
				$pdo = null;
			}

			else {
				$resultado=false;
				echo "No pudo anularse la factura para el pedido $id_factura - por favor, intentelo de nuevo";
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


function datos_pedido($id_pedido)
{	
		$pdo = conexion();
		if($pdo){
			try{
			$sql = "SELECT * FROM pedido
					WHERE id_pedido ='$id_pedido'";	

			$resultado = $pdo->query($sql);
			$datosPedido = $resultado->fetchObject();
			$pdo = null;
		}	
		
		catch(PDOException $e){
			echo 'Excepción: ', $e->getMessage();
			return null;
			}
		}  
		
	return $datosPedido;
		
}
function items_pedido($id_pedido)
{	
		$pdo = conexion();
		if($pdo){
			try{
			$sql = "SELECT * FROM productos_pedido
					WHERE id_pedido ='$id_pedido'";	

			$resultado = $pdo->query($sql);
			$detallePedidoArray = $resultado->fetchAll(PDO::FETCH_OBJ);//retorna array de objetos
			$pdo = null;
		}	
		
		catch(PDOException $e){
			echo 'Excepción: ', $e->getMessage();
			return null;
			}
		}  
		
	return $detallePedidoArray;
						
}
function contenido_factura($id_pedido)
{	
		$pdo = conexion();
		if($pdo){
			try{
			$sql = "SELECT id_prod, nombre, cantidad, precio FROM productos_pedido
					WHERE id_pedido ='$id_pedido'";	

			$resultado = $pdo->query($sql);
			$detallePedidoArray = $resultado->fetchAll(PDO::FETCH_OBJ); //retorna array de objetos
			$pdo = null;
		}	
		
		catch(PDOException $e){
			echo 'Excepción: ', $e->getMessage();
			return null;
			}
		}  
		
	return $detallePedidoArray;
						
}

function datos_factura($id_factura)
{	
		$pdo = conexion();
		if($pdo){
			try{
			//La búsqueda se realiza en mysql con el comando LIKE
			$sql = "SELECT * FROM factura 
					WHERE id_factura ='$id_factura'";		

			$resultado = $pdo->query($sql);
			$datosFactura = $resultado->fetchObject();
			$pdo = null;
		}	
		
		catch(PDOException $e){
			echo 'Excepción: ', $e->getMessage();
			return null;
			}
		}  
		
	return $datosFactura;
						
}




function datos_facturacion($id_factura)
{	
		$pdo = conexion();
		if($pdo){
			try{
			//La búsqueda se realiza en mysql con el comando LIKE
			$sql = "SELECT * FROM facturacion 
					WHERE id_factura ='$id_factura'";		

			$resultado = $pdo->query($sql);
			$datosFacturacion = $resultado->fetchObject();
			$pdo = null;
		}	
		
		catch(PDOException $e){
			echo 'Excepción: ', $e->getMessage();
			return null;
			}
		}  
		
	return $datosFacturacion;
						
}


function detalle_producto($id)
{	
	//$id = isset($_GET["id"]) ? $_GET["id"] : "";
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

function actualizar_stock($id_prod, $cantidad, $operacion){
			
	$pdo = conexion();
	if($pdo){
		$sql='';
		try{				
	$pdo->beginTransaction();


	if($operacion == 'sumar'){

	$sql = "UPDATE producto
			SET stock = stock + $cantidad
			WHERE id_prod = $id_prod" ;
	}

	
	elseif($operacion == 'restar'){

	$sql = "UPDATE producto
			SET stock = stock - $cantidad
			WHERE id_prod = $id_prod" ;
		}

	$stock_actualizado = $pdo->prepare($sql);

	if(($stock_actualizado->execute())>0){ //si la consulta ha retornado algún resultado ---          
		$pdo->commit();
		$resultado = $stock_actualizado->rowCount(); // --- entonces retorno el número de productos afectados 
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

?>
		