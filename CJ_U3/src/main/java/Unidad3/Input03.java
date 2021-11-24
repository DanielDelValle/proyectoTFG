package Unidad3;

import java.util.Scanner;

class Input03 {

    public static void main(String[] args) {
        //Create a Scanner
        Scanner entrada = new Scanner(System.in);
        //Find and print the sum of three integers entered by the user
        System.out.println("Introduzca su edad");
        int a = entrada.nextInt();
        System.out.println("Introduzca la edad de su padre");
        int b = entrada.nextInt();
        System.out.println("Introduzca la edad de su abuelo");
        int c = entrada.nextInt();
        
      
        System.out.println("Sus edades suman = " + (a + b + c) + " años");
        entrada.close();
        
        //Remember to close the Scanner
        
    }
}
