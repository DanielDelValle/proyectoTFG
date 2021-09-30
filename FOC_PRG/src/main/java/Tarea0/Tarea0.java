package Tarea0;

public class Tarea0 {
    
    public static void main (String[] args) {
        
        int a = 2;
        int b = 3;
//        float resultado;
//        resultado = a + b;
//        System.out.println("resultado = " + resultado);
//        resultado = a - b;
//        System.out.println("resultado = " + resultado);
//        resultado = a * b;
//        System.out.println("resultado = " + resultado);
//        resultado = a / b;
//        System.out.println("resultado = " + resultado); 
        var resultado2 = a+b*b-b-a*b/a;                   //Los paréntesis alteran la prioridad de los cálculos
        System.out.println("resultado2 = " + resultado2);
        var resultado3 = (a+b)*(b-b)-(a*b)/a;
        System.out.println("resultado3 = " + resultado3);
        var resultado4 = (a+b)*b-(b-a)*(b/a);
        System.out.println("resultado4 = " + resultado4);
    }
    
}
