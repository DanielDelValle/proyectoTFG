package Clases;

public class MetodosEjemplo2 {

    public static void main(String[] args) {
        //creamos instancia de la clase MetodosEjemplo
        MetodosEjemplo ejemplo = new MetodosEjemplo();//SUMARÁ VALORES X DEFECTO(0,0);SIN CONSTR. VACIO, DARÍA ERROR. 
        //MetodosEjemplo ejemplo = new MetodosEjemplo(1,2,3,4); EN ESTE CASO, NO HACE FALTA CONSTRUCTOR. VACIO (HAY ARGS)
        //var result = ejemplo.sumar(17, 34);  // sirve "var" porque se deduce (infiere) de los argumentos
        var resultVoid = ejemplo.sumar();
        System.out.println("resultVoid = " + resultVoid);
        
        ejemplo.a = 9;              //asi podemos modificar los valores sin acceder al constructor (es decir, instancia a instancia)
        ejemplo.b = 16;             //no es lo más elegante, ya que modifica los valores tras crear instancia. En java, mejor directamente alcrear (con args)
        
        var resultArgs = ejemplo.sumar();        
        System.out.println("resultArgs = " + resultArgs);
        
        MetodosEjemplo ejemplo2 = new MetodosEjemplo(2,3,7,8);//como tenemos constructor con args, podemos directamente meterlos
        //var result3 = ejemplo2.multiplicar();     Esto podemos ahorrarlo, si no necesitamos guardar la variable            
        System.out.println("result3 = " + ejemplo2.multiplicar());// el metodo multiplicar usara c y d, osea 5x6. 
        
        
    }
}
