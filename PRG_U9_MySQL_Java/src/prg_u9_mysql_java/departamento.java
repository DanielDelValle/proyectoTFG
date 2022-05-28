/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package prg_u9_mysql_java;

/**
 *
 * @author DaniValle
 */
public class departamento {
    protected int codigo;
    protected String nombre;
    protected int localizacion_id;
    protected int manager_id;

    public departamento(int codigo, String nombre, int localizacion_id, int manager_id) {
        this.codigo = codigo;
        this.nombre = nombre;
        this.localizacion_id = localizacion_id;
        this.manager_id = manager_id;
    }

    public int getCodigo() {
        return codigo;
    }

    public void setCodigo(int codigo) {
        this.codigo = codigo;
    }

    public String getNombre() {
        return nombre;
    }

    public void setNombre(String nombre) {
        this.nombre = nombre;
    }

    public int getLocalizacion_id() {
        return localizacion_id;
    }

    public void setLocalizacion_id(int localizacion_id) {
        this.localizacion_id = localizacion_id;
    }

    public int getManager_id() {
        return manager_id;
    }

    public void setManager_id(int manager_id) {
        this.manager_id = manager_id;
    }
    
    
    
    
    
}
