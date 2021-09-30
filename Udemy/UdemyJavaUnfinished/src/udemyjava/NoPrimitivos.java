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
public class NoPrimitivos {
    public static void main(String[] args) {
    // int numero1 = null dará error, tipos incompatibles. Integer es un NonPrimitive, tienen muchas funciones (métodos) asociados
Integer numero2 = null;
    System.out.println("El numero2 tiene valor : " +numero2);
String  palabra = "Hola qué tal"; // String permite cadenas de caracteres
    System.out.println(palabra);
    }
}
