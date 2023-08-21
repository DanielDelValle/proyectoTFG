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

            //Crear tablas producto, cliente, pedido, albaran
            
            $sql1="CREATE TABLE IF NOT EXISTS producto (
                id_prod INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
                nombre VARCHAR(25),
                precio DECIMAL(5),
                stock DECIMAL(10),
                descripcion VARCHAR(250)
                )";
            if($bd->query($sql1)){
                echo "Tabla PRODUCTO creada con éxito<br>";
            }else echo "Error creando tabla PRODUCTO";


            $sql2 ="CREATE TABLE IF NOT EXISTS cliente (
                nif VARCHAR(9) PRIMARY KEY,
                nombre VARCHAR(50),
                apellidos VARCHAR(25),
                email VARCHAR(25),
                telefono INTEGER(9),
                direccion VARCHAR(50),
                localidad VARCHAR(50),
                cod_postal INTEGER(5),
                provincia VARCHAR(25),
                contrasena VARCHAR(30),
                creado_fecha DATETIME,
                estado_cuenta ENUM ('activo', 'inactivo', 'pendiente', 'bloqueado') DEFAULT 'activo'
               
                )";

              //  total_pedidos INTEGER(3) DEFAULT 0,     PARA LLEVAR CONTEO GASTO CLIENTE (POSIBLES GRADOS MEMBRESIA, RECOMPENSAS, DESCUENTOS)
           //     total_gasto FLOAT(10) DEFAULT 0.0

                if($bd->query($sql2)){
                    echo "Tabla CLIENTE creada con éxito<br>";
                }else echo "Error creando tabla CLIENTE";


            $sql3="CREATE TABLE IF NOT EXISTS pedido (
                id_pedido VARCHAR(30) PRIMARY KEY,        
                nif_cliente VARCHAR(9),
                total_precio DECIMAL(7),
                total_kg DECIMAL (5),
                forma_pago ENUM ('bizum', 'transferencia bancaria'),
                estado_pago ENUM ('pendiente', 'recibido', 'devuelto') DEFAULT 'pendiente',
                estado_pedido ENUM ('procesando', 'enviado', 'recibido') DEFAULT 'procesando',
                creado_fecha DATETIME,
                enviado_fecha DATETIME DEFAULT NULL,
                entregado_fecha DATETIME DEFAULT NULL,
                notas VARCHAR (500)

                )";
                
                                    
            if($bd->query($sql3)){
                echo "Tabla PEDIDO creada con éxito<br>";
            }else echo "Error creando tabla PEDIDO";



            $sql4="CREATE TABLE IF NOT EXISTS PRODUCTOS_PEDIDO (
                id_pedido VARCHAR(30),      
                id_prod INTEGER NOT NULL,
                nombre VARCHAR(25),
                cantidad DECIMAL(5)
                )";
                                    
            if($bd->query($sql4)){
                echo "Tabla PRODUCTOS_PEDIDO creada con éxito<br>";
            }else echo "Error creando tabla PRODUCTOS_PEDIDO";


                    

                    
            //Insertar datos iniciales tabla PRODUCTO
            $sql5 = "INSERT INTO producto (nombre, precio, stock, descripcion)
                        VALUES('Naranja de Zumo', 2.50, 30.0, 'Naranjas de zumo valencianas'), 
                        ('Naranja de Mesa', 2.00, 20.0, 'Naranjas de mesa'), 
                        ('Fresón de Palos', 5.50, 20.0, 'Caja de 2Kg de fresón de Palos de la Frontera'),
                        ('Plátano de Canarias', 2.19, 50.5, 'El auténtico plátano de Canarias'),
                        ('Aguacate Nacional', 4.50, 30.0, 'Aguacate cultivado en la Axarquía (España)'),
                        ('Limón', 1.99, 20, 'Limón nacional procedente de cultivo sostenible'),
                        ('Tomate Cherry', 1.99, 15.0, 'Envase de 1Kg de tomate cherry'),
                        ('Tomate Pera', 2.25, 35.5, 'Tomate pera de origen canario, perfecto para salsas, gazpacho, sofritos y todo tipo de preparados.'),
                        ('Tomate RAF', 3.09, 15.0, 'Tomate RAF de calidad, máximo sabor, perfecto para ensaladas.'),
                        ('Pepino Español', 1.79, 25.0, 'Pepino español, el de toda la vida, cultivado en Almería.'
                        )"                            
                                    ;
                                
            if($bd->query($sql5)){
                echo "Datos insertados con éxito en tabla PRODUCTO<br>";
                }else echo "Error insertando datos en tabla PRODUCTO<br>";
                                
                                    
            
            //Insertar datos iniciales tabla CLIENTE
            $sql6 = "INSERT INTO cliente (nif, nombre, apellidos, email, telefono, direccion, localidad, cod_postal, provincia, contrasena, creado_fecha)
                        VALUES('53665340S', 'Daniel', 'Del Valle Gonzalez', 'danimolar@hotmail.com', 657056073, 'Avenida Colmenar Viejo', 'San Sebastián de los Reyes', 28701, 'Madrid', 'Marico', '2023-08-21 02:55:00' )";

            if($bd->query($sql6)){
            echo "Datos insertados con éxito en tabla CLIENTE<br>";
            }else echo "Error insertando datos en tabla CLIENTE<br>";



            //Insertar datos iniciales tabla PEDIDO (los 2 valores NULL corresponden a los datetime que quiero esten vacios por defecto)
            $sql7= "INSERT INTO pedido (id_pedido, nif_cliente, total_precio, total_kg, forma_pago, creado_fecha, enviado_fecha, entregado_fecha, notas)
                    VALUES('ajkp', '53665340S', 355.68, 3.0, 'bizum', '2023-08-21 02:55:00' ,NULL, NULL, 'Entregar antes del lunes 30/07'                        
            
            )";

            if($bd->query($sql7)){
            echo "Datos insertados con éxito en tabla PEDIDO<br>";
            }else echo "Error insertando datos en tabla PEDIDO<br>";
                    

            //Insertar datos iniciales tabla PRODUCTOS_PEDIDO
            $sql8= "INSERT INTO productos_pedido (id_pedido, id_prod, nombre, cantidad)
                    VALUES('ajkp', 5, 'Aguacate Nacional', 3.0

            )";

            if($bd->query($sql8)){
            echo "Datos insertados con éxito en tabla PRODUCTOS_PEDIDO<br>";
            }else echo "Error insertando datos en tabla PRODUCTOS_PEDIDO<br>";




        // CLAVES EXTERNAS PARA PODER UNIR TABLAS SEGÚN NECESIDAD


            $sqlFK1 = "ALTER TABLE pedido ADD CONSTRAINT FK_cliente_pedido FOREIGN KEY (nif_cliente) REFERENCES cliente (nif) ON DELETE CASCADE ON UPDATE NO ACTION";
            if($bd->query($sqlFK1)){
                echo "FOREIGN KEY FK_cliente_pedido añadido con éxito<br>";
                }else echo "Error insertando FOREIGN KEY FK_cliente_pedido<br>";

            $sqlFK2 = "ALTER TABLE productos_pedido ADD CONSTRAINT FK_productos_pedido FOREIGN KEY (id_pedido) REFERENCES pedido (id_pedido) ON DELETE CASCADE ON UPDATE NO ACTION";
            if($bd->query($sqlFK2)){
                echo "FOREIGN KEY FK_productos_pedido añadido con éxito<br>";
                }else echo "Error insertando FOREIGN KEY FK_productos_pedido<br>";



            }else echo "Error creando BD";   

            $bd = null;
        
}
catch(PDOException $e){
    echo 'Excepción al conectar: ', $e->getMessage();
}
?>
    
</body>
</html>