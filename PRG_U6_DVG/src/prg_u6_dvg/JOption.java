/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package prg_u6_dvg;
import javax.swing.JOptionPane;

/**
 *
 * @author DaniValle
 */

public class JOption {
    

    public void JOption (String[] args) {
        
        
    }    
        
    public void help(){
        
        String texto = "¿Sobre que funcion necesita ayuda? Introduzca el número correspondiente y pulse OK" + 
            System.lineSeparator() + "1 - Insertar" +
            System.lineSeparator() + "2 - Buscar // Busqueda Exacta" +
            System.lineSeparator() + "3 - Modificar // Sumar" +
            System.lineSeparator() + "4 - Eliminar" +
            System.lineSeparator() + "5 - Mostrar Todo"+
            System.lineSeparator() + "6 - Lista Compra"+
            System.lineSeparator() + "7 - Salir de la Ayuda";
        
        String insertar = "1 - INSERTAR: Debe rellenar con un valor válido todos los campos obligatorios para crear un nuevo producto." +
                        System.lineSeparator() + "CODIGO: Valor alfanumerico de 4 cifras, obligatorio" +
                        System.lineSeparator() + "NOMBRE: Valor alfanumerico, obligaorio" +
                        System.lineSeparator() + "CANTIDAD: Valor numerico entre 0 y 1000, ambos incluidos, obligatorio"+
                        System.lineSeparator() + "DESCRIPCION: NO es obligatorio. En este campo puede incluir la medida (kilos, docenas, paquetes...)." +
                        System.lineSeparator() + System.lineSeparator() +
                        "Nota: Si el codigo de producto ya existe en la base de datos, no podrá introducirlo de nuevo para evitar duplicados. Use el boton 'modificar'.";
        
        String buscar = "2 - BUSCAR/Busqueda Exacta: Debe introducir un unico valor en un campo cualquiera del producto" + 
                        System.lineSeparator() + "Si en la lista hay un producto cuyo campo contenga el valor introducido, se motrará en pantalla el resultado." +
                        System.lineSeparator() + "Además, marcando la casilla 'BUSQUEDA EXACTA' se realizará una busqueda que solo muestre las coincidencias exactas";
        String modificar = "3 - MODIFICAR/Sumar: Debe introducir un codigo valido y existente en la base de datos, ademas de rellenar los campos deseados con la nueva informacion a introducir.Se sustituiran los valores previos."
                + System.lineSeparator() + "Tras rellenar los campos, pulse modificar y aparecera un mensaje de confirmacion. Pulsando 'aceptar', los cambios seran definitivos."
                + System.lineSeparator() + "Si marca la casilla 'SUMAR', solo debera rellenar los campos 'codigo' y 'cantidad' para sumar (o restar) la cantidad introducida (establezca un valor positivo o negativo)";
                
        String eliminar = "4 - ELIMINAR: Debe introducir un codigo valido y existente en la base de datos. Pulse 'eliminar' y aparecera un mensaje de confirmacion. Pulsando 'aceptar', los cambios seran definitivos.";
        
        String mostrar = "5 - MOSTRAR TODO: Pulse este boton para mostrar el listado completo de productos registrados.";
        
        String comprar = "6 - LISTA COMPRA: Pulse este boton para mostrar el listado de los productos cuyo stock (cantidad) es igual o menor a cero. " 
                + System.lineSeparator() + "Ademas, el programa generará un archivo de texto llamado 'productos.txt' con el fin de poder imprimirlo o enviarlo.";
                
        
        int opcion;
        
        do{
            
        opcion = Integer.parseInt(JOptionPane.showInputDialog(texto));  //aqui transformo la entrada del JOptionPane en int para que sirva de argumento al switch (opcion elegida)
         
        
            switch(opcion){
                
            case(1):
                JOptionPane.showMessageDialog(null, insertar);
                break;
            case(2):
                JOptionPane.showMessageDialog(null, buscar); 
                break;                
            case(3):
                JOptionPane.showMessageDialog(null, modificar);
                break;                
            case(4):
                JOptionPane.showMessageDialog(null, eliminar);
                break;                
            case(5):
                JOptionPane.showMessageDialog(null, mostrar);
                break;      
            case(6):
                JOptionPane.showMessageDialog(null, comprar);
                break;                      
            
            case(7):                
                int salir = JOptionPane.showConfirmDialog(null, "¿Seguro de que quiere salir de la ayuda?", "Salir", JOptionPane.YES_NO_OPTION);
                // en "showConfirmDialog, eligiendo la opcion "YES_NO_OPTION", el sistema interpreta el "YES" como un 0 y el "NO" como un 1. Lo uso en un switch:
                switch(salir){
                    case(1): 
                        help();
                    case(0):
                        break;
                }
            }
        }while(opcion !=6 );  //hago que el programa ayuda siga corriendo mientras la opción tenga un valor diferente a 7, osea a la opcion que indica salir del programa ayuda
    }
    
    public void mensaje_comprar(String lista_compra){
    JOptionPane.showMessageDialog(null, lista_compra);
    
    }
    
    public void code_lenght(){
        JOptionPane.showMessageDialog(null, "La longitud del campo 'codigo' debe ser de 4 caracteres como maximo");
    
    }
    
    public void amount_lenght(){
        JOptionPane.showMessageDialog(null, "El valor del campo 'cantidad' debe estar entre 0 y 999 ambos incluidos");   
    
    }    
    
    public void neg_amount(String codigo){
    JOptionPane.showMessageDialog(null, "¡Tienes una cantidad negativa del producto con codigo "+ codigo+ "!");   

    }    
    
    public void max_amount(String codigo){
    JOptionPane.showMessageDialog(null, "¡Has alcanzado el limite de cantidad posible del producto con codigo " + codigo+  "!");   

    }  
    
    public void zero_amount(String codigo){
    JOptionPane.showMessageDialog(null, "¡Te has quedado sin stock del producto con codigo "+ codigo+  "!");   

    }  
      
    public void modificado(String codigo){
        JOptionPane.showMessageDialog(null, "¡Producto con codigo '" + codigo + "' modificado con exito!");
    }    
    public void no_modificado(){
    JOptionPane.showMessageDialog(null, "No se modificó el producto");
    }
    public void deleted(String codigo){
    JOptionPane.showMessageDialog(null, "¡Producto con codigo '" + codigo + "' ha sido eliminado!");
    }   
    
    public void not_deleted(){
    JOptionPane.showMessageDialog(null, "No se eliminó el producto");
    }        
    
    public void no_match(){  //este metodo sirve para la busqueda en caso de que no haya coincidencias
        JOptionPane.showMessageDialog(null, "No hay coincidencias con los datos introducidos");
    }
    public void not_new(String codigo, String nombre, String cantidad, String descripcion){ //este metodo sirve para la funcion "insertar" en caso de que el codigo ya exista, para evitar duplicidades
        JOptionPane.showMessageDialog(null, "Ya existe un producto registrado con el codigo '"+codigo+"' :" + System.lineSeparator()
                + System.lineSeparator() + System.lineSeparator() + " > Nombre: " +nombre
                + System.lineSeparator() + System.lineSeparator() + " > Cantidad: " + cantidad
                + System.lineSeparator() + System.lineSeparator() + " > Descripcion: " + descripcion + System.lineSeparator()+ System.lineSeparator() +
                " >>>Cada codigo debe ser unico para evitar duplicidades.<<<" + System.lineSeparator() + "Por favor, use la opcion modificar si lo que quiere es cambiar alguno de sus campos.");
    }
    
    public void pre_delete(String codigo, String nombre, String cantidad, String descripcion){ //este metodo sirve para la funcion "eliminar"para dar a conocer el producto que va a eliminarse
        JOptionPane.showMessageDialog(null, "Está a punto de eliminar el producto con codigo '"+codigo+"' :" + System.lineSeparator()
                + System.lineSeparator() + System.lineSeparator() + " > Nombre: " +nombre
                + System.lineSeparator() + System.lineSeparator() + " > Cantidad: " + cantidad
                + System.lineSeparator() + System.lineSeparator() + " > Descripcion: " + descripcion + System.lineSeparator());
                
    }    
    
    public void insertado(String codigo){
        JOptionPane.showMessageDialog(null, "¡Producto con codigo '" + codigo + "' registrado con exito!");
    }
    
    public void empty_code(){
    JOptionPane.showMessageDialog(null, "El campo 'codigo' está vacío; no se pueden registrar productos sin codigo");
    }
    
    public void empty_name(){
    JOptionPane.showMessageDialog(null, "El campo 'nombre' está vacío; es un valor obligatorio");
    }    
    public void empty_amount(){
    JOptionPane.showMessageDialog(null, "El campo 'cantidad' está vacío; es un valor obligatorio");
    }
    
    public int confirmar(String codigo){
        int confirmar = JOptionPane.showConfirmDialog(null, "¿Desea confirmar los cambios en el producto con codigo '" + codigo + "' ?", "Confirmar cambios", JOptionPane.YES_NO_OPTION);
    // en "showConfirmDialog, eligiendo la opcion "YES_NO_OPTION", el sistema interpreta el "YES" como un 0 y el "NO" como un 1. Lo uso en un switch.
       return confirmar;    
    }
    public void salir (){
        int exit = JOptionPane.showConfirmDialog(null, "¿Seguro de que quiere salir del programa?", "Salir", JOptionPane.YES_NO_OPTION);
        // en "showConfirmDialog, eligiendo la opcion "YES_NO_OPTION", el sistema interpreta el "YES" como un 1 y el "NO" como un 0. Lo uso en un switch:
        switch(exit){            
            case(0):
                System.exit(0); 
            case(1): 
                break;

        
        }
        
    }
} 
