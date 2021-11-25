/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package Tarea1;

/**
 *
 * @author jil
 */
public class PGR_U02_EJERS_05 {

    /**
     * @param args the command line arguments
     */
    public static void main(String[] args) {
        int distancia = 25; //en kilómetros
        int velocidad = 9; //kilometros/hora
        double tiempo = 0.0;
        tiempo = (double) distancia/velocidad;
        
        System.out.println ("El corredor recorre " + distancia + 
                " Km. a una velocidad de " + velocidad + " Km/h. en " + 
                tiempo + " horas.");
    }
    
}
