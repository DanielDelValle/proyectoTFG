
package clases;

public class persona { //atributos protected en lugar de private, pues tendrá clases hijas que deben poder heredarlos
    protected String nombre;
    protected int edad;
    
    public persona(String nombre, int edad) {
        this.nombre = nombre;
        this.edad = edad;           
    }
    
    public String getNombre() {
        return nombre;
    }

    public int getEdad() {
        return edad;
    }
    
    public void setNombre(String nombre) {
        this.nombre = nombre;
    }
    
    public void setEdad(int edad) {
        if (edad > 0)
            this.edad = edad;
        else
            System.out.println("Por favor, introduce una cifra positiva (mayor que 0)");
    }    
    public void describirse(){
        System.out.println("Me llamo " + nombre + " y tengo " + edad + " años.");
        /*el método "describirse" se usará para visualizar 
        los atributos de las instancias de persona*/
    }    
    
}
