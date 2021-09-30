package primeraClaseJava;

public class primeraClaseJava {                         //Clase     
    
    public static void main (String[] args){            //Método main (imprescindible)
        
        double expre;                                 //Declarando variables - "expre" será decimal, ya que será resultado de dividir valores. Por eso tipo "double"
        int a = 4;                                    //"a", "b" y "c" serán numeros enteros, por eso tipo "int"
        int b = 6;                                    //"variableBooleana" será true o false, por eso tipo "boolean"
        int c = 2;                                    //"nombre" será texto(cadena de caracteres), por eso "String"
        boolean variableBooleana = true;              //Al inicializar variables, se usa el operador de asignación " = " (en el caso de expre, se deja sin inicializar)
        String nombre = "Daniel Del Valle González";        
                                                      //Mostrando variables por pantalla con la expresión "System.out.println"
                                                      //La expresión ".println" es más conveniente ya que añade salto de página, a diferencia de ".print"
        System.out.println("a = " + a);        
        System.out.println("b = " + b);        
        System.out.println("c = " + c);        
        System.out.println("variableBooleana = " + variableBooleana);        
        System.out.println("nombre = " + nombre);
                                    
        expre = a+b/c+1;                              //asignando valor a variable "expre", usando operadores aritméticos "+" y "/" (suma y división)
        System.out.println("expre = " + expre);
        expre = (a+b)/c+1;                            //cambiando valor de "expre" al introducir paréntesis y alterar precedencia de operadores
        System.out.println("expre con paréntesis = " + expre);  
        nombre = "53665340S";                         //reasignando valor de"nombre" con un String formado por letras y números
        System.out.println("nombre modificado a DNI = " + nombre); 
        
    }    
}
