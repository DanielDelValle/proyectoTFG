<?php
//gestionLibrosTest.php
require_once 'validadores.php' ;
require 'modelo.php';

use PHPUnit\Framework\TestCase;
//  comando para ejecutar test: 
//      php phpunit-9.5.27.phar test1.php     [REFERENCIA: TAREA 6 DWES - Gestion Libros]
class test1 extends TestCase
{

public function testEstextoOK()
{
$esperado = true;

$resultado = es_texto('cami seta');
$this->assertEquals($esperado, $resultado);
}//OK


public function testEstextoKO()
{
$esperado = false;

$resultado = es_texto('!?-');
$this->assertEquals($esperado, $resultado);
}//OK

public function testEscifraOK()
{
$esperado = true;

$resultado = es_cifra(123456);
$this->assertEquals($esperado, $resultado);
}//OK


public function testEscifraKO()
{
$esperado = false;

$resultado = es_cifra('camiseta óñ Á 5');
$this->assertEquals($esperado, $resultado);
}//OK


public function testValidNIFOK()
{
$esperado = true;

$resultado = val_nif('53665340S');
$this->assertEquals($esperado, $resultado);
}//OK


public function testValidNIFKO()
{
$esperado = false;

$resultado = val_nif('53665340F');
$this->assertEquals($esperado, $resultado);
}//OK

public function testValidpostalOK()
{
$esperado = true;

$resultado = valid_postal(28769);
$this->assertEquals($esperado, $resultado);
}//OK


public function testValidpostalKO()
{
$esperado = false;

$resultado = valid_postal(805266);
$this->assertEquals($esperado, $resultado);
}//OK

public function testValidtelOK()
{
$esperado = true;

$resultado = valid_tel(725856963);
$this->assertEquals($esperado, $resultado);
}//OK


public function testValidtelKO()
{
$esperado = false;

$resultado = valid_tel(5554075635);
$this->assertEquals($esperado, $resultado);
}//OK


public function testValiddireccionOK()
{
$esperado = true;

$resultado = valid_direccion('camiseta óñ Á arroz 245');
$this->assertEquals($esperado, $resultado);
}//OK


public function testValiddireccionKO()
{
$esperado = false;

$resultado = valid_direccion(']!¡we-+');
$this->assertEquals($esperado, $resultado);
}//OK


public function testemailexisteOK()
{
$lista_emails = array_column(lista_users(), 'email');
$esperado = true;

$resultado = email_existe('daniel@frutasdelvalle.com', $lista_emails);
$this->assertEquals($esperado, $resultado);
}//OK


public function testValiddiEmailexisteKO()
{
    $lista_emails = array_column(lista_users(), 'email');
$esperado = false;

$resultado = email_existe('perico_elde_los_palotes@gmail.com', $lista_emails);
$this->assertEquals($esperado, $resultado);
}//OK

public function testValidemailOK()
{
$esperado = true;

$resultado = valid_email('daniel@frutasdelvalle.com');
$this->assertEquals($esperado, $resultado);
}//OK


public function testValidemailKO()
{
$esperado = false;

$resultado = valid_email('manolo_gmail.com');
$this->assertEquals($esperado, $resultado);
}//OK

public function testValidemailempleadoOK()
{
$esperado = true;

$resultado = valid_email_empleado('daniel@frutasdelvalle.com');
$this->assertEquals($esperado, $resultado);
}//OK


public function testValidemailempleadoKO()
{
$esperado = false;

$resultado = valid_email_empleado('manolo@gmail.com');
$this->assertEquals($esperado, $resultado);
}//OK


public function testValidcontrasenaOK()
{
$esperado = true;

$resultado = valid_contrasena('ContraseÑa_Segura123!');
$this->assertEquals($esperado, $resultado);
}//OK


public function testValidcontrasena()
{
$esperado = false;

$resultado = valid_contrasena('clave_no_segura');
$this->assertEquals($esperado, $resultado);
}//OK


}

?>