package Clases;

public class PersonaPrueba {
    
    public static void main (String args[]){  //parece ser que [] pueden ir tambien en esa posicion - investigar
        //crear instancia (objeto) de la clase Persona; su nombre será p1
        Persona p1 = new Persona();
        //Modificar atributos
        p1.nombre = "Daniel";
        p1.apellido = "Del Valle";
        //Tambien podriamos decir "Persona p1;". Así, se crea como variable, y más tarde se especifican
        //los atributos
    }    
}
