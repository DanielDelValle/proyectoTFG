/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package prg_u6_dvg;

import java.util.*;
import java.io.*;

/**
 *
 * @author DaniValle
 */
public class fichero {

    public static String fich = "productos.dat";
    private ArrayList<producto> datos = new ArrayList<producto>();

    JOption mensaje = new JOption();    // así siempre que necesite mostrar un mensaje emergente, tengo esta instancia de JOptionPane creada, par usar sus metodos.

    public fichero() {

        try {
            File ficherodatos = new File(fich);
            if (!ficherodatos.exists()) {
                ficherodatos.createNewFile();
            }
            DataInputStream loader = new DataInputStream(new FileInputStream(ficherodatos));
            producto item = new producto();
            item.setCodigo(loader.readUTF());
            while (item.getCodigo() != null) {
                item.setNombre((loader.readUTF()));
                item.setCantidad(loader.readUTF());
                item.setDescripcion(loader.readUTF());
                datos.add(item);
                item = new producto();
                item.setCodigo(loader.readUTF());
            }
            loader.close();
            
        } catch (Exception ex) {
            System.out.println("Error al crear crear el archivo 'productos.dat': " + ex.getMessage());

        }

    }

    public void guardar() {
        try {
            File ficherodatos = new File(fich);
            DataOutputStream saver = new DataOutputStream(new FileOutputStream(ficherodatos));
            for (int i = 0; i < datos.size(); i++) {
                saver.writeUTF(datos.get(i).getCodigo());
                saver.writeUTF(datos.get(i).getNombre());
                saver.writeUTF(datos.get(i).getCantidad());
                saver.writeUTF(datos.get(i).getDescripcion());
            }
            saver.close();
        } catch (Exception ex) {

        }
    }
    
    public void insertar_unico(String codigo, String nombre, String cantidad, String descripcion){          
        
        
        int existe = -1;
          try {
            producto item = new producto(codigo, nombre, cantidad, descripcion);
            for (int i = 0; i < datos.size(); i++) {
                String comparar = datos.get(i).getCodigo().toLowerCase();  // En esta y la siguiente linea, igualo ambos codigos en minusculas para que no sea case sensitive.
                if(codigo.equals("")){
                    mensaje.empty_code();
                    existe = 0;
                    break;
                    }
                else if (comparar.equals(codigo.toLowerCase())) {                 
                    existe = i;       
                    mensaje.not_new(datos.get(existe).getCodigo(), datos.get(existe).getNombre(), datos.get(existe).getCantidad(),datos.get(existe).getDescripcion());
                    break;
                }
                }
            if (existe == -1){  //si la variable "existe" continua invariable, valiendo -1, entonces no hubo coincidencia y el código no está repetido, dando permiso para registrarlo
                  if (codigo.length() > 4){      //restriccion de 4 digitos al codigo
                      mensaje.code_lenght();
                  }
                  if (nombre.equals("")){    //restriccion de 25 digitos al nombre
                      mensaje.empty_name();
                  }
                  
                  if (cantidad.equals("")){    //la descripcion no puede exceder los 250 caracteres
                      mensaje.empty_amount();
                  }      
                  
                  if ((Integer.parseInt(cantidad) > 999) | (Integer.parseInt(cantidad)) < 0){ //la cantidad debe estar entre 0 y 999 incluidos
                      mensaje.amount_lenght();
                  }      
                                                                          
                  else{                  
                    datos.add(item);
                    guardar();
                    mensaje.insertado(codigo);
                    }
                }
            } catch (Exception ex) {
            ex.getMessage();

    }
        
    }
    public String mostrarTodo() {
        String lista0 = "";
        String lista_compra = "";
        int comprar = -1;
        int contador = 0;
        for (int i = 0; i < datos.size(); i++) {
            lista0 = lista0 + System.lineSeparator() + "Codigo:  " + datos.get(i).getCodigo() + System.lineSeparator();
            lista0 = lista0 + "Nombre:  " + datos.get(i).getNombre() + System.lineSeparator();
            lista0 = lista0 + "cantidad:  " + datos.get(i).getCantidad() + System.lineSeparator();
            lista0 = lista0 + "Descripcion:  " + datos.get(i).getDescripcion() + System.lineSeparator();
            contador++;

        }
        return lista0 + System.lineSeparator() + "Total productos: " + contador;
    }
    
    
    
    
    public String comprar() {
        String lista_compra = "Los siguientes productos necesitan comprar más stock: " + System.lineSeparator();
        int comprar = -1;
        int contador = 0; 

        for (int i = 0; i < datos.size(); i++) {

            try {                                           //con este try, creo un archivo.txt a partir de la lista de la compra, para podre imprimirlo y llevarlo encima
            File fich = new File("productos.txt");
            FileWriter fw = new FileWriter(fich, false); 
            
            if (Integer.parseInt(datos.get(i).getCantidad()) <= 0){           //compruebo productos con stock 0 o menos, para advertir de que hay que comprarlos
              comprar = i; 
              contador++;
                lista_compra = System.lineSeparator() + lista_compra + System.lineSeparator() + "Codigo:  " + datos.get(comprar).getCodigo() + System.lineSeparator();
                lista_compra = lista_compra + "Nombre:  " + datos.get(comprar).getNombre() + System.lineSeparator();
                lista_compra = lista_compra + "Cantidad:  " + datos.get(comprar).getCantidad() + System.lineSeparator();
                lista_compra = lista_compra + "Descripcion:  " + datos.get(comprar).getDescripcion() + System.lineSeparator() +System.lineSeparator()+"Total productos: " + contador;           

                fw.write(lista_compra);
               /* fw.write(System.lineSeparator() + "CODIGO:" + datos.get(comprar).getCodigo() + System.lineSeparator());
                fw.write("NOMBRE:" + datos.get(comprar).getNombre() + System.lineSeparator());
                fw.write("CANTIDAD:" + datos.get(comprar).getCantidad() + System.lineSeparator());
                fw.write("DESCRIPCION:" + datos.get(comprar).getDescripcion() + System.lineSeparator());*/

            fw.close();

            }
        } catch (Exception ex) {
            ex.getMessage();
        }

            

            }   if (comprar == -1){                 // si el parametro "comprar" sigue siendo -1, significa que ningun producto tenia un stock igual o menor a cero
                lista_compra = "Todos los productos tienen stock suficiente";

            }return lista_compra;
        }


    public void salir() {
        System.exit(0);    //para cerrar el programa
    }

    public String buscar_exacto(String codigo, String nombre, String cantidad, String descripcion) {  // con este metodo realizamos coincidencia exacta(aunque no sensible a mayusculas/minusculas)
        
        String lista2 = "";
        int busqueda = -1;
        int contador = 0;
        for (int i = 0; i < datos.size(); i++) {            
            String codeLow = datos.get(i).getCodigo().toLowerCase();
            String nameLow = datos.get(i).getNombre().toLowerCase();           // Con estas 4 líneas transformo los string en minusculas para que siempre haya coincidencia
            String amountLow = datos.get(i).getCantidad().toLowerCase(); 
            String descriptionLow = datos.get(i).getDescripcion().toLowerCase();
            
            if ((codeLow.equals(codigo))){                
                                
                busqueda = i;
                contador++;
            
                lista2 = lista2 + System.lineSeparator() + "Codigo:  " + datos.get(busqueda).getCodigo() + System.lineSeparator();
                lista2 = lista2 + "Nombre:  " + datos.get(busqueda).getNombre() + System.lineSeparator();
                lista2 = lista2 + "Cantidad:  " + datos.get(busqueda).getCantidad() + System.lineSeparator();
                lista2 = lista2 + "Descripcion:  " + datos.get(busqueda).getDescripcion() + System.lineSeparator();

            }
        
            if (nameLow.equals(nombre)){                
                                
                busqueda = i;
                contador++;
            
                lista2 = lista2 + System.lineSeparator() + "Codigo:  " + datos.get(busqueda).getCodigo() + System.lineSeparator();
                lista2 = lista2 + "Nombre:  " + datos.get(busqueda).getNombre() + System.lineSeparator();
                lista2 = lista2 + "Cantidad:  " + datos.get(busqueda).getCantidad() + System.lineSeparator();
                lista2 = lista2 + "Descripcion:  " + datos.get(busqueda).getDescripcion() + System.lineSeparator();

            }
            
            if (amountLow.equals(cantidad)){                
                                
                busqueda = i;
                contador++;
            
                lista2 = lista2 + System.lineSeparator() + "Codigo:  " + datos.get(busqueda).getCodigo() + System.lineSeparator();
                lista2 = lista2 + "Nombre:  " + datos.get(busqueda).getNombre() + System.lineSeparator();
                lista2 = lista2 + "Cantidad:  " + datos.get(busqueda).getCantidad() + System.lineSeparator();
                lista2 = lista2 + "Descripcion:  " + datos.get(busqueda).getDescripcion() + System.lineSeparator();

            }           
            
            if (descriptionLow.equals(descripcion)){                
                                
                busqueda = i;
                contador++;
            
                lista2 = lista2 + System.lineSeparator() + "Codigo:  " + datos.get(busqueda).getCodigo() + System.lineSeparator();
                lista2 = lista2 + "Nombre:  " + datos.get(busqueda).getNombre() + System.lineSeparator();
                lista2 = lista2 + "Cantidad:  " + datos.get(busqueda).getCantidad() + System.lineSeparator();
                lista2 = lista2 + "Descripcion:  " + datos.get(busqueda).getDescripcion() + System.lineSeparator();

            }
        }
        if (busqueda >= 0){
            lista2 = lista2 + System.lineSeparator() + "Total resultados de la busqueda: " + contador;
        }
        else {
            lista2 = "No se han encontrado coincidencias con estos parametros";
        }
        return lista2;
    }
    
    

    public String buscar_contiene(String codigo, String nombre, String cantidad, String descripcion) {  //en este metodo, se busca una coincidencia contenida en el campo deseado (no exacta)
        
        String lista3 = "";
        int busqueda = -1;
        int contador = 0;
        for (int i = 0; i < datos.size(); i++) {            
            String codeLow = datos.get(i).getCodigo().toLowerCase();
            String nameLow = datos.get(i).getNombre().toLowerCase();           // Con estas 4 líneas transformo los string en minusculas para que siempre haya coincidencia
            String amountLow = datos.get(i).getCantidad().toLowerCase();
            String descriptionLow = datos.get(i).getDescripcion().toLowerCase();
            
            if (!"".equals(codigo) & (codeLow.contains(codigo))){          // con !"".equals(parametro), me aseguro de que si el parametro esté vacío, no le tenga en cuenta en la busqueda
                                
                busqueda = i;
                contador++;
            
                lista3 = lista3 + System.lineSeparator() + "Codigo:  " + datos.get(busqueda).getCodigo() + System.lineSeparator();
                lista3 = lista3 + "Nombre:  " + datos.get(busqueda).getNombre() + System.lineSeparator();
                lista3 = lista3 + "Cantidad:  " + datos.get(busqueda).getCantidad() + System.lineSeparator();
                lista3 = lista3 + "Descripcion:  " + datos.get(busqueda).getDescripcion() + System.lineSeparator();

            }
        
            else if (!"".equals(nombre) & nameLow.contains(nombre)){                
                                
                busqueda = i;
                contador++;
            
                lista3 = lista3 + System.lineSeparator() + "Codigo:  " + datos.get(busqueda).getCodigo() + System.lineSeparator();
                lista3 = lista3 + "Nombre:  " + datos.get(busqueda).getNombre() + System.lineSeparator();
                lista3 = lista3 + "Cantidad:  " + datos.get(busqueda).getCantidad() + System.lineSeparator();
                lista3 = lista3 + "Descripcion:  " + datos.get(busqueda).getDescripcion() + System.lineSeparator();


            }
            
            else if (!"".equals(nombre) & nameLow.contains(nombre)){                
                                
                busqueda = i;
                contador++;
            
                lista3 = lista3 + System.lineSeparator() + "Codigo:  " + datos.get(busqueda).getCodigo() + System.lineSeparator();
                lista3 = lista3 + "Nombre:  " + datos.get(busqueda).getNombre() + System.lineSeparator();
                lista3 = lista3 + "Cantidad:  " + datos.get(busqueda).getCantidad() + System.lineSeparator();
                lista3 = lista3 + "Descripcion:  " + datos.get(busqueda).getDescripcion() + System.lineSeparator();


            }            
            
            else if (!"".equals(cantidad) & amountLow.contains(cantidad)){                
                                
                busqueda = i;
                contador++;
            
                lista3 = lista3 + System.lineSeparator() + "Codigo:  " + datos.get(busqueda).getCodigo() + System.lineSeparator();
                lista3 = lista3 + "Nombre:  " + datos.get(busqueda).getNombre() + System.lineSeparator();
                lista3 = lista3 + "Cantidad:  " + datos.get(busqueda).getCantidad() + System.lineSeparator();
                lista3 = lista3 + "Descripcion:  " + datos.get(busqueda).getDescripcion() + System.lineSeparator();

            }
        }
        if (busqueda >= 0){
            lista3 = lista3 + System.lineSeparator() + "Total resultados de la busqueda: " + contador;
        }
        else {
            lista3 = "No se han encontrado coincidencias con estos parametros";
        }
        return lista3;
    }
    
    public String modificar(String codigo, String nombre, String cantidad, String descripcion) {
        String lista = "";      //contendrá los valores antes de modificar la clase
        String lista_new = ""; // contendrá los valores tras la modificación, para poder comparar visualmente
        String resultado = "";
        int coincidencia = -1;
        try{
            for (int i = 0; i < datos.size(); i++) {
            String comparar = datos.get(i).getCodigo().toLowerCase();  // En esta y siguiente linea, igualo ambos codigos en minusculas para que no sea case sensitive
            if (comparar.equals(codigo.toLowerCase())) {
               
                lista = lista + System.lineSeparator() + "Codigo:  " + datos.get(i).getCodigo() + System.lineSeparator();
                lista = lista + "Nombre:  " + datos.get(i).getNombre() + System.lineSeparator();
                lista = lista + "Cantidad:  " + datos.get(i).getCantidad() + System.lineSeparator();
                lista = lista + "Descripcion:  " + datos.get(i).getDescripcion() + System.lineSeparator();
                coincidencia = i; 
                break;

            /*} else {                
                continue;*/
            }
        }

            if (coincidencia >= 0) {
               
            int guardar_o_no = mensaje.confirmar(datos.get(coincidencia).getCodigo());  //su valor será 1 ó 0 según elijamos Yes o No en el mensaje emergente
            switch(guardar_o_no){

                case(1):
                    
                    mensaje.no_modificado();
                    break;
                
                case(0):

                    mensaje.modificado(datos.get(coincidencia).getCodigo());

                    lista_new = lista_new + System.lineSeparator() + "Codigo:  " + datos.get(coincidencia).getCodigo() + System.lineSeparator();
                    if (!"".equals(nombre)) {     //en cada caso, compruebo que el campo no está vacío, para sólo sobreescribir aquellos en los que haya valores indicados y no borrar los existentes
                        datos.get(coincidencia).setNombre(nombre);
                        lista_new = lista_new + "Nombre:  " + datos.get(coincidencia).getNombre() + System.lineSeparator();
                        guardar();
                    }
                    if (!"".equals(cantidad)) {
                        int old_cantidad_int = Integer.parseInt(datos.get(coincidencia).getCantidad()); //creo una variable int que es la transformacion del valor de cantidad
                        int dif_cantidad_int = Integer.parseInt(cantidad);  //la otra variable que es el texto introducido en el campo "cantidad"
                        int total = old_cantidad_int + dif_cantidad_int;
                        
                        if (total > 999){                            
                            datos.get(coincidencia).setCantidad("999");
                            mensaje.max_amount(codigo);
                            guardar();
                        }
                        if (total < 0){
                            datos.get(coincidencia).setCantidad(String.valueOf(total));                            
                            mensaje.neg_amount(codigo);
                            guardar();
 
                        }
                        if (total == 0){
                            datos.get(coincidencia).setCantidad(String.valueOf(total));
                            mensaje.zero_amount(codigo);
                            guardar();

                        }
                        
                        lista_new = lista_new + "Cantidad:  " + datos.get(coincidencia).getCantidad() + System.lineSeparator();
                        guardar();
                    }
                    
                    if (!"".equals(descripcion)) {
                        datos.get(coincidencia).setDescripcion(descripcion);
                        lista_new = lista_new + "Descripcion:  " + datos.get(coincidencia).getDescripcion() + System.lineSeparator();
                        guardar();
                    }              
                    
                    resultado = "¡Producto con codigo " + datos.get(coincidencia).getCodigo() + " modificado con exito!" + System.lineSeparator() + System.lineSeparator()
                        + "VALORES ANTERIORES: " + System.lineSeparator() + lista + System.lineSeparator()
                        + "VALORES NUEVOS: " + System.lineSeparator() + lista_new; 
                    
                    
                    break;                    

            }
        }else{ 
                mensaje.no_match();
            }    
        }catch(Exception ex){
            resultado = ex.getMessage();
        }    
        return resultado;
      

    }
    
    public String sumar(String codigo, String nombre, String cantidad){ //este metodo, una variacion de "modificar", verifica que haya una coincidencia de codigo, y suma las cantidades actual e introducida
        String lista = "";      //contendrá los valores antes de modificar la clase
        String lista_new = ""; // contendrá los valores tras la modificación, para poder comparar visualmente
        String resultado = "";
        int coincidencia = -1;
        try{
            for (int i = 0; i < datos.size(); i++) {
            String comparar = datos.get(i).getCodigo().toLowerCase();  // En esta y siguiente linea, igualo ambos codigos en minusculas para que no sea case sensitive
            if (comparar.equals(codigo.toLowerCase())) {
               
                lista = lista + System.lineSeparator() + "Codigo:  " + datos.get(i).getCodigo() + System.lineSeparator();
                lista = lista + "Nombre:  " + datos.get(i).getNombre() + System.lineSeparator();
                lista = lista + "Cantidad:  " + datos.get(i).getCantidad() + System.lineSeparator();
                lista = lista + "Descripcion:  " + datos.get(i).getDescripcion() + System.lineSeparator();
                coincidencia = i; 
                break;

            /*} else {                
                continue;*/
            }
        }

            if (coincidencia >= 0) {
               
            int guardar_o_no = mensaje.confirmar(datos.get(coincidencia).getCodigo());  //su valor será 1 ó 0 según elijamos Yes o No en el mensaje emergente
            switch(guardar_o_no){

                case(1):  //en caso de elegir "No" en la ventana emergente que pregunta si queremos modificar el producto
                    
                    mensaje.no_modificado();
                    break;
                
                case(0):    //en caso de elegir "Yes" en la ventana emergente que pregunta si queremos modificar el producto

                    mensaje.modificado(datos.get(coincidencia).getCodigo());

                    lista_new = lista_new + System.lineSeparator() + "Codigo:  " + datos.get(coincidencia).getCodigo() + System.lineSeparator();
                    
                    if (!"".equals(cantidad)) {
                        int old_cantidad_int = Integer.parseInt(datos.get(coincidencia).getCantidad()); //creo una variable int que es la transformacion del valor de cantidad
                        int dif_cantidad_int = Integer.parseInt(cantidad);  //la otra variable que es el texto introducido en el campo "cantidad"
                        int total = old_cantidad_int + dif_cantidad_int;
                        
                        if (total > 999){
                            datos.get(coincidencia).setCantidad("999");
                            mensaje.max_amount(codigo);
                        }
                        if (total < 0){
                            mensaje.neg_amount(codigo);
                            datos.get(coincidencia).setCantidad(String.valueOf(old_cantidad_int + dif_cantidad_int).toString()); 
                        }
                        if (total == 0){
                            mensaje.zero_amount(codigo);
                        }
                        
                        else{
                        datos.get(coincidencia).setCantidad(String.valueOf(old_cantidad_int + dif_cantidad_int).toString());  //hago el setCantidad a partir de la suma de ambos, tras transformarla de nuevo a String

                    }   lista_new = lista_new + "Cantidad:  " + datos.get(coincidencia).getCantidad() + System.lineSeparator();
                        guardar();
                    }
                    
                    resultado = "¡Stock del producto con codigo " + datos.get(coincidencia).getCodigo() + " modificado con exito!" + System.lineSeparator() + System.lineSeparator()
                        + "VALORES ANTERIORES: " + System.lineSeparator() + lista + System.lineSeparator()
                        + "VALORES NUEVOS: " + System.lineSeparator() + lista_new;                     
                    
                    break;                    

            }
        }else{ 
                mensaje.no_match();  //en caso de que el codigo no coincida con ningun producto existente
            }    
        }catch(Exception ex){
            resultado = ex.getMessage();
        }    
        return resultado;
      

    }
        


public String eliminar(String codigo){
        
        String resultado = "";
        int eliminado = -1;
        try{
            for (int i = 0; i < datos.size(); i++) {
            String comparar = datos.get(i).getCodigo().toLowerCase();  // En esta y la siguiente linea, igualo ambos codigos en minusculas para que no sea case sensitive
            if (comparar.equals(codigo.toLowerCase())) {   
               eliminado = i; 
               break;
                
            /*} else {                
                continue;*/
            }
        }

            if (eliminado >= 0) {
            mensaje.pre_delete(datos.get(eliminado).getCodigo(), datos.get(eliminado).getNombre(), datos.get(eliminado).getCantidad(), datos.get(eliminado).getDescripcion());
            int guardar_o_no = mensaje.confirmar(datos.get(eliminado).getCodigo());  //su valor será 1 ó 0 según elijamos Yes o No en el mensaje emergente
            switch(guardar_o_no){

                case(1):
                    
                    mensaje.not_deleted();
                    break;
                
                case(0):

                    mensaje.deleted(datos.get(eliminado).getCodigo());
                    resultado = resultado + System.lineSeparator() + "Codigo:  " + datos.get(eliminado).getCodigo() + System.lineSeparator();
                    resultado = resultado +  "Nombre:  " + datos.get(eliminado).getNombre() + System.lineSeparator();
                    resultado = resultado +  "Cantidad:  " + datos.get(eliminado).getCantidad() + System.lineSeparator();
                    resultado = resultado +  "Descripcion:  " + datos.get(eliminado).getDescripcion() + System.lineSeparator();
                    
                    resultado = "VALORES DEL PRODUCTO ELIMINADO: " +  System.lineSeparator() + resultado;
                    datos.remove(eliminado);
                    guardar();
                    break;                    

            }
        }else{ 
                mensaje.no_match();
            }
        }catch(Exception ex){
            resultado = ex.getMessage();
        }    
        return resultado;
    }

}