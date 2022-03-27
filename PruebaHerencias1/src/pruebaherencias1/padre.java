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
public class padre {
    protected String name;
    protected String ciudad;
    protected int edad;
    
    public void describirse(){
        System.out.println("Me llamo " + name + " y tengo " + edad + " años");
    }

    public padre(String name, String ciudad, int edad) {
        this.name = name;
        this.ciudad = ciudad;
        this.edad = edad;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getCiudad() {
        return ciudad;
    }

    public void setCiudad(String ciudad) {
        this.ciudad = ciudad;
    }

    public int getEdad() {
        return edad;
    }

    public void setEdad(int edad) {
        this.edad = edad;
    }
    
}
