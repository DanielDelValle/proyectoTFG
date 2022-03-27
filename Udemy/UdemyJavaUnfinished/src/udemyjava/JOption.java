/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package udemyjava;

import javax.swing.JOptionPane;

public class JOption {
    public static void main(String[] args) {
        String cadena;
        int entero;
        char letra;
        double decimal;
        
        cadena = JOptionPane.showInputDialog("Introduzca una cadena de caracteres:");
        /* Con JOptionPane, aparece una ventanita emergente a modo de input mejorado.
        Lo que introduzcamos en ella, podrá ser accesible tras simplemente llamar a la variable donde se guardó (en este caso, "cadena")
        Lo guarda todo como String, asi que si son de otro tipo habrá que convertirlos
        */
        entero = Integer.parseInt(JOptionPane.showInputDialog("Introduzca un número:"));
        //En este caso, hay que introducir el parseInt para convertir la entrada numérica en un texto(string). Lo que está entre () representa un string.
        letra = JOptionPane.showInputDialog("Introduzca una letra").charAt(0); // asi coge solo 1 letra (la primera)
        decimal = Double.parseDouble(JOptionPane.showInputDialog("Digite un decimal"));
        
        JOptionPane.showMessageDialog(null, "La string es: "+cadena);
        JOptionPane.showMessageDialog(null, "El numero entero es :"+entero);
        JOptionPane.showMessageDialog(null, "La letra es :"+letra);
        JOptionPane.showMessageDialog(null, "El decimal es :"+decimal);
    }
}