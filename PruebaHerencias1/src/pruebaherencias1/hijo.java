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
public class hijo extends padre {
    private String alias;
    
    public void describirse(){
        super.describirse();  // se llama al método del padre
        System.out.println("Me llamo " + name + " , pero mi padre me llama " + alias);
    }

    public hijo(String alias, String name, String ciudad, int edad) {
        super(name, ciudad, edad);
        this.alias = alias;
    }

    public String getAlias() {
        return alias;
    }

    public void setAlias(String alias) {
        this.alias = alias;
    }
    
    
}
