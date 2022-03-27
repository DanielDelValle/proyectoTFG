package prg_u2;


/**
 *
 * @author Daniel
 */
public class DanielDelValleGonzalez {  //constructor sin valores por defecto, solo especificando atributos
    
    private String nombre;
    private int edad;
    
    public DanielDelValleGonzalez() {   //Constructor con valores de atributos por defecto
        nombre = "Daniel";
        edad = 33;
    }
    public DanielDelValleGonzalez(String nombre, int edad) {   //constructor con atributos sin definir
        this.nombre = nombre;
        this.edad = edad;
     
    }
    //---------------------------------------------------------------------//
    
    //Getters & Setters
    
    public String getNombre() {
        return nombre;
    }

    public void setNombre(String nombre) {
        this.nombre = nombre;
    }

    public int getEdad() {
        return edad;
    }

    public void setEdad(int edad) {
        if (edad<=100) {        //Con esta sentencia, condicionamos el valor de edad a valer como máximo 100.
        this.edad = edad;
        } else {this.edad = 0;  //Con esta sentencia, si edad vale más de 100, se reseteará a 0 y aparecerá el mensaje de error.
        System.out.println("PERSONA ERRONEA");
        }
    }
    
    //Metodo "ayuda"
    public void ayuda() {
       System.out.println("Esta clase permite crear personas con un nombre y una edad");
    }
    
}
