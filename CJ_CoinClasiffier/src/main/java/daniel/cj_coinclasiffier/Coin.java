package daniel.cj_coinclasiffier;

public class Coin { 
// [habría que crear subclases de cada valor, y en funcion de diam y/o peso, crear una instancia de ese valor
    String nombre;     
    int cantidad; 
    int valor;   //en cents
    int diametro;//en mm/100
    int peso;    //en mm/100
                                //Name 	Face value 	Weight 	Diameter
                            //1 Eurocent 	0,01 € 	2,30 g 	16,25 mm
                            //2 Eurocent 	0,02 € 	3,06 g 	18,75 mm
                            //5 Eurocent 	0,05 € 	3,92 g 	21,25 mm
                            //10 Eurocent 	0,1 € 	4,10 g 	19,75 mm
                            //20 Eurocent 	0,2 € 	5,74 g 	22,25 mm
                            //50 Eurocent 	0,5 € 	7,80 g 	24,25 mm
                            //1 Euro 	1 € 	7,50 g 	23,25 mm
                            //2 Euros 	2 € 	8,50 g 	25,75 mm

    public void contar() {
//      [lista con cantidades actuales de cada moneda]
//      lista.tipomoneda.append+1 (funcion que sume una unidad a ese tipo moneda.
        System.out.println("Hay " + cantidad + " moneda(s) de " + nombre);
    }

    public void clasificar() {
    switch (diametro){
            case 1625:
                nombre = "1 céntimo";
                valor = 1;
                peso = 23;
                cantidad+=1;
                break;
            case 1875:                                
                nombre = "2 céntimos";
                valor = 2;
                peso = 306;
                cantidad+=1;
                break;
            case 2125:                                
                nombre = "5 céntimos";
                valor = 5;
                peso = 392;
                cantidad+=1;
                break;
            case 1975:                                
                nombre = "10 céntimos";
                valor = 10;
                peso = 410;
                cantidad+=1;
                break;
            case 2225:                                
                nombre = "20 céntimos";
                valor = 20;
                peso = 574;
                cantidad+=1;
                break;
            case 2425:                                
                nombre = "50 céntimos";
                valor = 50;
                peso = 780;
                cantidad+=1;
                break;
            case 2325:                                
                nombre = "1 euro";
                valor = 100;
                peso = 750;
                cantidad+=1;
                break;
            case 2575:                                
                nombre = "2 euros";
                valor = 200;
                peso = 850;
                cantidad+=1;
                break;
            default: 
                System.out.println("La moneda no es de curso legal");
        }
        }
    }
