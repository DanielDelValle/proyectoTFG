package Basico;

public class ContinueBreakLabels {
    
    public static void main (String[] args) {
        for (var i = 0; i < 10000; i++) {
            if (i % 2 != 0 || i % 3 != 0 || i % 5 != 0 || i % 7 != 0 || i % 11 != 0) {
                continue;                //CONTINUE indica que el ciclo for continue, es decir, siga iterando  
            }                            // Sólo cuando la condición de if NO se cumple, se printará i
        System.out.println("Divisor de i multiplo de 2, 3, 5 y 7 y 11 :" + i);}
        
        
    }
    
}
