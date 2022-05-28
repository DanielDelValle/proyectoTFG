/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package prg_u9_mysql_java;
import java.sql.*;   //importamos la libreria que permite conectar java con sql. Hay que añadirla a "libraries", dentro del proyecto
import java.util.logging.Level;
import java.util.logging.Logger;

/**
 *
 * @author DaniValle
 */
public class mysql {
    
    public int altas (int codigo, String nombre, int localizacion_id, int manager_id){
        int i=0;
        try {
            Class.forName("com.mysql.jdbc.Driver");  //cargo el driver que enlaza con mysql
            try {
                Connection cn = DriverManager.getConnection("jdbc:mysql://localhost/53665340S","root",""); //establezco conexción a la base de datos
                String sql = "insert into departamentos values(?,?,?,?)";  //es una frase preestablecida, donde cada interrogación equivale a un valor numérico ordenado, por orden de aparición
                PreparedStatement sentencia =cn.prepareStatement(sql);      //cada valor "?" se sustituirá por los "sentencia.setInt, donde el primer índice indica el orden del simbolo a sustituir
                                                                            //y el segundo argumento indica el valor que se introducirá
                
                sentencia.setInt (1,codigo);
                sentencia.setString (2, nombre);
                sentencia.setInt (3, localizacion_id);
                sentencia.setInt (4, manager_id);  //asigno valores a cada una de las interrogaciones
                
                i = sentencia.executeUpdate();
                sentencia.close();
                cn.close();
                
                
            } catch (SQLException ex) {
                Logger.getLogger(mysql.class.getName()).log(Level.SEVERE, null, ex);
            }
 
        } catch (ClassNotFoundException ex) {
            Logger.getLogger(mysql.class.getName()).log(Level.SEVERE, null, ex);
        }
        
        return i;
    }
    
    
   /* public void listado (javax.swing.JTextArea txtarea){
        int i=0;
        try {
            Class.forName("com.mysql.jdbc.Driver");
            try {
                Connection cn = DriverManager.getConnection("jdbc:mysql://localhost/53665340S","root","");
                String sql = "select * from departamentos";
                Statement sentencia =cn.createStatement();
                ResultSet resultado = sentencia.executeQuery(sql);
                txtarea.setText("");
                while (resultado.next()){
                    i++;
                    txtarea.append(resultado.getString(1) + "\t" + resultado.getString(2)+"\n" + "\t" + resultado.getString(3)+"\n" + "\t" + resultado.getString(4)+"\n\n" + "total resultados: "+i);
                }
               
                sentencia.close();
                cn.close();
                
                
            } catch (SQLException ex) {
                Logger.getLogger(mysql.class.getName()).log(Level.SEVERE, null, ex);
            }
 
        } catch (ClassNotFoundException ex) {
            Logger.getLogger(mysql.class.getName()).log(Level.SEVERE, null, ex);
        }*/
        
  
    
    
        public String listado (){
        int contador = 0;
        String linea ="";
        try {
            Class.forName("com.mysql.jdbc.Driver");
            try {
                Connection cn = DriverManager.getConnection("jdbc:mysql://localhost/53665340S","root","");
                String sql = "select * from departamentos";
                Statement sentencia =cn.createStatement();
                ResultSet resultado = sentencia.executeQuery(sql);
                
                while (resultado.next()){  //mientras el resultado de la query tenga lineas, le pido que me las devuelva
                    contador++;
                    linea = linea + " Codigo : " + String.valueOf(resultado.getInt(1)) +"\n" 
                    + " Nombre Departamento : " + resultado.getString(2) +"\n" 
                    + " Id Localizacion : " + String.valueOf(resultado.getInt(3)) +"\n" 
                    + " Id Manager : " + String.valueOf(resultado.getInt(4)) +"\n\n";

                    
                }
               
                sentencia.close();  //cierro las conexiones con la base de datos para no producir fallos y sobrecarga de recursos
                cn.close();
                
                
            } catch (SQLException ex) {
                Logger.getLogger(mysql.class.getName()).log(Level.SEVERE, null, ex);
            }
 
        } catch (ClassNotFoundException ex) {
            Logger.getLogger(mysql.class.getName()).log(Level.SEVERE, null, ex);
        }
        
       return linea + "total resultados: "+ contador +"\n\n";
    }
    
    
    public int eliminar (int codigo){
    int i=0;
        try {
            Class.forName("com.mysql.jdbc.Driver");
            try {
                Connection cn = DriverManager.getConnection("jdbc:mysql://localhost/53665340S","root","");
                String sql = "delete from departamentos Where codigo=?";
                PreparedStatement sentencia = cn.prepareStatement(sql);
                sentencia.setInt (1,codigo);

                i = sentencia.executeUpdate();
                sentencia.close();
                cn.close();
                
                
            } catch (SQLException ex) {
                Logger.getLogger(mysql.class.getName()).log(Level.SEVERE, null, ex);
            }
 
        } catch (ClassNotFoundException ex) {
            Logger.getLogger(mysql.class.getName()).log(Level.SEVERE, null, ex);
        }
        
        return i; 
        
    }    
    
     
    public int modificar (String codigo, String nombre, String localizacion_id, String manager_id){
        int ejecutado =0;
        try {
            //cargar Driver
            Class.forName("com.mysql.jdbc.Driver");
            Connection conexion = DriverManager.getConnection("jdbc:mysql://localhost/53665340S","root","");
            Statement sentencia=conexion.createStatement();
            
            String filtro="";
            
                       
            if (!nombre.trim().equals("")){
                if (!filtro.trim().equals("")){
                    filtro = filtro + " , ";
                }
                else if (filtro.trim().equals("")){
                    filtro = filtro + " set ";
                }
                filtro = filtro + " nombre = '" + nombre.trim() + "'";
                }
            if (!localizacion_id.equals("")){
                if (!filtro.trim().equals("")){
                    filtro = filtro + " , ";
                }
                else if (filtro.trim().equals("")){
                    filtro = filtro + " set ";
                }
                filtro = filtro + " localizacion_id = '" + localizacion_id.trim() +"'";
            }

            if (!manager_id.equals("")){
                if (!filtro.trim().equals("")){
                    filtro = filtro + " , ";
                    filtro = filtro + " manager_id = '" + manager_id.trim() +"'";
                }
                else if (filtro.trim().equals("")){
                    filtro = filtro + " set ";
                    filtro = filtro + " manager_id = '" + manager_id.trim() +"'";
                }
                

            
            }
            
            System.out.println(filtro); //esta sentencia me permite ver si el filtro es correcto, ya que es complejo armar una frase tan larga "a ciegas" 
            ejecutado = sentencia.executeUpdate("update departamentos " + filtro + " where codigo = '" + codigo +"';");

                                       
            sentencia.close();
            conexion.close ();
            
        } catch (ClassNotFoundException ex) {
            Logger.getLogger(mysql.class.getName()).log(Level.SEVERE, null, ex);
            System.out.println(ex.getMessage());  
        } catch (SQLException ex) {
            Logger.getLogger(mysql.class.getName()).log(Level.SEVERE, null, ex);
            System.out.println(ex.getMessage());
        }

        return ejecutado;
    
    }
    public String buscar (String codigo, String nombre, String localizacion_id, String manager_id){ //todos los argumentos son String para compararlos con los valores de SQL y de textfield.getText().
        String linea="";
        int contador =0;
        try {
            //cargar Driver
            Class.forName("com.mysql.jdbc.Driver");
            Connection conexion = DriverManager.getConnection("jdbc:mysql://localhost/53665340S","root","");
            Statement sentencia=conexion.createStatement();
            
            String filtro="";
            if (codigo != ""){
                filtro =" codigo like '%" + codigo.trim() + "%'";
            }
            
            if (nombre.trim()!=""){   //si el campo nombre NO esta vacio
                if (filtro.trim ()!= ""){ //y si el filtro ya tiene datos
                    filtro = filtro + " and "; //añado la palabra "and" para que la query tenga sentido
                }    
                filtro = filtro + " nombre like '%" + nombre.trim() + "%'";
            }
            if (localizacion_id != ""){
                if (filtro.trim ()!= ""){
                    filtro = filtro + " and ";
                }
                filtro = filtro + " localizacion_id like '%" + localizacion_id.trim() +"%'";
            }

            if (manager_id != ""){
                if (filtro.trim ()!= ""){
                    filtro = filtro + " and ";
                }
                filtro = filtro + " manager_id like '%" + manager_id.trim() +"%'";
            
            }
            
            ResultSet resultado = sentencia.executeQuery("select * from departamentos where " + filtro);
            
            while (resultado.next()){
                contador++;
                linea = linea + " Codigo : " + String.valueOf(resultado.getInt(1)) +"\n" 
                        + " Nombre Departamento : " + resultado.getString(2) +"\n" 
                        + " Id Localizacion : " + String.valueOf(resultado.getInt(3)) +"\n" 
                        + " Id Manager : " + String.valueOf(resultado.getInt(4)) +"\n\n";
                        
            }              
                
                        
            sentencia.close();
            conexion.close ();
            
        } catch (ClassNotFoundException ex) {
            Logger.getLogger(mysql.class.getName()).log(Level.SEVERE, null, ex);
            linea = ex.getMessage();
        } catch (SQLException ex) {
            Logger.getLogger(mysql.class.getName()).log(Level.SEVERE, null, ex);
            linea = ex.getMessage();  //si la función entra en un catch y falla, al menos podré imprimir por la interfaz el error (como programador me ayuda a subsanarlo)
        }
        return linea + "número de coincidencias: " + contador +"\n";
    
    }    
    
}



