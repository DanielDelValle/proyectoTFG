
package readwrite;
import java.io.*;
import java.util.Scanner;

public class ReadWrite {

    public static void main(String[] args) throws IOException{
        //Crear la entrada de teclado, con su indicacion
        Scanner sc = new Scanner(System.in);
        System.out.println("Introduce el texto a guardar");
        String cadena = sc.nextLine();
        System.out.println("Texto guardado: " + cadena);
        
        //Crear documento fisico (fich), e instancia clase "FileWriter" (fw), y conectarlas.        
        File fich = new File("documento.txt");
        FileWriter fw = new FileWriter(fich, true);
        System.out.println("Se ha creado un fichero en la ubicación: "+ fich.getAbsolutePath());
      
        //Asignar la entrada del teclado como argumento a escribir por fw.
        fw.write(cadena + System.lineSeparator());
        fw.close();
        //-----------------------------------------------------------------------------------------------------
        //Leer del documento; de char en char con "FileReader" o de cadena en cadena con "Buffered" (es mejor).
        //a) FileWriter
        if (fich.canRead()){
            System.out.println("El archivo es legible");}
        else {
            System.out.println("El archivo no puede ser leido");
        } //pregunto al sistema si puede leer el archivo "fich"
        FileReader fr = new FileReader(fich);
        int lectura = 1; //el numero es necesario para llevar una indicacion del orden de caracteres leidos 
        while (lectura != -1) { //con esto, hasta no llegar al caracter -1 (osea el último), se ejecutara el while.
            try{
                lectura = fr.read();
            } catch (IOException ex) {
                ex.printStackTrace();
            }
            System.out.print((char)lectura);  //asi convertimos una salida numerica en String        
        }
        fr.close();    
        
        //b) Buffered
        BufferedReader bfr = new BufferedReader(new FileReader(fich));
        String linea_lectura = bfr.readLine();
        while(linea_lectura != null){
            System.out.println(linea_lectura);
            linea_lectura = bfr.readLine();
        }
        bfr.close();
    }
}
    
