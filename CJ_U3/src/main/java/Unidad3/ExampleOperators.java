/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package Unidad3;

/**
 *
 * @author Gman
 */
public class ExampleOperators {
    public static void main(String[] args){
        int a = 20, b = 10;

    //CASO 1: operador de pre-incremento
    // a = a+1 y entonces c = a;
        int c = ++a;
        System.out.println("Valor de c (++a) = " + c);

    //CASO 2: operador de post-incremento
    // c=b entonces b=b+1 (b pasa a ser 11)
        int c2 = b++;
        System.out.println("Valor de c2 (b++) = " + c2);
        System.out.println("Valor de b (b++) = " + b);

    //¿Qué se imprimiría en cada caso?

    /*CASO 1, se imprime: Valor de c (++a) = 21 ---> Incrementa primero y luego asigna el valor a C
    Valor final de a --> 21
    CASO 2, se imprime: Valor de c (b++) = 10 ---> Asigna el valor a c y luego incrementa b
    Valor final de b --> 11*/
}
}
