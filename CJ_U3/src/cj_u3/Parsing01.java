package cj_u3;

public class Parsing01 {
    public static void main(String[] args) {
        //Declare and intitialize 3 Strings: shirtPrice, taxRate, and gibberish
        String shirtPrice = "15";
        String taxRate = "0.05";
        String gibberish = "frege894g68teg4";
        
        
        
        //Parse shirtPrice and taxRate, and print the total tax
        int shirtPrice2 = Integer.parseInt(shirtPrice);
        double taxRate2 = Double.parseDouble(taxRate);
        System.out.println("total tax = " + (shirtPrice2*taxRate2));
        
        
        //Try to parse taxRate as an int
        int taxRate3 = Integer.parseInt(taxRate); // returns error
        System.out.println("taxRate3 = " + taxRate3);
        //Try to parse gibberish as an int
        int gibberish2 = Integer.parseInt(gibberish); //returns error
        System.out.println("gibberish2 = " + gibberish2);
    }
    
}
