<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style type="text/css">
        @import "css/tarea7.css";
    </style>
    <title>API DWES</title>

<body>
    <header class="banner">

        <h1>API GESTIÓN DE BIBLIOTECA</h1>
    </header>

    <?php


// Si se ha hecho una peticion que busca informacion de un autor "get_datos_autor" a traves de su "id"...
if (isset($_GET["action"]) && isset($_GET["id"]) && $_GET["action"] == "get_datos_autor") 
{
    //Se realiza la peticion a la api que nos devuelve el JSON con la información de los autores
    $respuesta = file_get_contents('http://localhost/dwest7/api.php?action=get_datos_autor&id=' . $_GET["id"]);
  // echo $respuesta;
   //echo gettype($respuesta);

    // Se decodifica el fichero JSON y se convierte a array
    $respuesta = json_decode($respuesta);
   //echo gettype($respuesta);
   //var_dump( $respuesta);?>

    <div>
        <table id="titular">
            <!-- Mostramos los datos del autor-->
            <?php foreach($respuesta['0'] as $autor):
            //Con strtoupper, transformo en mayúsculas los datos importantes a resaltar
            echo "<tr><td>Id </td><td>" . $autor->id . "</td></tr>" 
            ."<tr><td>Nombre </td><td>" . strtoupper($autor->nombre) . "</td></tr>" 
            ."<tr><td>Apellidos </td><td>" . strtoupper($autor->apellidos). "</td></tr>"
            ."<tr><td>Nacionalidad </td><td>" . strtoupper($autor->nacionalidad). "</td></tr>"
            . "<tr><td></td><td><i>Se han encontrado ". count($respuesta['1'])  ." libros</i></td></tr>";             
             endforeach;?>
            
        </table>
        </div>
                    

    <!-- Mostramos los libros del autor -->
    <div class='descrip'><table>
        <?php echo 
            "<tr>
            <th>Id</th>
            <th>Titulo</th>
            </tr>";
            
            foreach($respuesta['1'] as $libro): 
                    echo"<tr>";
                    echo "<td>" . $libro->id . "</td>" 
                    . "<td>" ?>
        <a href="<?php echo " http://localhost/dwest7/cliente.php?action=get_datos_libro&id=" . $libro->id  ?>">
            <?php echo $libro->titulo. "</td>";?>
        </a>
        <?php echo "</tr>";  endforeach; ?>
    </table>
</div>
    <br />
    <!-- Enlace para volver a la lista de autores -->
    <br><br><p><a href="http://localhost/dwest7/cliente.php?action=get_listado_autores" alt="Lista de autores"> Volver a la lista de autores </a></p><br><br><br><br>
            <?php
    }

else//sino hay id, muestra la lista de autores
{
        // Pedimos al la api que nos devuelva una lista de autores. La respuesta se da en formato JSON
        $lista_autores = file_get_contents('http://localhost/dwest7/api.php?action=get_listado_autores');
            // Convertimos el fichero JSON en array
    //echo $lista_autores;
    //echo gettype($lista_autores);
        $lista_autores = json_decode($lista_autores);
    //echo gettype($lista_autores);
        ?>
            <ul>
                <!-- Mostramos una entrada por cada autor -->
                <h2>Autores Disponibles: <?php echo count($lista_autores);?></h2>

                <?php foreach($lista_autores as $autores): ?>

                <li>
                    <!-- Enlazamos cada nombre de autor con su informacion (primer if) -->
                    <a href="<?php echo "
                        http://localhost/dwest7/cliente.php?action=get_datos_autor&id=" . $autores->id  ?>">
                        <?php echo $autores->id . " - " . $autores->nombre . " " . $autores->apellidos ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php //echo "<tr><td></td><td><i>". call_user_func('conteo', $lista_autores)   ."</i></tr></td>"; //callback a una funcion predefinida por mi, "conteo", que cuenta la longitud del array
            }?>


            <?php
// Si se ha hecho una peticion que busca informacion de un autor "get_datos_libro a traves de su "id"...
if (isset($_GET["action"]) && isset($_GET["id"]) && $_GET["action"] == "get_datos_libro") 
{
    //Se realiza la peticion a la api que nos devuelve el JSON con la información de los autores
    $datos_libro = file_get_contents('http://localhost/dwest7/api.php?action=get_datos_libro&id=' . $_GET["id"]);
  // echo $datos_libro;
   //echo gettype($datos_libro);

    // Se decodifica el fichero JSON y se convierte a array
    $datos_libro = json_decode($datos_libro);
   //echo gettype($datos_libro);


   //var_dump($datos_libro); ?>
            <div class='descrip'><table>
                <?php foreach($datos_libro as $libro): 
        echo    "<tr>
                <th>Id</th>
                <th>Titulo</th>
                <th>Fecha Publicacion</th>
                <th>Autor</th>
                <th>Id Autor</th>
                </tr>";
        echo"<tr>";
        echo "<td>" . $libro->id . "</td>" 
        ."<td>" . $libro->titulo . "</td>" 
        ."<td>" . $libro->f_publicacion . "</td>"
        . "<td>";?>

                <a
                    href="<?php echo 'http://localhost/dwest7/cliente.php?action=get_datos_autor&id='. $libro->id_autor?>">
                    <?php echo $libro->apellidos . ", ". $libro->nombre . "</td>" ?>
                </a>

                <?php echo "<td>" . $libro->id_autor ."</td>";?>

                <?php endforeach;?>
            </table></div>
            <!-- Enlace para volver a la lista de libros -->
            <br><br><p><a href="http://localhost/dwest7/cliente.php?action=get_listado_libros" alt="Lista de libros">Volver a la lista de libros  </a></p><br><br><br><br>
            
            <?php


    }
    else //sino hay id,muestra la lista de libros
{
    // Pedimos al la api que nos devuelva una lista de libros. La respuesta se da en formato JSON
    $lista_libros = file_get_contents('http://localhost/dwest7/api.php?action=get_listado_libros');
        // Convertimos el fichero JSON en array
  //echo $lista_libros;
  //echo gettype($lista_libros);
    $lista_libros = json_decode($lista_libros);
  //echo gettype($lista_libros);
?>
                    <ul>
                        <h2>Libros Disponibles: <?php echo count($lista_libros)?></h2>
                        <!-- Mostramos una entrada por cada libro -->
                        <?php foreach($lista_libros as $libros): ?>
                        <li>
                            <!-- Enlazamos cada nombre de autor con su informacion (primer if) -->
                            <a href="<?php echo "
                                http://localhost/dwest7/cliente.php?action=get_datos_libro&id=" . $libros->id  ;?>">
                                <?php echo $libros->id . " - " . $libros->titulo ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php //echo "<tr><td></td><td><i>". call_user_func('conteo', $lista_libros)   ."</i></td></tr>";
                    }?>
</body>

<footer>

    <p>Proyecto <i><u>API REST </u> </i> --- Daniel del Valle Gonzalez --- DNI 53665340S --- 2023 Intituto FOC</p>

</footer>



</html>