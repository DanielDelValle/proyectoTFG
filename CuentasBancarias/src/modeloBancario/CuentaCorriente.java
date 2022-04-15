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

    public CuentaCorriente() { 
        /*he añadido constructores sin parametros para poder declarar un objeto
        de cada tipo y asi usarlos de forma global en todos los metodos*/
    }
    
    
    public CuentaCorriente(int numeroCuenta, double saldo, Cliente titular, double interesFijo) {
        super(numeroCuenta, saldo, titular);  
    }
    
    @Override
    public void actualizarSaldo(){
        this.setSaldo(saldo+(saldo * interesFijo)/100);
    }
    
    @Override
    public void retirar(double retirada){
        try{
            this.setSaldo(saldo - retirada);
        }
        catch (Exception ex){
            
        }          
 
    }
}
