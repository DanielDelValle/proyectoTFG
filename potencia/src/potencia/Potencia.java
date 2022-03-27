
package potencia;

/**
 *
 * @author Gman
 */
public class Potencia {

    /**
     * @param args the command line arguments
     */
    public static void main(String[] args) {
//        CalcularPotencia2 potencia2 = new CalcularPotencia2(3, 5);
//        potencia2.getResultado();
//        potencia2.ayuda();
        
        CalcularPotencia potencia = new CalcularPotencia();
        potencia.ayuda();          //método ayuda que explica la funcionalidad de la clase CalcularPotencia
        // Ejecutando ejemplos indicados:
        potencia.getResultado(0,10);
        potencia.getResultado(10,0);
        potencia.getResultado(1,20);
        potencia.getResultado(5,3);
        potencia.getResultado(11,5);
        
    }
}