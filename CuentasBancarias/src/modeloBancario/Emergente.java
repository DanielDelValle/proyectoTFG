/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package modeloBancario;
import javax.swing.JOptionPane;
/**
 *
 * @author Daniel
 */
public class Emergente {
   

    public void Emergente (String[] args) {
        
        
    }    
    
    public int crear(){
        
        String texto = "¿Qué tipo de cuenta desea? Introduzca el número correspondiente y pulse OK" + 
            System.lineSeparator() + "1 - Cuenta Corriente" +
            System.lineSeparator() + "2 - Cuenta Ahorro" +
            System.lineSeparator() + "9 - Salir";
        
        String corriente = "CUENTA CORRIENTE: Tendrá un interés anual fijo del 0.15%. Admite descubiertos.";
        String ahorro = "CUENTA DE AHORRO: Tendrá un interés anual variable que usted elija. Exige un saldo mínimo de 500 € en todo momento.";

        
        int opcion;
        int tipo;
        do{
            
        opcion = Integer.parseInt(JOptionPane.showInputDialog(texto));  //aqui transformo la entrada del JOptionPane en int para que sirva de argumento al switch (opcion elegida)
         
        
            switch(opcion){
                
            case(1):
                JOptionPane.showConfirmDialog(null, "¿Crear Cuenta Corriente?",corriente, JOptionPane.YES_NO_OPTION);

                tipo = 1;
                break;
            case(2):
                JOptionPane.showConfirmDialog(null, "¿Crear Cuenta Ahorro?",ahorro, JOptionPane.YES_NO_OPTION); 

                tipo = 2;
                break;                             
            
            case(9):                
                int salir = JOptionPane.showConfirmDialog(null, "¿Seguro de que quiere salir?", "Salir", JOptionPane.YES_NO_OPTION);
                // en "showConfirmDialog, eligiendo la opcion "YES_NO_OPTION", el sistema interpreta el "YES" como un 0 y el "NO" como un 1. Lo uso en un switch:
                switch(salir){
                    case(1): 
                        crear();
                    case(0):
                        break;
                        
                }
            }tipo = 9;
        }while(opcion !=9 );  //hago que el programa ayuda siga corriendo mientras la opción tenga un valor diferente a 7, osea a la opcion que indica salir del programa ayuda
    
        return tipo;
    }    
    
    
        public void salir (){
        int exit = JOptionPane.showConfirmDialog(null, "¿Seguro de que quiere salir del programa?", "Salir", JOptionPane.YES_NO_OPTION);
        // en "showConfirmDialog, eligiendo la opcion "YES_NO_OPTION", el sistema interpreta el "YES" como un 1 y el "NO" como un 0. Lo uso en un switch:
        switch(exit){            
            case(0):
                System.exit(0); 
            case(1): 
                break;

        
        }
    }
}   