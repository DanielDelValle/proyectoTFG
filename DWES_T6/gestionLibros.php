<?php
$id='';
$nombre="";
$apellidos="";
$nacionalidad = "";
$titulo="";
$f_publicacion="";
$id_autor="";
$mensaje="";

//gestionLibros.php

/**
* Crea una conexión PDO
*
* @param string $servidor dirección a la que conectará (localhost o IP).
* @param string $usuario Usuario que accederá a la BD
* @param string $contraseña clave del usuario de acceso a la BD
* @param string $bd base de datos a la que accederá

* @return object Retorna $pdo (un objeto de conexión pdo) si todo va bien
* @return null retorna null si hay algún error
*/

class gestionLibros {

public function conexion($servidor, $usuario, $contraseña, $bd)
{
try {
    
//Conexión PDO
    $cadenaConexion = "mysql:dbname=$bd;host=$servidor";
    $pdo = new PDO($cadenaConexion, $usuario, $contraseña,
    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")  //PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ) para devolver objetos, pero fetchObject ya lo hace
);
    return $pdo;

    }
catch (PDOException $e) {
    return null;
	die('Conexión a base de datos no conseguida');
    }
}
        
/**
* Consulta autores en la BD
*
* @param object $pdo La conexión PDO a utilizar
* @param string $id La clave primaria ID del autor a buscar

* @return object Retorna $autor (un objeto) si hay coincidencia
* @return null retorna null si hay algún error
*/

public function consultarAutores($pdo, $id){
    if($pdo){
        $autor= new stdClass();

            try{ $sql= "SELECT * FROM autor WHERE id = '$id'";
                $resultado = $pdo->query($sql);
                while($fila= $resultado->fetchObject()){
                    $autor->id=$fila->id;              
                    $autor->nombre=$fila->nombre;
                    $autor->apellidos=$fila->apellidos;
                    $autor->nacionalidad=$fila->nacionalidad;

                    echo"<tr>";
                    echo "<td>" . $autor->id . "</td>" 
                    . "<td>" . $autor->nombre . "</td>" 
                    . "<td>" . $autor->apellidos . "</td>" 
                    . "<td>" . $autor->nacionalidad . "</td>" ;
                    echo "</tr>";           
    
                }
                echo $mensaje = "Se han encontrado <b>" . $resultado->rowCount() . "</b> autor(s) <br><br>";
                if ($resultado->rowCount()==0)  return null; else return $autor;

                
 
                 }
    
            catch(PDOException $e){
                echo 'Excepción: ', $e->getMessage();
                return null;
            }
           
        }else{    return null; die("error en la conexión a la BD"); //en caso de no haber conexión, directamente se para el proceso
            
            
        
    
        }  return $autor;
        
    }
/**
* Consulta libros por ID de Autor en la BD
*
* @param object $pdo La conexión PDO a utilizar
* @param string $id La clave primaria ID del autor cuyos libros queremos visualizar

* @return array Retorna $resultadoArray (un array formado por objetos "libro") si hay coincidencia, que contiene todos los libros escritos por el autor buscado
* @return null retorna null si hay algún error
*/

public function consultarLibros($pdo, $id){

    if($pdo){
        $libro = new stdClass();
        $resultadoArray=array($libro);
                
                //Lanzo consulta segun busqueda, haciendo JOIN según la clave id_autor de la tabla 'libros', que corresponde con id de la tabla 'autor'
            try{ $sql= "SELECT l.id, l.titulo, l.f_publicacion, l.id_autor
                        FROM libro l JOIN autor a ON l.id_autor=a.id
                        WHERE id_autor = '$id'";
                $resultado = $pdo->query($sql);
                $resultadoArray = $resultado->fetchAll(PDO::FETCH_OBJ);  //retorna array de objetos
                
               
                foreach($resultadoArray as $i => $libro) {   
                    //echo var_dump($i) . '<br><br>';
                    //echo var_dump($libro) . '<br><br>';
                    
                    echo"<tr>";
                    echo "<td>" . $libro->id . "</td>" 
                    . "<td>" . $libro->titulo . "</td>" 
                    . "<td>" . $libro->f_publicacion . "</td>" 
                    . "<td>" . $libro->id_autor . "</td>" ;
                    echo "</tr>";  
                }//echo var_dump($resultadoArray) . '<br><br>';
                             
            
            
            echo $mensaje = "Se han encontrado <b>" . $resultado->rowCount() . "</b> libro(s) <br><br>"; 
            if ($resultado->rowCount()==0)  return null; else return $resultadoArray;

            
        }
            catch(PDOException $e){
                echo 'Excepción: ', $e->getMessage();
                return null;
                     if(isset($_POST['consultar'])){
                    
                    if ((is_numeric($_POST['id'])) ){
                        $id=$_POST['id'];
                        
                        //Lanzo consulta segun busqueda, haciendo JOIN según la clave id_autor de la tabla 'libros', que corresponde con id de la tabla 'autor'
                    try{ $sql= "SELECT l.id, l.titulo, l.f_publicacion, l.id_autor
                                FROM libro l JOIN autor a ON l.id_autor=a.id
                                WHERE id_autor = $id";
                        $resultado = $pdo->query($sql);
                        $resultadoArray = $resultado->fetchAll(PDO::FETCH_OBJ);  //retorna array de objetos
                        
                       
                        foreach($resultadoArray as $i => $libro) {   
                            //echo var_dump($i) . '<br><br>';
                            //echo var_dump($libro) . '<br><br>';
                            
        
                            echo"<tr>";
                            echo "<td>" . $libro->id . "</td>" 
                            . "<td>" . $libro->titulo . "</td>" 
                            . "<td>" . $libro->f_publicacion . "</td>" 
                            . "<td>" . $libro->id_autor . "</td>" ;
                            echo "</tr>";  
                        }echo var_dump($resultadoArray) . '<br><br>';
                                     
                    
                    
                    echo $mensaje = "Se han encontrado <b>" . $resultado->rowCount() . "</b> libro(s) <br><br>"; 
                    if ($resultado->rowCount()==0)  return null;
                   
                    
                }
                    catch(PDOException $e){
                        echo 'Excepción: ', $e->getMessage();
                        return null;
                    }
                   
                }  
                else{ 
                    //Si no hay ningún ID_AUTOR seleccionado pero se pulsa "consultar", se muestran por pantalla todos los libros asociados a autor.
                        $id ='';
                        $resultadoArray=array($libro);
            
                     try{  $sql= "SELECT l.id, l.titulo, l.f_publicacion, l.id_autor
                        FROM libro l JOIN autor a ON l.id_autor=a.id";
                                
                        $resultado = $pdo->query($sql);
                        while($fila= $resultado->fetchObject()){
                            echo"<tr>";
                            echo "<td>" . $fila->id . "</td>" 
                            . "<td>" . $fila->titulo . "</td>" 
                            . "<td>" . $fila->f_publicacion . "</td>" 
                            . "<td>" . $fila->id_autor . "</td>" ;
                            echo "</tr>";           
                    
                                }echo $mensaje = "Ningún ID especificado - Se han encontrado " . $resultado->rowCount() . " libro(s) <br><br>";
                    
                    
                                }
                    
                    catch(PDOException $e){
                        echo 'Excepción: ', $e->getMessage();
            
                        return null;
                    }
                    
                    } 
            
                }  else{    
                        return null; die("error en la conexión a la BD"); //en caso de no haber conexión, directamente se para el proceso
                
            }
   
    

    }
    
    }  else{ return null; die("error en la conexión a la BD"); //en caso de no haber conexión, directamente se para el proceso
}
    
}   

/**
* Consulta libros en la BD
*
* @param object $pdo La conexión PDO a utilizar
* @param string $id La clave primaria ID del libro a buscar

* @return object Retorna $libro (un objeto) si hay coincidencia
* @return null retorna null si hay algún error
*/

    
public function consultarDatosLibro($pdo, $id){
   
    if($pdo){
        $libro= new stdClass();            

            try{ $sql= "SELECT * FROM libro WHERE id = $id";
                $resultado = $pdo->query($sql);
                while($fila= $resultado->fetchObject()){
                    $libro->id=$fila->id;                //str_replace('"', "'", $libro->id); para reemplazar "" por ''
                    $libro->titulo=$fila->titulo;
                    $libro->f_publicacion=$fila->f_publicacion;
                    $libro->id_autor=$fila->id_autor;

                    echo"<tr>";
                    echo "<td>" . $libro->id . "</td>" 
                    . "<td>" . $libro->titulo . "</td>" 
                    . "<td>" . $libro->f_publicacion . "</td>" 
                    . "<td>" . $libro->id_autor . "</td>" ;
                    echo "</tr>";           
    
                }echo $mensaje = "Se han encontrado <b>" . $resultado->rowCount() . "</b> libro(s) <br><br>";
                if ($resultado->rowCount()==0) return null; else return $libro; 
                //echo var_dump($libro);
                
 
                 }
    
            catch(PDOException $e){
                echo 'Excepción: ', $e->getMessage();
                return null;

            }
        }
}


/**
* Borrar autor por ID en la BD
*
* @param object $pdo La conexión PDO a utilizar
* @param string $id La clave primaria ID del autor a borrar

* @return boolean Retorna true si la transacción se lleva a cabo y se elimina alguna línea
* @return boolean retorna false si hay algún error
*/

public function borrarAutor($pdo, $id){
    $borrado=false;

    if($pdo){

        try{        
            $pdo->beginTransaction();
            $sql = "DELETE FROM autor WHERE id= $id";
            $borrar= $pdo->prepare($sql);
            
            
            if(($borrar->execute())>0){ //si la consulta ha retornado algún resultado ---
                $borrado=true;             
                $pdo->commit();
                echo $mensaje = "Se han borrado <b>". $borrar->rowCount() . "</b> autor(es) </b><br><br>"; // --- entonces retorno el número de autores afectados (borrados)
               
                }

            else {$borrado=false;
                echo "Ninguna fila borrada - introduce un ID válido";
                $pdo->rollback();
               
                }
        }
        catch(PDOException $e){
            echo 'Excepción: ', $e->getMessage();

            return null;
        }

        return $borrado;
    }else{  return null; die("error en la conexión a la BD");} //en caso de no haber conexión, directamente se para el proceso

    

}

/**
* Borrar libro por ID en la BD
*
* @param object $pdo La conexión PDO a utilizar
* @param string $id La clave primaria ID del libro a borrar

* @return boolean Retorna true si la transacción se lleva a cabo y se elimina alguna línea
* @return boolean retorna false si hay algún error
*/

public function borrarLibro($pdo, $id){
    $borrado=false;

    if($pdo){

        try{        
            $pdo->beginTransaction();
            $sql = "DELETE FROM libro WHERE id= $id";
            $borrar= $pdo->prepare($sql);
            
            
            if(($borrar->execute())>0){ //si la consulta ha retornado algún resultado ---
                $borrado=true;             
                $pdo->commit();
                echo $mensaje = "Se han borrado <b>". $borrar->rowCount() . "</b> libro(s) </b><br><br>"; // --- entonces retorno el número de autores afectados (borrados)
               
                }

            else {$borrado=false;
                echo "Ninguna fila borrada - introduce un ID válido";
                $pdo->rollback();
               
                }
        }
        catch(PDOException $e){
            echo 'Excepción: ', $e->getMessage();

            return null;
        }

        return $borrado;
    }else{  return null; die("error en la conexión a la BD");} //en caso de no haber conexión, directamente se para el proceso

}

/**
* Consulta libros por ID de Autor en la BD
*
* @param object $pdo La conexión PDO a utilizar
* @param string $id La clave primaria ID del autor cuyos libros queremos visualizar
* @return null retorna null si hay algún error -- No retorna nada más, ya que se trata de una función auxiliar de visualización
*/
    public function mostrarTodo($pdo, $id){
        if(isset($_POST['consultar'])){
            
            if ((is_numeric($_POST['id'])) ){
                $id=$_POST['id'];

        if($pdo){
            $resumen = new stdClass();
            $resultadoArray=array($resumen);
                    
                    
                try{ $sql= "SELECT a.id as idautor, a.nombre, a.apellidos, a.nacionalidad, l.id as idlibro, l.titulo, l.f_publicacion, l.id_autor
                    FROM libro l JOIN autor a ON l.id_autor=a.id
                    WHERE id_autor = '$id'";
            $resultado = $pdo->query($sql);
            $resultadoArray = $resultado->fetchAll(PDO::FETCH_OBJ);  //retorna array de objetos
            
        
            foreach($resultadoArray as $i => $resumen) {   
                //echo var_dump($i) . '<br><br>';
                //echo var_dump($resumen) . '<br><br>';
                
                echo"<tr>";
                echo "<td>" . $resumen->idautor . "</td>" 
                . "<td>" . $resumen->nombre . "</td>" 
                . "<td>" . $resumen->apellidos . "</td>" 
                . "<td>" . $resumen->nacionalidad . "</td>" 
                . "<td>" . $resumen->idlibro . "</td>" 
                . "<td>" . $resumen->titulo . "</td>" 
                . "<td>" . $resumen->f_publicacion . "</td>";
                echo "</tr>";  
              }//echo var_dump($resultadoArray) . '<br><br>';
                                 
                
                
                echo $mensaje = "Se han encontrado <b>" . $resultado->rowCount() . "</b> libro(s) <br><br>"; 
                if ($resultado->rowCount()==0)  return null; else return $resultadoArray;
    
                
            }
                catch(PDOException $e){
                    echo 'Excepción: ', $e->getMessage();
                    return null;
                         if(isset($_POST['consultar'])){
                        
                        if ((is_numeric($_POST['id'])) ){
                            $id=$_POST['id'];
                            
                            //Lanzo consulta segun busqueda, haciendo JOIN según la clave id_autor de la tabla 'libros', que corresponde con id de la tabla 'autor'
                        try{ $sql= "SELECT l.id, l.titulo, l.f_publicacion, l.id_autor
                                    FROM libro l JOIN autor a ON l.id_autor=a.id
                                    WHERE id_autor = $id";
                            $resultado = $pdo->query($sql);
                            $resultadoArray = $resultado->fetchAll(PDO::FETCH_OBJ);  //retorna array de objetos
                            
                           
                            foreach($resultadoArray as $i => $libro) {   
                                //echo var_dump($i) . '<br><br>';
                                //echo var_dump($libro) . '<br><br>';
                                
            
                                echo"<tr>";
                                echo "<td>" . $libro->id . "</td>" 
                                . "<td>" . $libro->titulo . "</td>" 
                                . "<td>" . $libro->f_publicacion . "</td>" 
                                . "<td>" . $libro->id_autor . "</td>" ;
                                echo "</tr>";  
                            }
                                         
                        
                        
                        echo $mensaje = "Se han encontrado <b>" . $resultado->rowCount() . "</b> libro(s) <br><br>"; 
                        if ($resultado->rowCount()==0)  return null;
                       
                        
                    }
                        catch(PDOException $e){
                            echo 'Excepción: ', $e->getMessage();
                            return null;
                        }
                    }
                    }  
                    }
                    }  else{    
                            return null; die("error en la conexión a la BD"); //en caso de no haber conexión, directamente se para el proceso
                    
                }
       
        
    
        }else echo "Introduzca una id de valor numérico<br><br>";
        
        }  else{ return null; die("error en la conexión a la BD"); //en caso de no haber conexión, directamente se para el proceso
    }
           

}
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style type="text/css"> @import "css/tarea6.css";</style>
    <title>Consulta JOIN</title>
</head>
<body>
<?php
require_once( 'gestionLibros.php' );
$consulta = new gestionLibros();
$pdo = $consulta->conexion('localhost', 'daniel', 'Daniel88!', 'libros');




?>

<h1>Consultar Libros por ID de Autor</h1>

<form method='post' action='?'>
        
            
        <fieldset>
            <label for="id">Id</label><br>
            <input type="text" name="id" id="id" value="<?php echo isset($_POST['id']) ? $_POST['id'] : '' ?>"><br /> <!--si el campo está relleno, se mantiene el valor; sino, vale '' -->
            </fieldset>
        <input type="submit" name="consultar" value="Consultar">
    </form>

<br><a href="consultarAutores.php">Buscar Autores</a> <br>  
<a href="consultarDatosLibro.php">Buscar Libros por Id</a><br> 
<a href="consultarLibros.php">Buscar Libros escritos por Autor</a><br><br>  

<div class="descrip" >

<table>

<tr>    
    <th>Id</th>
    <th>Nombre</th>
    <th>Apellidos</th>
    <th>Nacionalidad</th>
    <th>Id</th>
    <th>Titulo</th>
    <th>Fecha Publicacion</th>
    </tr><br> 


    <?php

    try{
        $consulta->mostrarTodo($pdo,$id);

    }
    catch(PDOException $e){
        echo 'Excepción: ', $e->getMessage();
        return null;
    }{

    }
    ?>
</table>
    
</div>
</body>
</html>



