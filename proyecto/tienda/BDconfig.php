<?php

function conexion(){
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
	die(json_encode(array('outcome' => false, 'message' => 'Unable to connect')));
    }

}

/*try { 
    
    

//COMPROBAR Conexión PDO
    $cadenaConexion="mysql:host=localhost;dbname=tienda;charset=utf8";
    $pdo = new PDO($cadenaConexion, 'daniel', 'Daniel88!',     
	array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));  

        
        die(json_encode(array('outcome' => true, 'message' => 'Connection OK')));
    }
    catch(PDOException $ex){
        die(json_encode(array('outcome' => false, 'message' => 'Unable to connect')));
    }


	
catch (PDOException $e) {
    echo $e->getMessage();
    return null;
	die('Conexión a base de datos no conseguida');
    }*/

?>

