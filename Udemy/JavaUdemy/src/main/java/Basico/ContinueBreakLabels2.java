package Basico;


public class ContinueBreakLabels2 {

    public static void main(String[] args) {
        Funcion1:

        for (var i = 0; i < 500; i++) {
            if (i % 7 != 0 | i % 9 != 0) {
                System.out.println(i + " NO SIRVE,seguimos para bingo");
                if (i % 100 == 0) {
                    System.out.println("----¡¡¡CENTENA NÚMERO " + (i/100) + " !!!--------");
                }
                continue Funcion1;       //CONTINUE indica que vaya a la LABE, en este caso, "Funcion1"  
            }                            // Sólo cuando la condición de if NO se cumple, se printará i
            System.out.println(i + " --------> Múltiplo de 7 y 9 SIIIII");
                

        }
    }

}
