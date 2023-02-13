function validar() {
	var okUsuario = validarUsuario();
	var okContraseña = validarContraseña();
	if (okUsuario && okContraseña)
		//si ambas comprobaciones son válidas, se retorna true y la info se envía al servidor para, en su caso, ser registrado.
		return true;
	return false;
}
/* Función validar Usuario */
function validarUsuario() {
	var ok = true;
	var msgError = "";
	var usuario = document.getElementById("usuario").value; //Se apunta hacia el valor "usuario" presente en el documento HTML, para referenciarlo mediante una variable
	var divUsuario = document.getElementById("divUsuario"); //El div con id "divUsuario" se asocia a una variable de mismo nombre para referenciarlo
	var error = document.getElementsByClassName("error")[0]; //se apunta al label "error" y a su primer elemento, correspondiente al campo usuario (es un label "oculto")

	divUsuario.style.border = "";
	error.innerHTML = "";
	if (usuario.length == 0) {  // Comprobamos que el campo no esté vacío 
		ok = false;
		msgError = "Este campo no puede estar vacío";
	}
	else
		if (/^\s+$/.test(usuario)) {  // Comprobamos que no esté compuesto sólo por espacios en blanco
			ok = false;
			msgError = "Este campo no puede contener sólo espacios en blanco -> Ejemplo usuario: daniel";
		}
		else
			if (/^\d+$/.test(usuario)) { // Comprobamos que no sea un número (equivalente a isNaN pero con regex)
				ok = false;
				msgError = "Este campo no puede ser un número -> Ejemplo usuario: daniel";
			}
			else
				if (/^[a-z]{3,12}$/.test(usuario)) {   // Si está compuesto por letras minúsculas nada más, y con longitud entre 3 y 12 caracteres
					ok = true;
				}
				else {
					ok = false;
					msgError = "Este campo admite exclusivamente LETRAS MINÚSCULAS, y con longitud de 3 a 12 caracteres -> Ejemplo usuario: daniel";
				}

	if (!ok) {
		divUsuario.style.border = "2px solid red";
		error.style.color = "red";
		error.innerHTML = msgError;		
		return false;
	}else{
	divUsuario.style.border = "2px solid green";  //si se ha pasado la validación, se indicará con una marca verde alrededor del campo, y un mensaje de confirmación
	error.style.color = "green";
	error.innerHTML = "Usuario valido";
	return true;}
}

/* Función validar Contraseña */
function validarContraseña() {
	var ok = true;
	var msgError = "";
	var contraseña = document.getElementById("contraseña").value; //Se apunta hacia el valor "contraseña" presente en el documento HTML, para referenciarlo mediante una variable
	var divContraseña = document.getElementById("divContraseña"); //El div con id "divContraseña" se asocia a una variable de mismo nombre para referenciarlo
	var error2 = document.getElementsByClassName("error")[1]; //se apunta al label "error" y a su segundo elemento, correspondiente al campo contraseña (es un label "oculto")

	divContraseña.style.border = "";
	error2.innerHTML = "";
	if (contraseña.length == 0) {  // Comprobamos que el campo no esté vacío 
		ok = false;
		msgError = "Este campo no puede estar vacío";
	}
	else
		if (/^\s+$/.test(contraseña)) {  // Comprobamos que no esté compuesto sólo por espacios en blanco (\s)
			ok = false;
			msgError = "Este campo no puede contener sólo espacios en blanco  -> Ejemplo contraseña: G-as14dg";
		}
		else
			// Comprobamos formato correcto de Contraseña (Inicia con 1 mayúscula, seguido de 1 punto/coma/guión, y 6 alfanuméricos)
			if (!(/^[A-Z]{1}[\.\,\-]{1}[a-z\d]{6}$/.test(contraseña))) {
				ok = false;
				msgError = "Este campo debe estar formado por: 1x Mayúscula + 1x coma(,) / punto(.) / guión(-) + 6x alfanuméricos -> Ejemplo contraseña: G-as14dg";
			}
			else {  // Si la prueba anterior no retorna false, entonces está cumpliendo la expresión regular y es válida.
				ok = true;
			}
	if (!ok) {
		//si no se han pasado correctamente las validaciones, aparece un bordeado rojo con el mensaje de error correspondiente, y se retorna false.
		divContraseña.style.border = "2px solid red";
		error2.style.color = "red";
		error2.innerHTML = msgError;
		return false;
	}else{
	divContraseña.style.border = "2px solid green";  //si se ha pasado la validación, se indicará con una marca verde alrededor del campo, y un mensaje de confirmación
	error2.style.color = "green";    //he nombrado a este elmento "error 2" para que se distinga del mensaje de error del usuario.
	error2.innerHTML = "Contraseña válida";
	return true;}
}

