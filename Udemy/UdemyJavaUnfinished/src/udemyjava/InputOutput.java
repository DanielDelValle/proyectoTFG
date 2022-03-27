/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package udemyjava;

import java.util.Scanner;

public class InputOutput {
    public static void main(String[] args) {
        Scanner entrada = new Scanner(System.in); //Scanner es como "input" en Python
        int age;
            System.out.println("Introduzca su edad");
            age = entrada.nextInt();
            System.out.println("Su edad es : " +age);
        float notaMedia;
            System.out.println("Introduzca su Nota Media");
            notaMedia = entrada.nextFloat();
            System.out.println("Su nota media es :"+notaMedia); //en float, si la respuesta la escribimos con punto, da error. Los decimales mejor con coma.
        double cantidad;
            System.out.println("Teclee cantidad con decimales");
            cantidad = entrada.nextDouble();
            System.out.println("la cantidad es: "+cantidad);
        //para guardar cadenas de caracteres (strings), nos vale next y nextLine.
        String texto;
            System.out.println("Introduzca una palabra o frase");
            texto = entrada.nextLine();   //solo retornará el texto hasta encontrar un espacio
            System.out.println("Su texto completo es: : " + texto);
        String palabra;
            palabra = entrada.next();
            System.out.println("Su primera palabra es: : " + palabra);
        char letra;
            System.out.println("Introduzca una palabra");
            letra = entrada.next().charAt(0); //guardará la primera letra        
            System.out.println(letra);
    }
}
