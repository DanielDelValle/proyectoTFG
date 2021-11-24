package Unidad3;

import javax.swing.JOptionPane;

public class Input02 {
    public static void main(String[] args) {
        
        JOptionPane.showMessageDialog(null,
                "Este panel no acepta inputs - Es sólo un mensaje del sistema", //message shown
                "Aviso importante para usuarios", //title
                0);    //Icon number

        
        String input1 = (String)JOptionPane.showInputDialog(null,
                "Por favor, introduzca su nombre",
                "Pregunta 1 - Nombre",
                3,
                null,
                null,
                "Escriba aquí su nombre");
        JOptionPane.showMessageDialog(null,
                "Bienvenido, " + input1, //message shown
                "Saludo", //title
                1);    //Icon number
        
        
        String[] acceptableValues = {"Yo diría que sí", "Últimamente no", "No sé"}; //nombres de las opciones
        String input2 = (String)JOptionPane.showInputDialog(null,
                "¿Es usted feliz?",
                "Pregunta 2 - Felicidad",
                2,
                null,
                acceptableValues,
                acceptableValues[1]);
                
    }
}
