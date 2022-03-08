/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package ed_u3_dvg;

/**
 *
 * @author DaniValle
 */
public class notas {
    
    private float examenTeorico;
    private float examenPracticas;
    private boolean practicaConvalidada;

    public notas(float examenTeorico, float examenPracticas, boolean practicaConvalidada) {
        this.examenTeorico = examenTeorico;
        this.examenPracticas = examenPracticas;
        this.practicaConvalidada = practicaConvalidada;
    }

    public float getExamenTeorico() {
        return examenTeorico;
    }

    public float getExamenPracticas() {
        return examenPracticas;
    }

    public boolean isPracticaConvalidada() {
        return practicaConvalidada;
    }

    public void setExamenTeorico(float examenTeorico) {
        this.examenTeorico = examenTeorico;
    }

    public void setExamenPracticas(float examenPracticas) {
        this.examenPracticas = examenPracticas;
    }

    public void setPracticaConvalidada(boolean practicaConvalidada) {
        this.practicaConvalidada = practicaConvalidada;
    }
    
    
    
        
    public float calificaciones(float examenTeorico, float examenPracticas, boolean practicaConvalidada){

         if(examenTeorico >= 3.5F){
               if(practicaConvalidada){
                     return examenTeorico+1.5F;
               }
               else{
                     return examenTeorico+examenPracticas;
              }
         }
         else{
               return examenTeorico;
         }

    }
    }
    
