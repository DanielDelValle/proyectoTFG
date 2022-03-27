package Basico;

import java.util.Scanner;

public class OperadorTernario {

    public static void main (String[] args) {
        
        var pregunta1 = "Capital de España";
        var solucion = "Madrid";
        
        Scanner sc = new Scanner(System.in);
        System.out.println("pregunta1 : = "+ pregunta1);   
              
        var respuesta = sc.nextLine();
        System.out.println("Tu respuesta es : " + respuesta);
                
        var correcion = (respuesta.equals(solucion))? "Correcto!":"Lo siento, has fallado!";
        System.out.println(correcion);   // arriba se establecen las salidas para true:false
        
    }
}
