//Función constructora que crea objeto de tipo Smartphone con 4 propiedades y 3 métodos
function Smartphone(marca, modelo, color, tamano) {
    this.marca = marca;
    this.modelo = modelo;
    this.color = color;
    this.tamano = tamano;

//Las 3 funciones toman un argumento definido en un input por pantalla del cliente (prompt) y retornan un alert informando de la accion tomada
    this.instalarAplicacion = function (apli) {
        var apli = prompt("Introduzca el nombre de la aplicación a instalar");
        return alert("Aplicación " + apli + " instalada en con éxito en \nsmartphone " + this.marca + " " + this.modelo + " " + this.color);
    }
    this.enviarCorreo = function (mensa) {
        var mensa = prompt("Introduzca el mensaje a enviar");
        return alert("Mensaje " + mensa + " enviado con éxito");
    }
    this.llamar = function (num) {
        var num = prompt("Introduzca el número a llamar");
        return alert("Llamando al número " + num + "\ndesde mi smartphone de tamaño " + this.tamano + " pulgadas")
    }
};  

//Siguiento el patrón prototipo, añado un método que muestra todas las propiedades de un objeto tipo Smartphone, mediante un bucle for
Smartphone.prototype.obtenDatosSmartphone = function(){
    var propiedades = "";
    var ejemplo = new Smartphone();
    for(var prop in ejemplo){
        propiedades += "\n " + prop + "\n ";
    }
    alert(propiedades);
}

//Función asociada al botón "Mostrar Propiedades de un Smartphone"

function mostrar(){
    Smartphone.prototype.obtenDatosSmartphone();
}

//Función asociada al botón "crear Smartphone" , que obtiene los parámetros de la función constructora a partir de inputs por pantalla del usuario
function crear(mar, mod, col, tam) {
    var mar = document.getElementById("marca").value;
    var mod = document.getElementById("modelo").value;
    var col = document.getElementById("color").value;
    var tam = document.getElementById("tamano").value;

    var movil = new Smartphone(mar, mod, col, tam);
    var resultado = "";

    resultado = "<h3>Creado Smartphone con características :</h3><br> "  + "Marca :" + movil.marca + "<br>" + "Modelo :" + movil.modelo + "<br>" + "Color :" + movil.color + "<br>" + "Tamaño :" + movil.tamano + " pulgadas <br>";
    document.getElementById("resultado").innerHTML = resultado;
    return movil;
}
//Función asociada al botón "Instalar Aplicación", que llama al método instalarAplicacion() del objeto creado.
function install(){
    var movil = crear();
    document.getElementById("resultado").innerHTML = movil.instalarAplicacion();
}
//Función asociada al botón "Enviar Email", que llama al método enviarCorreo() del objeto creado.
function mail(){
    var movil = crear();
    document.getElementById("resultado").innerHTML = movil.enviarCorreo();
}
//Función asociada al botón "Llamar", que llama al método llamar() del objeto creado.
function call(){
    var movil = crear();
    document.getElementById("resultado").innerHTML = movil.llamar();
}

//Función informativa que aparece al cargarse la página.
function info() {
    alert("Pulse el botón 'Crear Smartphone' para ejecutar el script");
}

