package Basico;

import java.util.Scanner;

public class SwitchCondition {
    
    public static void main(String[] args) {
        
        var sc = new Scanner(System.in);
        System.out.println("Por favor, escoja su opcion: " + "\n" +"1 - Menu    2 - Configuracion   3-Salir" );
        var opcion = sc.nextInt();
        String respuesta = null;
        
        switch (opcion){
            case 1:                                
                respuesta = "Bienvenido al Menu";
                break;
            case 2:                                
                respuesta = "Bienvenido a Configuracion";
                break;
                
            case 3:                                
                respuesta = "Hasta Pronto!!";
                break;
            default: 
                respuesta = "Sólo hay 3 opciones";     
            
        }
        System.out.println("respuesta = " + respuesta);
    }
}
