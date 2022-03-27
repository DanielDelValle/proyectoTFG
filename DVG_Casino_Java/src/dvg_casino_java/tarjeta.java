/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package dvg_casino_java;

/**
 *
 * @author DaniValle
 */
public class tarjeta {
    private int id_tarjeta;
    private int creditos;
    private int tickets;

    public tarjeta(int id_tarjeta, int creditos, int tickets) {
        
        this.id_tarjeta = id_tarjeta;
        this.creditos = creditos;
        this.tickets = tickets;
    }

    public tarjeta() {
    }

    public int getId_tarjeta() {
        return id_tarjeta;
    }

    public int getCreditos() {
        return creditos;
    }

    public int getTickets() {
        return tickets;
    }

    public void setId_tarjeta(int id_tarjeta) {
        int contador = 100;
        this.id_tarjeta = id_tarjeta;
        contador +=1;
    }

    public void setCreditos(int creditos) {
        this.creditos = creditos;
    }

    public void setTickets(int tickets) {
        this.tickets = tickets;
    }
    

    
    }
