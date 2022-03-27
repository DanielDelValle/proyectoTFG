package Clases;

public class MetodosEjemplo {

    int a;  //son atributos de la clase MetodosEjemplo. así,podrán usarse como argumentos en metodos.
    int b;
    int c;
    int d;

    public MetodosEjemplo() { //Es el CONSTRUCTOR vacio, el cual por default se crea junto a la clase (sin args predet.)
//        //Debe llamarse igual que la clase, pero los() y su mayuscula lo distinguen de un metodo.
//        //Ademas, no retorna nada. solo puedes llamar al constructor con new (no sirve MetodosEjemplo.MetodosEjemplo)
//          a = 7;                //valores por defecto de los atributos de las instancias que se creen de la clase Met.Ejem.
//          b = 13;

        System.out.println("Ejecutando constructor vacio");
    }

    public MetodosEjemplo(int arg1, int arg2, int arg3, int arg4) { //una de las funciones del constructor, es asignar valores a los atributos
        a = arg1;
        b = arg2;
        c = arg3;                          //en cuanto hay un Constructor con Args, debemos crear de serie el vacio,
        d = arg4;                              //sino no podriamos crear instancias SIN espeficiar cada argumento.

        System.out.println("Ejecutando constructor con argumentos");//aun asi, debe existir el vacio.
    }
//    public int sumar(int a, int b)   en caso de que el constructor estuviese vacio, se requieren los args.

    public int sumar() {  //definimos metodo. Podria darse sin argumentos, ya que la clase tiene atributos a y b.
        // No se puede usar "var", ya que es ambiguo. Hay que concretar.
        int resultado = a + b;
        return resultado;
    }

    public int multiplicar() {  //definimos metodo. Podria darse sin argumentos, ya que la clase tiene atributos a y b.
        // No se puede usar "var", ya que es ambiguo. Hay que concretar.
        int resultado = c * d;
        return resultado;
    }
}
