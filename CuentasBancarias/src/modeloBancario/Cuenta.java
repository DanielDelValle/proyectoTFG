/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package modeloBancario;

/**
 *
 * @author Daniel
 */
public abstract class Cuenta {
    protected int numeroCuenta; 
    protected double saldo;
    protected Cliente titular;

    
    public Cuenta() {
        numeroCuenta = 0;
        saldo = 0;
        titular = null;
    }

    public Cuenta(int numeroCuenta, double saldo, Cliente titular) {
        this.numeroCuenta = numeroCuenta;
        this.saldo = saldo;
        this.titular = titular;
    }

    public int getNumeroCuenta() {
        return numeroCuenta;
    }

    public void setNumeroCuenta(int numeroCuenta) {
        this.numeroCuenta = numeroCuenta;
        
    }

    public double getSaldo() {
        return saldo;
    }

    public void setSaldo(double saldo) {
        this.saldo = saldo;
    }

    public Cliente getTitular() {
        return titular;
    }

    public void setTitular(Cliente titular) {
        this.titular = titular;
    }
    
    public double ingresar (double ingreso){
        saldo = saldo + ingreso;
        return saldo;
    }
    public abstract void retirar(double retirada); //métodos abstractos que aquí no tienen código pero serán implementados en clases hijas
    
    public abstract void actualizarSaldo();
    
}
