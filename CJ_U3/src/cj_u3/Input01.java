package cj_u3;

import javax.swing.JOptionPane;


public class Input01 {
    public static void main(String[] args) {
        //Create a JOptionPane.
        //Store the input as a String and print it.
        String inputString = JOptionPane.showInputDialog("Please, enter your age");
        System.out.println("inputString = " + inputString);
        
        //Parse the input as an int.
        //Print its value +1
        int input = Integer.parseInt(inputString);
        input++;
        System.out.println("input + 1= " + input);
        
        
        //Try creating a dialog, parsing it, and initializing an int in a single line.
        //You should have only one semicolon (;) in this line.
        int input2 = Integer.parseInt(JOptionPane.showInputDialog("Please, enter your birth year")) +1;
        System.out.println(input2);
        
        
    }
}
