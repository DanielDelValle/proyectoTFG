/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package prg_u1;

/**
 *
 * @author jil
 */
public class PGR_U02_EJERS_04 {

    /**
     * @param args the command line arguments
     */
    public static void main(String[] args) {
        // TODO code application logic here
        final double pi = 3.14;
        int lado = 12;
        int base = 10;
        int altura =25;
        int radio= 14;
        
        // Area del cuadrado
        int cuadrado = lado * lado ;
        System.out.println ("El area del cuadrado es: " + cuadrado);
        
        //area del rectángulo
        int rectangulo = base * altura;
        System.out.println ("El area del rectangulo es: " + rectangulo);
        
        //triangula
        double triangulo = (base * altura)/2;
        System.out.println ("El area del triangulo es: " + triangulo);
                
        //circulo
        double circulo =  pi * (radio * radio);
        System.out.println ("El area del circuloo es: " + circulo);
        //usando funciones de la librería math
        double circulo2 = Math.PI * Math.pow(radio, 2);
        System.out.println ("El area del circulo con funciones es: " + circulo2);
    }
    
}
