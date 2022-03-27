import org.junit.Assert;
import org.junit.*;

import ed_u3_dvg.notas;


public class JUnitTest {
    
    static notas notas1;
    
    public JUnitTest() {
    }
    
    @BeforeClass
    public static void setUpClass() {
        notas1 = new notas (5.0F, 2.5F, false);
        
    }
    
    @AfterClass
    public static void tearDownClass() {
    }
    
    @Before
    public void setUp() {
    }
    
    @After
    public void tearDown() {
    }

    // TODO add test methods here.
    // The methods must be annotated with annotation @Test. For example:
    //
 @Test
  public void pruebasValidas() {
      Assert.assertEquals(7.5F, notas1.calificaciones(5.0F, 2.5F, false), 0); //caso 1
      Assert.assertEquals(6.5F, notas1.calificaciones(5.0F, 2.5F, true), 0); // caso 2
  }
  
  @Test
  public void pruebasNoValidas () {
      Assert.assertEquals(-1.2F, notas1.calificaciones(-1.2F, 2.5F, true), 0);     // caso 3
      Assert.assertEquals(9.0F, notas1.calificaciones(7.5F, 2.5F, true), 0);      // caso 4
      Assert.assertEquals(6.5F, notas1.calificaciones(5.0F, -2.8F, true), 0);     // caso 5
      Assert.assertEquals(6.5F, notas1.calificaciones(5.0F, 4.3F, true), 0);     // caso 6
      
      //Assert.assertEquals(ERROR, notas1.calificaciones(5.0F, 2.5F, "asdf35"), 0);   //caso 7, el tipo no es ompatible asi que no puede ejecutarse
      
  }
}
