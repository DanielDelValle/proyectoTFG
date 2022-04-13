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
public class CuentaCorriente extends Cuenta{    
    final protected double interesFijo = 0.15;
    
    
    public CuentaCorriente(int numeroCuenta, double saldo, Cliente titular, double interesFijo) {
        super(numeroCuenta, saldo, titular);
    }
    
    @Override
    public double actualizarSaldo(){
        saldo = saldo * interesFijo;
        return saldo;
    }
    
    @Override
    public double retirar(double retirada){
        try{
            saldo = saldo- retirada;
        }
        catch (Exception ex){
            
        }          
        
        return saldo;
    }
}
