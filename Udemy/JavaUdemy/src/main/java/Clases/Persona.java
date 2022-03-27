package Clases;

public class Persona {     //Primera letra del nombre de la clase, debe ser MAYUS. Resto, minus. Ej: PersonaMayor
    //Atributos

    String nombre;
    String apellido;

    //Método de esta clase (funcionalidades)
    public void saludar() { //void para que no retorne nada, public para poder usar en otras clases. 
                            //Para diferenciar de clases, 1a letra minusc. Ej.nombre:saludoBuenosDias
        System.out.println("Tu nombre es : " + nombre);
        System.out.println("Tu apellido es : " + apellido);
    }

}