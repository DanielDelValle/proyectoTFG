<?php
/**
* Crea una conexión PDO directa a la BD de la api en mysql

* @return object Retorna $pdo (un objeto de conexión pdo) si todo va bien
* @return null retorna null si hay algún error
*/


function conexion()
{
try {
    
//Conexión PDO

    $cadenaConexion="mysql:host=localhost;dbname=libros;charset=utf8";
    $pdo = new PDO($cadenaConexion, 'daniel', 'Daniel88!'); 
    //PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ) para devolver objetos, pero fetchObject ya lo hace

    
    return $pdo;

    }
catch (PDOException $e) {
    return null;
	die('Conexión a base de datos no conseguida');
    }
}

/**
* Consulta todos lo autores de la BD

* @return array Retorna $lista_autores (array de objetos)
* @return null retorna null si hay algún error
*/

function get_listado_autores(){


$pdo=conexion();

  if ($pdo){
    try{ 
      $sql = ("SELECT * FROM autor");
      $resultado = $pdo->query($sql);
      $lista_autores = $resultado->fetchAll(PDO::FETCH_OBJ);
      
       }

  catch(PDOException $e){
      echo 'Excepción: ', $e->getMessage();
      return null;
    }
  }  

  return $lista_autores;
}

/**
* Consulta info de autor y sus libros en la BD por ID
*
* @param object $pdo La conexión PDO a utilizar
* @param string $id La clave primaria ID del autor a buscar

* @return array Retorna $info_autor (array) si hay coincidencia. Contiene 2 arrays, "autor" y "libros"; éste último a su vez contiene objetos "libro".
* @return null retorna libros = null si el escritor no tiene libros en registro, y retorna null si hay algún error
*/


function get_datos_autor($id){

$pdo=conexion();
if($pdo){
               
      //Lanzo consulta segun busqueda, haciendo JOIN según la clave id_autor de la tabla 'libros', que corresponde con id de la tabla 'autor'
    try{ 
      
      $sql = "SELECT * FROM autor where id='$id'";
      $lectura = $pdo->query($sql);
      $autor = $lectura->fetchAll(PDO::FETCH_OBJ);
      //$info_autor->datos = $lectura->fetch(PDO::FETCH_ASSOC);
          
      
      $sql2 = "SELECT * FROM libro WHERE id_autor='$id'";
      $lectura2 = $pdo->query($sql2);
      //$info_autor->libros = $lectura->fetchAll();
      
      if ($lectura2->rowCount() > 0){
      //Procesar result set como objeto
      foreach($lectura2->fetchAll(PDO::FETCH_OBJ) as $fila)
        {
      //array_push($info_autor, $fila);
      $libros[]= $fila;
        }
      }
      else $libros = null;
      
      }
    catch(PDOException $e){
        echo 'Excepción: ', $e->getMessage();
        return null;
    }
    $info_autor= array($autor, $libros);
  //  $info_autor = call_user_func_array('array_merge',$info_autor); //reduzco en 1 la dimension del array
    return $info_autor;                    
    
  }  else echo 'Error al conectar con la BD';   
}  

/**
* Consulta todos los libro de la BD

* @return array Retorna $lista_libros (un array formado por objetos "libro") si hay coincidencia, que contiene todos los libros escritos por el autor buscado
* @return null retorna null si hay algún error
*/


function get_listado_libros(){

  $pdo=conexion();
  if($pdo){
    try{
      $sql = "SELECT * FROM libro";
      $lectura = $pdo->query($sql);
      $lista_libros= $lectura->fetchAll(PDO::FETCH_OBJ);
    }
        catch(PDOException $e){
        echo 'Excepción: ', $e->getMessage();
        return null;
    }

  }
  return $lista_libros;
}


/**
* Consulta libros en la BD
*
* @param object $pdo La conexión PDO a utilizar
* @param string $id La clave primaria ID del libro a buscar

* @return object Retorna $datos_libro (un objeto) si hay coincidencia
* @return null retorna null si hay algún error
*/

function get_datos_libro($id){

  $pdo=conexion();
  if($pdo){
    try{
      $sql = "SELECT l.id, l.titulo, l.f_publicacion, a.nombre, a.apellidos, l.id_autor
      FROM libro l JOIN autor a ON l.id_autor=a.id
      WHERE l.id = '$id'";
      $lectura = $pdo->query($sql);
      $datos_libro= $lectura->fetchAll(PDO::FETCH_OBJ);
    }
        catch(PDOException $e){
        echo 'Excepción: ', $e->getMessage();
        return null; 
    }

  }return $datos_libro;

}

//Array de los únicos dominios posibles (para delimitar el acceso del usuario a otras dependencias de la api)
$posibles_URL = array("get_listado_autores", "get_datos_autor", "get_listado_libros", "get_datos_libro");

$respuesta = "Ha ocurrido un error";

//Si el campo "action" de la cabecera está seteado, y su valor se encuentra dentro del array "posibles_URL" (FILTRO DE SALIDA):
if (isset($_GET["action"]) && in_array($_GET["action"], $posibles_URL))
{
  switch ($_GET["action"])
    {
      case "get_listado_autores":
        $respuesta = get_listado_autores();
        break;
      case "get_datos_autor":
        if (isset($_GET["id"]))
            $respuesta = get_datos_autor($_GET["id"]);
        else
            $respuesta = "Argumento no encontrado";
        break;

      case "get_listado_libros":
        $respuesta = get_listado_libros();
        break;
      case "get_datos_libro":
        if (isset($_GET["id"]))
            $respuesta = get_datos_libro($_GET["id"]);
        else
            $respuesta = "Argumento no encontrado";
        break;

    }
}

//devolvemos los datos serializados en JSON (MUY IMPORTANTE)
exit(json_encode($respuesta));


?>