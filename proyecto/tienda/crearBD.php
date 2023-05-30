<!DOCTYPE html>
<html lang="es">
    <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear BD - PDO</title>
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
        echo "Conexión establecida con éxito a MySql por usuario <b>". $usuario."</b><br>";

            //Crea DB si no existe (para no sobreescribir y perder info)
            $sql ="CREATE DATABASE IF NOT EXISTS `tienda` 
            DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;";
            //cotejamiento para mayor compatibilidad español

            if($bd->query($sql)){
                echo "Base de Datos TIENDA creada con éxito<br>";

                //Usar la base datos creada
                $bd->query("USE tienda");

                //Crear tablas producto, pedido, cliente
                
                $sql1="CREATE TABLE IF NOT EXISTS producto (
                    id_prod INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    nombre VARCHAR(25),
                    precio DECIMAL(5),
                    unidad VARCHAR(15),
                    stock DECIMAL(10),
                    descripcion VARCHAR(250)
                    )";
                if($bd->query($sql1)){
                    echo "Tabla PRODUCTO creada con éxito<br>";
                }else echo "Error creando tabla PRODUCTO";

                //Insertar datos iniciales
                $sql2 = "INSERT INTO producto (nombre, precio, unidad, stock, descripcion)
                            VALUES('Naranja de Zumo', 2.50, 'Kilo', 30.0, 'Naranjas de zumo valencianas'), 
                                ('Naranja de Mesa', 2.00, 'Kilo', 20.0, 'Naranjas de mesa'), 
                                ('Fresón de Palos', 5.50, 'Caja', 20.0, 'Caja de 2Kg de fresón de Palos de la Frontera'),
                                ('Plátano de Canarias', 2.19, 'Kilo', 50.5, 'El auténtico plátano de Canarias'),
                                ('Aguacate Nacional', 4.50, 'Kilo', 30.0, 'Aguacate cultivado en la Axarquía (España)'),
                                ('Limón', 1.99, 'Kilo', 20, 'Limón nacional procedente de cultivo sostenible'),
                                ('Tomate Cherry', 1.99, 'Envase', 15.0, 'Envase de 1Kg de tomate cherry'),
                                ('Tomate Pera', 2.25, 'Kilo', 35.5, 'Tomate pera de origen canario, perfecto para salsas, gazpacho, sofritos y todo tipo de preparados.'),
                                ('Tomate RAF', 3.09, 'Kilo', 15.0, 'Tomate RAF de calidad, máximo sabor, perfecto para ensaladas.'),
                                ('Pepino Español', 1.79, 'Kilo', 25.0, 'Pepino español, el de toda la vida, cultivado en Almería.'
                                )"                            
                                ;
                                 


                    if($bd->query($sql2)){
                        echo "Datos insertados con éxito en tabla PRODUCTO<br>";
                     }else echo "Error insertando datos en tabla PRODUCTO<br>";
                     

                $sql3="CREATE TABLE IF NOT EXISTS pedido (
                    num_pedido INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,                    
                    nif VARCHAR(9),
                    precio DECIMAL(7),
                    forma_pago VARCHAR(20),
                    estado_pago VARCHAR(20),
                    estado_pedido VARCHAR(20),
                    texto VARCHAR (500)
                    )";

                    //fecha DATETIME DEFAULT DATE_TIME    PENDIENTE AÑADIR FECHA CADA VEZ QUE SE CREA UN PEDIDO
                                      
                if($bd->query($sql3)){
                    echo "Tabla PEDIDO creada con éxito<br>";
                }else echo "Error creando tabla PEDIDO";

                //Insertar datos iniciales
                $sql4 = "INSERT INTO pedido (nif, precio, forma_pago, estado_pago, estado_pedido, texto)
                        VALUES('53665340S', 355.68, 'bizum', 'pendiente', 'preparando', 'Naranja de Zumo - 1Kg -- Limón - 0,5Kg -- Fresón de Palos - 1 Caja'),
                        ('20357590P', 150.20, 'transferencia bancaria', 'recibido', 'enviado', 'Aguacate Nacional - 5kg -- Plátano de Canarias - 3Kg -- Fresón de Palos - 2 Caja'
                        
                        )";

                    if($bd->query($sql4)){
                    echo "Datos insertados con éxito en tabla PEDIDO<br>";
                    }else echo "Error insertando datos en tabla PEDIDO<br>";

                     
                     
                $sql5 ="CREATE TABLE IF NOT EXISTS cliente (
                    nif INTEGER NOT NULL PRIMARY KEY,
                    nombre VARCHAR(50),
                    apellidos VARCHAR(25),
                    email VARCHAR(25),
                    telefono INTEGER(9),
                    direccion VARCHAR(50),
                    localidad VARCHAR(20),
                    cod_postal INTEGER(5)
                    
                    )";
        //CONSTRAINT FK_autorcliente FOREIGN KEY (id_autor) REFERENCES autor(id)


                    if($bd->query($sql5)){
                        echo "Tabla CLIENTE creada con éxito<br>";
                    }else echo "Error creando tabla CLIENTE";
                        
                    //Insertar datos iniciales
                    $sql6 = "INSERT INTO cliente (nif, nombre, apellidos, email, telefono, direccion, localidad, cod_postal)
                             VALUES('53665340S', 'Daniel', 'Del Valle Gonzalez', 'danimolar@hotmail.com', 657056073, 'Avenida Colmenar Viejo 10', 'San Sebastián de los Reyes', 28701)";

                    if($bd->query($sql6)){
                    echo "Datos insertados con éxito en tabla CLIENTE<br>";
                    }else echo "Error insertando datos en tabla CLIENTE<br>";

                
                }else echo "Error creando BD";   

            
            
    }
    catch(PDOException $e){
        echo 'Excepción al conectar: ', $e->getMessage();
    }
?>
    
</body>
</html>