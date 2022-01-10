package prg_u4_danieldelvallegonzalez;
import clases.*;

public class PRG_U4_DanielDelValleGonzalez {

    public static void main(String[] args) {
        persona hombre1 = new persona("Manolo", 35);
        hombre1.describirse();
        System.out.println("-----------------------"); 
        
        camarero camarero1 = new camarero(1500, "David", 25);
        camarero camarero2 = new camarero(1500, "Sheila", 22);
        camarero maitre1 = new camarero(1850, "Carlos", 34);
        System.out.println("-----------------------");        
        
        //calculo de lo que le deben a camarero2 por haber hecho 35,5 horas extra:
        camarero2.sueldoExtras(35.5);
        //calculo de lo que le deben a maitre1 por haber hecho 72 horas extra:
        maitre1.sueldoExtras(70);
    }    
}
