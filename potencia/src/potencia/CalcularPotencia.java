
package potencia;

/**
 *
 * @author Gman
 */
public class CalcularPotencia {

    private int base;
    private int exp;
    private int resultado;

    
    public CalcularPotencia() {  //los atributos obligatorios al declarar la instancia serán sólo "base" y "exp", ya que el resultado es lo que buscamos.
    
    try {   
        this.base = base;
        this.exp = exp;
        this.resultado = resultado; }
        
    catch (Exception errorGeneral) { //error general, no específico "Exception"
            System.out.println("Error - Al instanciar, por favor introduzca sólo valores enteros como argumentos  " + "\n" + errorGeneral.getMessage()); }
    
    }

    public int getBase() {
        return base;
    }

    public void setBase(int base) {
        this.base = base;
    }

    public int getExponente() {
        return base;
    }

    public void setExponente(int exponente) {
        this.exp = exponente;        
    }
    public int getResultado(int base, int exp) {   // de "resultado" solo creo el "getter", ya que no tendría sentido crear un "setter2, puesto que eso lo calcula el programa.
        int acum = 1;                   //creando esta variable local, consigo poder acumular lo que sale de multiplicar base*base sin sobreescribir "base" ni "resultado"

    try {  if (exp == 0) {
           resultado = 1;
           System.out.println("El resultado de " + base + " elevado a " + exp + " es "+ resultado);
            
        } else if (base == 1) {            
            resultado = 1;
            System.out.println("El resultado de " + base + " elevado a " + exp + " es "+ resultado);
            
        } else {
            if (base > 1 && exp > 0) {
                for (int i = 1; i <= exp; i++) {
                    acum = base * acum;
                    resultado = acum;
            }
        }System.out.println("El resultado de " + base + " elevado a " + exp + " es "+ resultado);}
 

    }    catch (Exception ErrorValores) {
            
            System.out.println("Error en los valores: por favor, introduzca base y exponentes de valor entero positivo");
            }           
    return resultado;    //return es obligado por parte de la función y de java, ya que es una función en este caso que no tiene el "void" en su descripción.}    
    }
    public void ayuda() {
       System.out.println("Esta clase permite calcular la potencia de un número elevado al exponente deseados.");
    }
    
}

