
package clases;

public class camarero extends persona{
    private double sueldo;   //double porque los sueldos seguro tendrán decimales
    private static int numCamar = 0; //inicializo a 0 puesto que así, el primero en crearse será el núm.1
    
    private static final double pagoExtra = 12.50; //las horas extra siempre se pagarán a 12,50€
    private double horasExtra; //ese atributo simboliza el número de horas extra que ha hecho el camarero
            
    public camarero(double sueldo, String nombre, int edad) {
        super(nombre, edad);   // la clase hija camarero hereda los atributos nombre y edad de clase persona
        this.sueldo = sueldo;
        this.describirse();  //así consigo que al crearse la instancia, automáticamente se describa
        numCamar++; // incremento del contador "nCamar
        System.out.println("Total de camareros :  " + numCamar);
    }
   
    public double getSueldo() {
        return sueldo;
    }
    
    public void setSueldo(double sueldo) {                
        this.sueldo = sueldo;
    }

    public static int getnCamar() {
        return numCamar;
    }
    /*public static void setnCamar(int nCamar) {
        camarero.nCamar = nCamar;
    }   */                                    // oculto el setter de N para evitar manipulaciones

    public void describirse(){
        super.describirse();// se incluye al método de la clase padre, que está sobrecargado, y se añade nueva info
        System.out.println("Soy camarero y gano " + sueldo + " euros al mes.");    
    }
    
    public double getHorasExtra() {
        return horasExtra;
    }

    public void setHorasExtra(double horasExtra) {
        this.horasExtra = horasExtra;
    }
    
    public void sueldoExtras(double horasExtra) {   // añado esta función que permite calcular el sueldo por horas extras a pagar
        double deuda = horasExtra * pagoExtra;
        System.out.println("A " + nombre + " se le deben " + deuda + " euros en concepto de horas extra.");
    }


}
    

