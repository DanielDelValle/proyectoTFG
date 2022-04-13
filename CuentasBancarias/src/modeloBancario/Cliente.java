/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package modeloBancario;

/**
 *
 * @author Daniel
 */
public class Cliente {
    private int idCliente;
    private String nombre;
    private String direccion;
    private String telefono;
    
    public Cliente() {
        idCliente = 0;
        nombre = null;
        direccion = null;
        telefono = null;
    }

    public Cliente(int idCliente, String nombre, String direccion, String telefono) {
        this.idCliente = idCliente;
        this.nombre = nombre;
        this.direccion = direccion;
        this.telefono = telefono;
    }
    
    
    
    
}
