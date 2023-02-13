
       <?php 
        //1: declaro un array vacío llamado lista -> 2: establezco limites del bucle for, y decremento constante de 3 uds -> 3: cada valor se añadira a lista -> 
        // 4:se retorna la lista
          function generarArray($valor){
              $lista = [];    
            	for($valor;$valor>=0;$valor-=3){
                  array_push($lista, $valor);               
            }
            return $lista;
          }
          //el argumento de esta funcion será un array; por ello, lo recorro con un foreach y lo concateno con etiquetas de tabla html, para obtener la salida deseada.
          function tabla($valores){
            
            $salida = "<table><tr>";
            foreach ($valores as $fila) {
              $salida = $salida. "<td>" . $fila . "</td>";          
              } 
              $salida = $salida . "</tr></table>"; 
              return $salida; 

            } 
      
           // echo gettype(generarArray(10)) . "  ";
           // echo gettype(tabla(generarArray(10)));      //Para comprobar el tipo de archivo que retornan ambas funciones

          //echo var_dump(generarArray(10));
           echo tabla([15, 12, 9, 6, 3, 0]);
      ?>    

