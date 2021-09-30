package Clases;

public class Metodos { //Los metodos son, básicamente, funciones o automatismos dentro de las clases.


    public int sumar(int a, int b) { //primer int indica el tipo de dato que retorna (a +b es int). Los otros, son argumentos.
        //La "firma" del método sería: "int sumar int int". Plantilla dentro de plantilla (sumar dentro de clase metodos)
        return a + b;
    }
    //Llamar a un metodo:
    Metodos operacion = new Metodos();  //creamos instancia [REALMENTE,LOS () IMPLICAN LLAMAR AL CONSTRUCTOR (VACIO)]
    //llamamos al metodo metiendo los argumentos, pero guardandolo en una variable para no perder el valor return
    int resultado = operacion.sumar(10, 23);
}