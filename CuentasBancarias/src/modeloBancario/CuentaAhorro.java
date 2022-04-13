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
public class CuentaAhorro extends Cuenta{
    protected double interesVariable;
    final protected double saldoMinimo = 500.00;   
   

    public CuentaAhorro(double interesVariable, int numeroCuenta, double saldo, Cliente titular) {
        super(numeroCuenta, saldo, titular);
        this.interesVariable = interesVariable;
    }
    
    @Override
    public double retirar(double retirada){
        try{
            if (retirada <= saldoMinimo)
                saldo = saldo - retirada;
            else{
                saldo = saldo;
            }
        }
        catch (Exception ex){
        }          
        
        return saldo;
    }
    
    @Override
    public double actualizarSaldo(){
        saldo = saldo * interesVariable;
        return saldo;
    }
    
    
}
