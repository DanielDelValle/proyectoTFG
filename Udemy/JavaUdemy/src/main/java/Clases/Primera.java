package Clases;

public class Primera {

    public static void main(String[] args) {
        //Definimos variable de tipo(clase) persona , pero "vacío" aún (una "previa")
        Persona persona1;
        
        //Instanciando (creando) objeto 
        persona1 = new Persona();
        
        //Accedemos al objeto y llamamos al metodo Saludar
        persona1.saludar();  //el metodo saludar retornara null null por no haber dado valor a los atributos aun
        
        //Modificar atributos para que tengan un valor
        persona1.nombre = "Nini";
        persona1.apellido = "Torres";
        
        // Ahora al llamar al metodo, deberia retornar los valores dados.
        System.out.println(""); //con esto separamos con salto de linea las impresiones (prescindible)
        persona1.saludar();
        
        //Más directo:
        Persona persona2 = new Persona();
        persona2.nombre = "Yoyo";
        persona2.apellido = "Peluso";
        
        System.out.println("");        
        persona2.saludar();
    }

}
