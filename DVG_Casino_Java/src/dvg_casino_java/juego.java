/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package dvg_casino_java;
import dvg_casino_java.tarjeta;
/**
 *
 * @author DaniValle
 */
public class juego {
    private int precio;
    private tarjeta tar;

    public juego(int precio, tarjeta tar) {
        this.precio = precio;
        this.tar = tar;
    }

    public juego() {
    }

    public int getPrecio() {
        return precio;
    }

    public void setPrecio(int precio) {
        this.precio = precio;
    }

    public tarjeta getTar() {
        return tar;
    }

    public void setTar(tarjeta tar) {
        this.tar = tar;
    }

    public int leer tarjeta;
        return tarjeta.getId_tarjeta(tar);
    
    public void saldo(tar) {
        System.out.println("Tarjeta Numero " + tar.getId_tarjeta() + System.lineSeparator() + "Creditos : " + tar.getCreditos() + System.lineSeparator() + "Saldo Tickets : " + tar.getTickets());
    }
    
}
