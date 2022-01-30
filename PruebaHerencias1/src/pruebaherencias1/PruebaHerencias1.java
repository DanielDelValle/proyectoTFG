/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package pruebaherencias1;

/**
 *
 * @author Daniel
 */
public class PruebaHerencias1 {

    /**
     * @param args the command line arguments
     */
    public static void main(String[] args) {
        padre p1 = new padre("Gerardo", "Madrid", 55);
        hijo h1 = new hijo ("Cansino", "Daniel", "Madrid",33);
        p1.describirse();
        h1.describirse(); // el metodo está sobrecargado
        
    }
    
}
