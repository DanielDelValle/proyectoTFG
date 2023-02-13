
//Función que retorna true si n es de tipo number, de tipo integer y no es NaN, además de ser finito.
function esEntero(n) {
    if (typeof n === 'number' &&
        Number.isInteger(n) &&
        !isNaN(n) &&
        Number.isFinite(n)
    )

        return true;
}
//Funció auxiliar que devuelve un mensaje indicando qué tipo de valor introducir
function error() {
    alert('por favor, introduzca sólo números enteros no decimales');
}

//Funciones que calculan si un número es par o impar a partir de su resto dividiendo por 2.
function esPar(n) {
    if (n % 2 == 0)
        return n;
}
function esImpar(n) {
    if (n % 2 != 0)
        return n;
}
//Función que ordena de forma ascendente un array
function orden_asc(conjunto){
    conjunto.sort((a, b) => {
        if(a == b) {
          return 0; 
        }
        if(a < b) {
          return -1;
        }
        return 1;
      }); return conjunto;
}


//Función principal que toma el argumento y lo analiza para ver si es una entrada válida, a través de la función auxiliar esEntero().
function iniciar() {
    var longitud = parseInt(prompt("Introduzca un número entero"));
    if (esEntero(longitud) == true) {  
        var numeros = [];
        while (longitud > 0){            
            var num = parseInt(prompt('Introduzca ' + longitud + ' números de uno en uno'));
            if (esEntero(num) == true){
                numeros.push(num);
                longitud--;
            }else{
                if (num === null) {break;}
                else{error(); continue;}
            }
        }
        numeros = orden_asc(numeros);  //aplico la función que ordena el array para que los arrays pares e impares ya salgan ordenados

        var pares = numeros.filter(esPar);
        var impares = numeros.filter(esImpar);

        //console.log(pares);           compruebo en consola que ambos arrays se crean correctamente
        //console.log(impares);

        //El resultado de la función iniciar se escribe en los párrafos con id "pares" e "impares", además del total de números de cada tipo.

        document.getElementById("pares").innerHTML = "Números pares: " + pares + " . <br><br> Total pares: " + pares.length;
        document.getElementById("impares").innerHTML = "Números impares: " + impares + " . <br><br> Total impares: " + impares.length;

    } else{error(); } //si la entrada por teclado (longitud) no cumple con ser un número entero, se muestra el error.

    return pares, impares;

}

