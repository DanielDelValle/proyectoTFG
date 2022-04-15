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

    public CuentaAhorro() {
        /*he añadido constructores sin parametros para poder declarar un objeto
        de cada tipo y asi usarlos de forma global en todos los metodos*/
    }
   

    public CuentaAhorro(double interesVariable, int numeroCuenta, double saldo, Cliente titular) {
        super(numeroCuenta, saldo, titular);
        this.interesVariable = interesVariable;
    }
    
    @Override
    public void retirar(double retirada){
        try{
            this.setSaldo(saldo - retirada);

        }
        
        catch (Exception ex){
        }          
        
    }

    @Override
    public void actualizarSaldo(){
        this.setSaldo(saldo+(saldo * interesVariable)/100);
    }
    
    public double getInteresVariable() {
        return interesVariable;
    }

    public void setInteresVariable(double interesVariable) {
        this.interesVariable = interesVariable;
    }
    

    
    
}
