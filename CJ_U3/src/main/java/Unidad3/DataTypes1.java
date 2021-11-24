/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package Unidad3;

/**
 *
 * @author Gman
 */
public class DataTypes1 {
    public static void main(String[] args) {
        //Declare and initialize a byte with a value of 128
        //Observe NetBeans' complaint
        //byte x = 128;
        
        //Declare and initialize a short with a value of 128
        //Create a print statement that casts this short to a byte
        short y = 128;
        
        
        //Declare and initialize a byte with a value of 127
        //Add 1 to this variable and print it
        //Add 1 to this variable again and print it again
        byte z = 127;
        ++z;
        System.out.println("z = " + z);  //prints -128
        ++z;
        System.out.println("z = " + z); //prnts -127
           
  }  
    
}

