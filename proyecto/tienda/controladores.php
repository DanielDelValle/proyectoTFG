<?php

// controladores.php
require_once '../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

$twig->addGlobal('session', $_SESSION);


/*, [
    'cache' => 'templates/compilation_cache',
]);
descomentar y poner a continuacion de la linea "$twig=....($loader," para habilitar cache*/ 


function controlador_index()
{
    // Petición al modelo para que retorne la lista de artículos de la BD
    $articulos = lista_articulos();
    
    // Carga la plantilla que se mostrará al usuario con los datos recuperados del modelo
	global $twig;
    // Carga la plantilla que se mostrará al usuario con los datos recuperados 
    // del modelo
    $template = $twig->load('articulos.html');
	echo $template->render(array ( 'articulos' => $articulos));
}

// Controlador específico de artículo
function controlador_detalle($id)
{
    // Petición al modelo para que retorne la lista de artículos de la BD
    $articulo = articulo($id);
    // Carga la plantilla que se mostrará al usuario con los datos recuperados 
    // del modelo
	global $twig;
    // Carga la plantilla que se mostrará al usuario con los datos recuperados 
    // del modelo
    $template = $twig->load('detalles_articulo.html');
	echo $template->render(array ( 'articulo' => $articulo));
}


function controlador_login_clientes()
{
    // Petición al modelo para que retorne la lista de artículos de la BD
    $articulos = lista_articulos();
    // Carga la plantilla que se mostrará al usuario con los datos recuperados del modelo
	global $twig;
    // Carga la plantilla que se mostrará al usuario con los datos recuperados 
    // del modelo

    $template = $twig->load('login_clientes.html');
	echo $template->render(array ( 'articulos' => $articulos));
}

function controlador_login_empleados()
{
    // Petición al modelo para que retorne la lista de artículos de la BD
    $articulos = lista_articulos();
    // Carga la plantilla que se mostrará al usuario con los datos recuperados del modelo
	global $twig;
    // Carga la plantilla que se mostrará al usuario con los datos recuperados 
    // del modelo
    $template = $twig->load('login_empleados.html');
    echo $template->render(array ( 'articulos' => $articulos));

}


function controlador_home_clientes()
{
    // Petición al modelo para que retorne la lista de artículos de la BD
    $articulos = lista_articulos();
    // Carga la plantilla que se mostrará al usuario con los datos recuperados del modelo
	global $twig;
    // Carga la plantilla que se mostrará al usuario con los datos recuperados 
    // del modelo

    $template = $twig->load('home_clientes.html');
	echo $template->render(array ( 'articulos' => $articulos));
}

function controlador_home_empleados()
{
    // Petición al modelo para que retorne la lista de artículos de la BD
    $articulos = lista_articulos();
    // Carga la plantilla que se mostrará al usuario con los datos recuperados del modelo
	global $twig;
    // Carga la plantilla que se mostrará al usuario con los datos recuperados 
    // del modelo

    $template = $twig->load('home_empleados.html');
	echo $template->render(array ( 'articulos' => $articulos));
}




function controlador_contacto()
{
    // Petición al modelo para que retorne la lista de artículos de la BD
    $articulos = lista_articulos();
    // Carga la plantilla que se mostrará al usuario con los datos recuperados del modelo
	global $twig;
    // Carga la plantilla que se mostrará al usuario con los datos recuperados 
    // del modelo

    $template = $twig->load('contacto.html');
	echo $template->render(array ( 'articulos' => $articulos));
}


function controlador_sugerencias()
{
    // Petición al modelo para que retorne la lista de artículos de la BD
    $articulos = lista_articulos();
    // Carga la plantilla que se mostrará al usuario con los datos recuperados del modelo
	global $twig;
    // Carga la plantilla que se mostrará al usuario con los datos recuperados 
    // del modelo

    $template = $twig->load('sugerencias.html');
	echo $template->render(array ( 'articulos' => $articulos));
}

function controlador_registro()
{
	require 'validadores.php';
	
    $formulario = array(
        array('Nombre: ', 'text', 'nombre', ''),
        array('Apellidos: ', 'text', 'apellidos', ''),
        array('Direccion: ', 'text', 'direccion', ''),
        array('Email: ', 'email', 'email', ''),
        array('Contraseña: ', 'password', 'contrasena', ''),
        array('', 'submit', 'registrar', 'Registrarme')
    );
    if(isset($_POST['registrar']))
    {
        if( es_texto($_POST['nombre']) && es_texto($_POST['apellidos']) &&                  
            es_texto($_POST['direccion']) && es_email($_POST['email']) && 
            es_texto($_POST['contrasena']))
        {
            //Envío de datos al modelo y redirección
            registrar($_POST['nombre'], $_POST['apellidos'], 
                      $_POST['direccion'], $_POST['email'], 
                      $_POST['contrasena']);
			
			global $twig;
			$template = $twig->load('registro_correcto.twig');
			echo $template->render();	
        }
		else 
		{
			global $twig;
			$template = $twig->load('registro_no_correcto.twig');
			echo $template->render();	
		}
    }
	else
    {
		global $twig;
		$template = $twig->load('registro.twig');
		echo $template->render(array ('formulario' => $formulario));	
	}

    
}