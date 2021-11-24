/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package daniel.cj_coinclasiffier;

/**
 *
 * @author Daniel
 */
public class Example {
    
    public static void main(String[] args) {
        
        Coin coin1 = new Coin();
        coin1.diametro = 1625;
        coin1.clasificar();
        coin1.contar();
        Coin coin2 = new Coin();
        coin2.diametro = 1875;
        coin2.clasificar();
        coin2.contar();
    }
    
}
