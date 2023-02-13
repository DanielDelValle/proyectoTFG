function validar() {
	var okUsuario = validarUsuario();
	var okContraseña = validarContraseña();
	if (okUsuario && okContraseña)
		return true;
	return false;
}

/* Función validar nombre */
function validarUsuario() {
	var ok = true;
	var msgError = "";
	var usuario = document.getElementById("usuario").value;
	var divUsuario = document.getElementById("divUsuario");
	var error = document.getElementsByClassName("error")[0];

	divUsuario.style.border = "";
	error.innerHTML = "";
	if (usuario.length == 0) {  // Comprobamos que el campo no esté vacío 
		ok = false;
		msgError = "Este campo no puede estar vacío";
	}
	else
		if (/^\s+$/.test(usuario)) {  // Comprobamos que no esté compuesto sólo por espacios en blanco
			ok = false;
			msgError = "Este campo no puede contener sólo espacios en blanco";
		}
		else
			if (/^\d+$/.test(usuario)) { // Comprobamos que no sea un número
				ok = false;
				msgError = "Este campo no puede ser un número";
			}
			else
				if (/^([a-zA-Z]+\s?)+$/.test(usuario)) {   // Si está compuesto por letras nada más, le damos el ok.
					ok = true;
				}
				else {
					ok = false;
					msgError = "Este campo sólo admite texto compuesto exclusivamente por letras";
				}

	if (!ok) {
		divUsuario.style.border = "2px solid red";
		error.innerHTML = msgError;
		return false;
	}
	return true;
}

/* Función validar DNI */
function validarContraseña() {
	var ok = true;
	var msgError = "";
	var contraseña = document.getElementById("contraseña").value;
	var divContraseña = document.getElementById("divContraseña");
	var error = document.getElementsByClassName("error")[1];

	divContraseña.style.border = "";
	error.innerHTML = "";
	if (contraseña.length == 0) {  // Comprobamos que el campo no esté vacío 
		ok = false;
		msgError = "Este campo no puede estar vacío";
	}
	else
		if (/^\s+$/.test(contraseña)) {  // Comprobamos que no esté compuesto sólo por esapcios en blanco
			ok = false;
			msgError = "Este campo no puede contener sólo espacios en blanco";
		}
		else
			if (!(/^\d{8}[A-Z]$/.test(contraseña))) { // Comprobamos que tenga un formato correcto de Contraseña (8 dígitos y una letra)
				ok = false;
				msgError = "Este campo debe tener 8 dígitos y un letra mayúscula. Ejm. 23453287T";
			}
			else {  // Comprobamos que la letra del Contraseña es válida
				var numero = contraseña.substr(0, contraseña.length - 1);
				var letra = contraseña.substr(contraseña.length - 1, 1);
				numero = numero % 23;
				var letrasContraseña = "TRWAGMYFPDXBNJZSQVHLCKET";
				letrasContraseña = letrasContraseña.substr(numero, 1);
				if (letrasContraseña != letra) {
					ok = false;
					msgError = "La letra del contraseña no se corresponde con el Contraseña introducido";
				}
			}
	if (!ok) {
		divContraseña.style.border = "2px solid red";
		error.innerHTML = msgError;
		return false;
	}
	return true;
}

