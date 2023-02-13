<?php
//gestionLibrosTest.php
require_once( 'gestionLibros.php' );

use PHPUnit\Framework\TestCase;

class gestionLibrosTest extends TestCase
{

    
public function testConexionOK()
{
$o = new gestionLibros();

$resultado = $o->conexion("localhost", "daniel", "Daniel88!", "libros"); 

$this->assertNotNull($resultado);
}



public function testConexionKO()
{
$o = new gestionLibros();

$resultado = $o->conexion("localhost", "dani", "Daniel88!", "libro"); 

$this->assertNull($resultado);
}



public function testConsultarAutores()
{
$esperado = new stdClass();
$esperado->id = 1;
$esperado->nombre = "Isaac";
$esperado->apellidos = "Asimov";
$esperado->nacionalidad = "Rusia";

$o = new gestionLibros();
$pdo = $o->conexion("localhost", "daniel", "Daniel88!", "libros");
$resultado = $o->consultarAutores($pdo, 1);
$this->assertEquals($esperado, $resultado);
}



public function testConsultarLibros()
{
$obj = new stdClass();
$obj->id="4";
$obj->titulo="Un guijarro en el cielo";
$obj->f_publicacion="19/01/1950";
$obj->id_autor="1";
$obj1 = new stdClass();
$obj1->id="5";
$obj1->titulo="Fundación";
$obj1->f_publicacion="01/06/1951";
$obj1->id_autor="1";
$obj2 = new stdClass();
$obj2->id="6";
$obj2->titulo="Yo, robot";
$obj2->f_publicacion="02/12/1950";
$obj2->id_autor="1";
$esperado = array($obj, $obj1, $obj2);

$o = new gestionLibros();
$pdo = $o->conexion("localhost", "daniel", "Daniel88!", "libros"); 
$resultado = $o->consultarLibros($pdo, 1);
$this->assertEquals($esperado, $resultado);
}



public function testConsultarDatosLibro()
{
$esperado = new stdClass();
$esperado->id = 1;
$esperado->titulo = "La Comunidad del Anillo";
$esperado->f_publicacion = "29/07/1954";
$esperado->id_autor = "0";

$o = new gestionLibros();
$pdo = $o->conexion("localhost", "daniel", "Daniel88!", "libros"); 
$resultado = $o->consultarDatosLibro($pdo, 1);
$this->assertEquals($esperado, $resultado);
}



public function testBorrarLibro()
{
$o = new gestionLibros();
$pdo = $o->conexion("localhost", "daniel", "Daniel88!", "libros"); 
//Borrar libro 2
$resultado = $o->borrarLibro($pdo, 3);
$this->assertEquals(true, $resultado);
//Comprobar que el libro 2 ya no está
$resultado = $o->consultarDatosLibro($pdo, 3);
$this->assertNull($resultado);
}



public function testBorrarAutor()
{
$o = new gestionLibros();
$pdo = $o->conexion("localhost", "daniel", "Daniel88!", "libros"); 
//Borrar autor 2
$resultado = $o->borrarAutor($pdo, 1);
$this->assertEquals(true, $resultado);
//Comprobar que el autor 2 ya no está
$resultado = $o->consultarAutores($pdo, 1);
$this->assertNull($resultado);
//Comprobar que el autor 2 ya no tiene libros
$resultado = $o->consultarLibros($pdo, 1);
$this->assertNull($resultado);
}
}

?>