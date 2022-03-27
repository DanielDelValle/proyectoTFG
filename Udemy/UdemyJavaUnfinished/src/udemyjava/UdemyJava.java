/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package udemyjava;

/**
 *
 * @author Gman
 */
public class UdemyJava {

    /**
     * @param args the command line arguments
     */
    public static void main(String[] args) {
        boolean decision = true;
        System.out.println("la decision es dificil :" +decision);
        char letra = 'a';
        System.out.println(letra);
        int numero = 863298989;
        System.out.println(numero);
        System.out.println("Los comentarios de 1 linea con //, y de varias con /* y */");
        // para imprimir salto de linea, es println
        /*NUMERICOS existen BYTE, SHORT, INT, LONG. DEBEN SER ENTEROS
        OCUPAN RESPECTIVAMENTE 8, 16, 32, 64 bits
        */
        float numerodecimal = 468.45f; // hay que añadir la f tras los decimales para indicar que son float (32bits). Tambien hay double(64bits)
        System.out.println("numerodecimal = " + numerodecimal);
    }
    
}
