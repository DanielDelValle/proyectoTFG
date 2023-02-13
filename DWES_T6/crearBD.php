<!DOCTYPE html>
<html lang="es">
    <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear BD libros- PDO</title>
</head>
<body>

<?php
    try{
    $cadenaConexion ="mysql:host=localhost";  //no indico ninguna base de datos, porque se trata de crearla ("mysql:dbname=BD_daniel; host=localhost" por ejemplo)
    $usuario="daniel";
    $clave="Daniel88!";
    $bd = new PDO($cadenaConexion,$usuario,$clave,
            array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
        );
        if ($bd)echo "Conexión establecida con éxito a MySql por usuario <b>". $usuario."</b><br>";

            //Crea DB si no existe (para no sobreescribir y perder info)
            $sql ="CREATE DATABASE IF NOT EXISTS `libros` 
            DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;";
            //cotejamiento para mayor compatibilidad español

            if($bd->query($sql)){
                echo "BD creada con éxito<br>";

                //Usar la base datos creada
                $bd->query("USE libros");

                //Crear tablas autor y libro
                $sql="CREATE TABLE IF NOT EXISTS autor (
                    id INT(3) PRIMARY KEY,
                    nombre VARCHAR(15),
                    apellidos VARCHAR(25),
                    nacionalidad VARCHAR(10)
                    )";
                if($bd->query($sql)){
                    echo "Tabla AUTOR creada con éxito<br>";

                    //Insertar datos iniciales
                    $sql1 = "INSERT INTO autor (id, nombre, apellidos, nacionalidad)
                             VALUES('0', 'J.R.R.', 'Tolkien', 'Inglaterra'), 
                                    ('1','Isaac', 'Asimov', 'Rusia'),
                                    ('2','Fiodor', 'Dostoievski', 'Rusia')" ;


                    if($bd->query($sql1)){
                        echo "Datos insertados con éxito en tabla AUTOR<br>";
                     }else echo "Error insertando datos en tabla AUTOR<br>";

                   $sql2 ="CREATE TABLE IF NOT EXISTS libro (
                    id INT(3) PRIMARY KEY,
                    titulo VARCHAR(50),
                    f_publicacion VARCHAR(10),
                    id_autor INT(3),
                    FOREIGN KEY (id_autor) REFERENCES autor(id) ON DELETE CASCADE  
                    )";                                             //ON DELETE CASCADE permite que si elimino algún autor, también borre libros asociados a su id_autor
                    if($bd->query($sql2)){
                        echo "Tabla LIBRO creada con éxito<br>";
                    //Insertar datos iniciales
                    $sql3 = "INSERT INTO libro (id, titulo, f_publicacion, id_autor)
                             VALUES('0', 'El Hobbit', '21/09/1937', '0'),
                                    ('1', 'La Comunidad del Anillo', '29/07/1954', '0'), 
                                    ('2', 'Las Dos Torres', '11/11/1954', '0'), 
                                    ('3', 'El Retorno del Rey', '20/10/1955', '0'),
                                    ('4', 'Un guijarro en el cielo', '19/01/1950', '1'), 
                                    ('5', 'Fundación', '01/06/1951', '1'), 
                                    ('6', 'Yo, robot', '02/12/1950', '1'),
                                    ('7', 'Crimen y Castigo', '06/07/1867', '2'),
                                    ('8', 'El Jugador', '12/12/1866', '2')";

                    if($bd->query($sql3)){
                    echo "Datos insertados con éxito en tabla LIBRO<br>";
                    }else echo "Error insertando datos en tabla LIBRO<br>";
                
                    }

            }else echo "Error creando tablas";



            
            }else echo "Error creando BD";
    }
    catch(PDOException $e){
        echo 'Excepción al conectar: ', $e->getMessage();
    }
?>
    
</body>
</html>