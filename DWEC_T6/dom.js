function crearBody_modificarEnlace() {

	//Referencia al body
	var cuerpo = document.getElementsByTagName("body")[0];  
	/*Otra manera: 
	var cuerpo = document.body*/
	
	//Variable apunta al botón, para usarla como argumento referencia de la funcion "insertBefore"(nuevo_nodo, ref)
	var referencia = document.getElementsByTagName("button")[0];

	//Crear nodo encabezado y su nodo de texto, y subligarlos
	var encabezado = document.createElement("h1");
	var encabezado_texto = document.createTextNode("Encabezado dinámico");		
	encabezado.appendChild(encabezado_texto);
    //Inserto por encima del botón
	cuerpo.insertBefore(encabezado, referencia);

	//Crear barra horizontal e insertar por encima del botón
	var barra = document.createElement("hr");
	cuerpo.insertBefore(barra, referencia);

	//Crear div, parrafo, y su nodo texto
	var contenedor = document.createElement("div");
	var parrafo = document.createElement("p");
	var texto_parrafo = document.createTextNode("Párrafo Dinámico");

	//Subligar uno al otro de forma sucesiva, siendo el primero (div) insertado por encima del botón
	cuerpo.insertBefore(contenedor, referencia);
	contenedor.appendChild(parrafo);
	parrafo.appendChild(texto_parrafo);
	
	//Apuntar al link
	var link = document.getElementsByTagName("a")[0];
	//Apuntar al nodo hijo del link, osea su texto
	var texto_link = link.childNodes[0];
	//Crear texto nuevo para el link
	var texto_link_nuevo = document.createTextNode("Ir a wikipedia");	
	//Reemplazar el texto viejo del link por el nuevo
	link.replaceChild(texto_link_nuevo, texto_link);
	//Establecer el nuevo atributo del link
	link.setAttribute("href" ,"http://www.wikipedia.org");

	//EXTRA: Crear un footer con mis datos de forma dinámica
	var pie = document.createElement("footer");
	texto_pie = document.createTextNode("Tarea Realizada por Daniel del Valle González DNI 53665340S");
	pie.appendChild(texto_pie);
	cuerpo.appendChild(pie);

}
	