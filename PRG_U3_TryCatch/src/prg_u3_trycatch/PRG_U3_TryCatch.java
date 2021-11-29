/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package prg_u3_trycatch;

/**
 *
 * @author Gman
 */
public class PRG_U3_TryCatch {

    public static void main(String[] args) {
        errores();
        sustituto();
        
        
        
    }   
    public static void errores(){

        int n1 = 12;
        int n2 = 0;
        int resultado;     

        try {
            resultado = n1 / n2;
            System.out.println("resultado = " + resultado);
            
        } catch (Exception err) { //error general, no específico "Exception"
            System.out.println("Error  " + err.getMessage());
            
        } finally {
            System.out.println("This part is always read");

        
        }
    }   public static void sustituto(){
        
            String frase = ("Hola Mundo, me llamo Daniel");
            for (int i = 0; i<frase.length();i++){
                if (frase.charAt(i)=='a'){
                    System.out.print("@");}
                if (frase.charAt(i)=='e'){
                    System.out.print("3");}
                
                else {
                    System.out.print(frase.charAt(i));
                            }
                }System.out.println("\n");
    }
            


        
        }   


