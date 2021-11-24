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
public class ShoppingCart {
    public static void main(String[] args){
    String custName = "Alex";
    String itemDesc = "Shirts";
    String message = custName + " wants to purchase" + itemDesc;

    // Declare and initialize numeric fields: price, tax, quantity.   
    double price = 15.99;
    double tax = 7.42;
    int quantity = 3;

    // Declare and assign a calculated totalPrice
    // Modify message to include quantity 
    double totalPrice = quantity * price;
    message = custName + " wants to purchase " + quantity + " " + itemDesc;
    System.out.println (message);

    // Print another message with the total cost
    String message2 = custName + " wants to purchase " + quantity + " " + itemDesc + " for a total cost of " + totalPrice + " €uros";
        System.out.println(message2);
    }
}
