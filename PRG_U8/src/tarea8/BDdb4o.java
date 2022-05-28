/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package tarea8;

import com.db4o.*;

/*import com.db4o.Db4oEmbedded;
import com.db4o.ObjectContainer;
import com.db4o.ObjectSet;
import com.db4o.query.Query;
import com.db4o.query.Constraint;*/
/**
 *
 * @author DaniValle
 */
public class BDdb4o {

    private String path = "articulo.yap";     //esta es la ruta (incluido nombre del archivo) donde se almacenarán los objetos. Se creará directamente en la carpeta del proyecto
    private ObjectContainer db;

    public BDdb4o() {
    }

    public void insertar(articulo p, javax.swing.JTextArea pantalla) {
        db = Db4oEmbedded.openFile(Db4oEmbedded.newConfiguration(), path);
        db.store(p);    //se guarda el objeto         
        db.close();     //se cierra la comunicación de escritura.
        pantalla.setText("¡Producto " + p.getNombre() + " registrado con exito!");

    }

    public void mostrar(javax.swing.JTextArea pantalla) {
        db = Db4oEmbedded.openFile(Db4oEmbedded.newConfiguration(), path); //esta es la conexion con la base de datos, donde un argumento es el path del archivo.
        articulo p = new articulo();
        ObjectSet<articulo> resultado = db.queryByExample(p);  
        if (resultado.isEmpty()) {
            pantalla.setText("No hay productos registrados");
        } else {
            pantalla.append("Total de productos: " + resultado.size() + "\n\n");
            while (resultado.hasNext()) {  //mientras que haya un siguiente objeto guardado, se ejecutará la lectura de sus datos
                articulo pd = (articulo) resultado.next();   //obligamos con el "casting" para que el objeto que devuelva el metodo ".next()" sea de tipo "articulo".
                pantalla.append("Codigo: " + pd.getCodigo() + "\n\t" + "Nombre: " + pd.getNombre() + "\n\t" + "Cantidad: " + String.valueOf(pd.getCantidad()) + "\n\t" + "Descripcion: " + pd.getDescripcion() + "\n");

            }
        }
        db.close();
    }

    public void buscar(String codigo, javax.swing.JTextArea pantalla) {
        db = Db4oEmbedded.openFile(Db4oEmbedded.newConfiguration(), path);
        articulo busqueda = new articulo();
        busqueda.setCodigo(codigo);

        ObjectSet<articulo> resultado = db.queryByExample(busqueda); //le digo que busque objetos que sigan el patrón de "busqueda", en este caso con su codigo. Es una busqueda mediante ejemplo, queryByExample.

        if (resultado.isEmpty()) {
            pantalla.setText("No hay coincidencias con ese codigo");

        } else {
            while (resultado.hasNext()) { 
                articulo pd = (articulo) resultado.next();   
                pantalla.setText("¡Producto con codigo " + pd.getCodigo() + " encontrado!\n\t" + "Nombre: " + pd.getNombre() + "\n\t" + "Cantidad: " + String.valueOf(pd.getCantidad()) + "\n\t" + "Descripcion: " + pd.getDescripcion() + "\n");
            }
        } db.close();
    
    }
    
    
    
    public void modificar(String codigo, String nombre, String cantidad, String descripcion, javax.swing.JTextArea pantalla) {
        db = Db4oEmbedded.openFile(Db4oEmbedded.newConfiguration(), path);
        articulo busqueda = new articulo();
        busqueda.setCodigo(codigo);

        ObjectSet<articulo> resultado = db.queryByExample(busqueda);

        if (resultado.isEmpty()) {
            pantalla.setText("No hay coincidencias con ese codigo");

        } else {            
            
            articulo modificado = (articulo) resultado.next(); 
            
            pantalla.setText("¡Producto con codigo " + modificado.getCodigo() + " fue modificado!\n\n DATOS PREVIOS\n\t Nombre: " + modificado.getNombre() + "\n\t" + " Cantidad: " + String.valueOf((modificado.getCantidad())) + "\n\t" + " Descripcion: " + modificado.getDescripcion() + "\n");
            
            if (!nombre.equals("")){  //con estas comprobaciones, me aseguro de que sólo se modifiquen los campos donde se haya introducido valores nuevos, es decir, se mantienen los previos.
                modificado.setNombre(nombre);
            }
            if (!cantidad.equals("")){   //he convertido cantidad a String en esta función, puesto que me conviene para poder comprobar si el campo está vacío o no.
                modificado.setCantidad(Integer.parseInt(cantidad)); 
            }
            if (!descripcion.equals("")){
                modificado.setDescripcion(descripcion);
                }
                
            db.store(modificado);
            pantalla.append("\n\n DATOS NUEVOS\n\t Nombre: " + modificado.getNombre() + "\n\t" + " Cantidad: " + String.valueOf((modificado.getCantidad())) + "\n\t" + " Descripcion: " + modificado.getDescripcion() + "\n");
        } db.close();
    
    }
    
    

    public void eliminar(String codigo, javax.swing.JTextArea pantalla) {
        db = Db4oEmbedded.openFile(Db4oEmbedded.newConfiguration(), path);
        articulo busqueda = new articulo();
        busqueda.setCodigo(codigo);

        ObjectSet<articulo> resultado = db.queryByExample(busqueda);

        if (resultado.isEmpty()) {
            pantalla.setText("No hay coincidencias con ese codigo");

        } else {            
            articulo eliminado = (articulo) resultado.next(); 
            pantalla.setText("¡Producto con codigo " + eliminado.getCodigo() + " fue eliminado!\n\n\t" + "Nombre: " + eliminado.getNombre() + "\n\t" + "Cantidad: " + String.valueOf(eliminado.getCantidad()) + "\n\t" + "Descripcion: " + eliminado.getDescripcion() + "\n");
            db.delete(eliminado);
        } db.close();
    
    }
        
        
}
