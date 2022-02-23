/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package prg_u5_dvg;

import java.io.*;

/**
 *
 * @author DaniValle
 */
public class producto {

    private String codigo;
    private String nombre;
    private int cantidad;
    private String descripcion;

    public producto() {
    }

    public producto(String codigo, String nombre, int cantidad, String descripcion) {
        this.codigo = codigo;
        this.nombre = nombre;  
        this.cantidad = cantidad;
        this.descripcion = descripcion;
    }

    public String getCodigo() {
        return codigo;
    }

    public void setCodigo(String codigo) {
        this.codigo = codigo;
    }

    public String getNombre() {
        return nombre;
    }

    public void setNombre(String nombre) {
        this.nombre = nombre;
    }

    public int getCantidad() {
        return cantidad;
    }
    public void setCantidad(int cantidad){
        this.cantidad = cantidad;
    }

    public String getDescripcion() {
        return descripcion;
    }

    public void setDescripcion(String descripcion) {
        this.descripcion = descripcion;
    }
    
    public String Insertar() throws IOException {

        String informe;
        try {
            File fich = new File("productos.txt");
            FileWriter fw = new FileWriter(fich, true);
            
            fw.write("ID:" + codigo + System.lineSeparator());
            fw.write("NOMBRE:" + nombre.toLowerCase() + System.lineSeparator());
            fw.write("CANTIDAD:" + cantidad + System.lineSeparator());
            fw.write("DESCRIPCION:" + descripcion.toLowerCase() + System.lineSeparator());
            
            fw.close();
            informe = "Producto registrado con éxito!" + System.lineSeparator();

        } catch (Exception ex) {
            informe = "Error al registrar el producto" + ex.getMessage();
        }
        return informe;
    }

    public String MostrarTodo() {
        String lectura = "";
        int contador = 0;
        try {
            File fich = new File("productos.txt");
            BufferedReader bfr = new BufferedReader(new FileReader(fich));
            //int contador = 1;
            String code = bfr.readLine();
            while (code != null) {
                String name = bfr.readLine();
                String amount = bfr.readLine();
                String description = bfr.readLine();
                lectura = lectura + System.lineSeparator() + ((contador) + 1) + ": " + code + "  " + name + "  " + amount + "  " + description + System.lineSeparator();
                contador++;
                code = bfr.readLine();
            }

            bfr.close();

        } catch (Exception ex) {
            lectura = "Error al mostrar el listado " + ex.getMessage() + System.lineSeparator();

        }
        return lectura + System.lineSeparator() + "Total productos: " + contador;
    }

    public String Buscar(String busqueda) {
        String lectura2 = "";
        int contador2 = 0;
        try {
            File fich = new File("productos.txt");
            BufferedReader bfr = new BufferedReader(new FileReader(fich));
            String code = bfr.readLine();
            while (code != null) {
                String name = bfr.readLine();
                String amount = bfr.readLine();
                String description = bfr.readLine();
                
                String nameLow=name.toLowerCase();                               // Con estas 2 líneas transformo los string en minusculas para que siempre haya coincidencia
                String descriptionLow=description.toLowerCase();
                
                if (code.contains(busqueda)) {
                    lectura2 = lectura2 + System.lineSeparator() + ((contador2) + 1) + ": " + code + "  " + name + "  " + amount + "  " + description + System.lineSeparator();
                    contador2++;                    
                    }
                else if(nameLow.contains(busqueda)) {
                    lectura2 = lectura2 + System.lineSeparator() + ((contador2) + 1) + ": " + code + "  " + name + "  " + amount + "  " + description + System.lineSeparator();
                    contador2++;
                    }
                else if(descriptionLow.contains(busqueda)) {
                    lectura2 = lectura2 + System.lineSeparator() + ((contador2) + 1) + ": " + code + "  " + name + "  " + amount + "  " + description + System.lineSeparator();                    
                    contador2++;
                    }
                code = bfr.readLine();
            }
            bfr.close();

        } catch (Exception ex) {
            lectura2 = "Error al mostrar el listado " + ex.getMessage() + System.lineSeparator();

        }
        return lectura2 + System.lineSeparator() + "Total coincidencias: " + contador2;
    }

    public void salir() {
        System.exit(0);    //para cerrar el programa
    }
 }
