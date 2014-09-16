<?php
/**
 * This is the Factory to manage the ORDINE
 * 
 * @author gullo
 */
class Model_Ordini_Ordine extends Model_AF_AbstractManipulator 
{

    /**
     * Create Listino Factory
     */
    public function __construct() {
        parent::create(new Model_AF_OrdineFactory());
    }
            
    /*
    * Overloading, it try to call methods in the State Pattern
    * __call
    */
    public function __call ( $method, $args )
    {
        // controllo esistenza metodo
        if( method_exists( $this, $method ) )
        {
            call_user_func_array(array($this, $method), $args);
        } else {
            // get Order
            $order = Model_Ordini_State_OrderFactory::getOrder($this->getDati()->getValues());
            try {
                return $order->$method();

            } catch (MyFw_Exception $exc) {
                $exc->displayError();
            }
        }
    }
    
    
    
/*  **************************************************************************
 *  PERMISSION
 */    
    /**
     * 
     * @todo
     */
    private function isReferenteOrdine()
    {
        
        return true;
    }
    
    public function canManageOrdine()
    {
        return $this->isReferenteOrdine();
    }
    
    public function canManageCondivisione()
    {
        return $this->isReferenteOrdine();
    }

    
/*  **************************************************************************
 *  SAVE CHANGES TO DB
 */    
    
    public function save()
    {
        // save Dati
        $res1 = $this->getDati()->saveToDB();
        // save Groups
        $res2 = $this->getGroups()->saveToDB();
        
        return ($res1 && $res2);
    }
    
}
