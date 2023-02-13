function abrirReloj() {
    propiedades = "width= 210, height= 135, top= 300, left= 200, menubar=no, scrollbars=no, resizable=no"; 
    //así será más limpio y accesible, también para modifcarlo en un futuro.
    reloj = window.open("reloj.html", "reloj", propiedades); 
    //abro ventana con URL, nombre asignado, y propiedades
}
function tiempo() {
    //obtengo segundos, minutos y horas con metodos del objeto Date. Retorna en formato "number"
    var fecha = new Date();
    var segundos = fecha.getSeconds();
    var minutos = fecha.getMinutes();
    var horas = fecha.getHours();

    //el metodo "Date.getSeconds" retorna sólo 1 cifra, así que le añado un "0" delante por estética.
    if (segundos < 10) {
        segundos = parseInt(segundos);
        segundos = "0" + segundos;
    }

    //el metodo "Date.getMinutes" retorna sólo 1 cifra, así que le añado un "0" delante por estética.
    if (minutos < 10) {
        minutos = parseInt(minutos);
        minutos = "0" + minutos;
    }

    //el metodo "Date.getHoras" retorna sólo 1 cifra, así que le añado un "0" delante por estética.
    if (horas < 10) {
        horas = parseInt(horas);
        horas = "0" + horas;
    }

    //al tratarse de "div", uso innerHTML para insertar cada parámetro en su contenedor. Si se tratase de inputs, usaria "value"
    document.getElementById("horas").innerHTML = horas;
    document.getElementById("minutos").innerHTML = minutos;
    document.getElementById("segundos").innerHTML = segundos;
}

//cada 1000 ms, osea cada segundo, se ejecutara la funcion "tiempo", que toma la hora del sistema y la presenta en el cuadro.
function iniciar() {
    setInterval(tiempo, 1000);
}
