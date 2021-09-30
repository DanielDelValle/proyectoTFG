package Basico;


import java.util.Scanner;

public class SwitchLarge {

    public static void main(String[] args) {

        var sc = new Scanner(System.in);
        System.out.println("Por favor, escoja su opcion: " + "\n" + "1 - Menu    2 - Configuracion   3-Salir");
        var opcion = sc.nextInt();
        String respuesta = null;
        String ErroR = null;
        String info = null;
        String info2 = null;
        switch (opcion) {
            case 1:
                respuesta = "Está usted en: MENU PRINCIPAL";
                System.out.println(respuesta);
                System.out.println("\t Por favor, escoja su opcion: " + "\n \t\t" + "11 - Perfil Usuario    12 - Controles   13-Pagos");
                var opcion2 = sc.nextInt();
                switch (opcion2) {
                    case 11:
                        info = "Está usted en:: PERFIL USUARIO";
                        System.out.println(info);
                        break;

                    case 12:
                        info = "Está usted en: CONTROLES";
                        System.out.println(info);
                        break;

                    case 13:
                        info = "Está usted en: PAGOS";
                        System.out.println(info);
                        break;
                }
                break;
            case 2:
                respuesta = "Está usted en: CONFIGURACION";
                System.out.println(respuesta);
                System.out.println("\t Por favor, escoja su opcion: " + "\n \t\t" + "21 - Audio    22 - Imagen   23-Wifi");
                var opcion3 = sc.nextInt();
                switch (opcion3) {
                    case 21:
                        info2 = "Está usted en: AUDIO";
                        System.out.println(info2);
                        break;

                    case 22:
                        info2 = "Está usted en: IMAGEN";
                        System.out.println(info2);
                        break;

                    case 23:
                        info2 = "Está usted en: WIFI";
                        System.out.println(info2);
                        break;
                }
                break;
            case 3:
                respuesta = "Hasta Pronto!!";
                System.out.println(respuesta);
                break;
            default:
                ErroR = "Por favor, escoja una de las 3 opciones disponibles y pulse enter";
                System.out.println(ErroR);

        }

    }
}