package prg_u2;



public class Texto {
    
    public static void main (String[] args) {
    
        String mi_nombre = "Daniel del Valle Gonzalez";
        
        //Longitud del String:
        int longitud = mi_nombre.length();
        System.out.println("longitud del string = " + longitud);
        
        //Primera letra:
        char prim = mi_nombre.charAt(0);        
        System.out.println("Primera letra: " + prim);
        
        //Ultima letra:
        char ult = mi_nombre.charAt(mi_nombre.length()-1);        
        System.out.println("Ultima letra: " + ult);
        
        //El nombre de pila:
         String primPalabra = mi_nombre.substring(0, mi_nombre.indexOf(" "));
         System.out.println("Primera palabra = " + primPalabra);
         
        //El segundo apellido:
        String ultPalabra = mi_nombre.substring(mi_nombre.lastIndexOf(" ")+1);
        System.out.println("Ultima Palabra = " + ultPalabra);
        
        //Cambiar "a" por "_" :
        System.out.println("Reemplazando caracteres: " + mi_nombre.replace("a", "_"));
        
        DanielDelValleGonzalez correcto = new DanielDelValleGonzalez("Manolo", 45); //instanciamos un objeto con valores correctos        
        System.out.println("Nombre: " + correcto.getNombre());
        System.out.println("Edad: " + correcto.getEdad());
        
        correcto.setNombre("Antonio");  //ahora le damos otros valores válidos con los métodos "setter"
        correcto.setEdad(25);
        System.out.println("Nombre desde el setter: " + correcto.getNombre());
        System.out.println("Edad desde el setter: " + correcto.getEdad());
        
        DanielDelValleGonzalez erroneo = new DanielDelValleGonzalez("Carlos", 180);  //instanciamos un objeto con edad fuera de rango
        System.out.println("Comprobar edad reseteada a 0 por estar fuera de rango: edad = " + erroneo.getEdad()); 
        //En este caso, se retornará por pantalla la edad errónea, ya que no hay manera de controlarlo si se fija desde el constructor
        
        erroneo.setNombre("Francisca");
        erroneo.setEdad(150);               //ahora  sí que será corregido y resetado a 0, además de mostrarse el mensaje de error.
        System.out.println("Comprobar edad reseteada a 0 por estar fuera de rango: edad = " + erroneo.getEdad());
        
        //Comprobamos el metodo ayuda
        
        correcto.ayuda();
    }
}
