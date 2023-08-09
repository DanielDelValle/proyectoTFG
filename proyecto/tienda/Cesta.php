<?php $usuario = checkSession();
class Cesta {
    protected $cesta_totales = array();  //hay mejores formas de hacer esto (podria quedarse visible al destruir la cesta)
    
    public function __construct(){
        // get the shopping cart array from the session
        $this->cesta_totales = !empty($_SESSION['cesta_totales'])?$_SESSION['cesta_totales']:NULL;
        if ($this->cesta_totales === NULL){
            // set some base values
            $this->cesta_totales = array('total_precio' => 0, 'total_prods' => 0);
        }
    }
function anadir_producto(){

        $usuario = checkSession();
        $_SESSION['cesta'] = checkCesta();
        $prod_add = array();
        //$prod_add = new ArrayObject();  PARA CREAR UN ARRAY DE OBJETOS VACIO
        $prod_add['id'] = (int)$producto['id_prod'];
        $prod_add['nombre'] = $producto['nombre'];
        $prod_add['precio'] = (float)$producto['precio'];
        $prod_add['cantidad'] = (float)1.0;
        

        if(count($_SESSION['cesta']) != 0){
            if(in_array($prod_add['id'], array_keys($_SESSION['cesta']))){
                $_SESSION['cesta'][$prod_add['id']]['cantidad'] +=1;
                $prod_add = array();
            } 

            else {
                $_SESSION['cesta'][$prod_add['id']] = $prod_add;
                $prod_add = array();
            }
        
        }else{
            $_SESSION['cesta'][$prod_add['id']] = $prod_add;
            $prod_add = array();
        }

        }  
    }