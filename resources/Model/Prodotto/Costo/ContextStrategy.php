<?php
/**
 * The Context where the Strategy is employed.
 */
class Model_Prodotto_Costo_ContextStrategy
{
    /**
     *
     * @var Model_Builder_Prodotto_Parts_Product
     */
    private $_product;
    
    /**
     *  The context to use/work (Default is Anagrafica)
     * @var string
     */
    private $_context = "Anagrafica";
    
    /**
     * init the Strategy with the Product element
     * @param Model_Builder_Prodotto_Parts_Product $elements
     */
    public function __construct(Model_Builder_Prodotto_Parts_Product $product)
    {
        $this->_product = $product;
    }

    
    public function setContext($context)
    {
        $this->_context = $context;
    }
    
    
    
    
    /*
    * Overloading
    * __call
    */
    public function __call ( $method, $args )
    {
        // controllo esistenza metodo
        if( method_exists( $this, $method ) )
        {
            call_user_func_array(array($this, $method), $args);
        } else {
            try {
                // get Strategy class
                $obj = $this->_getStrategyClass($method);
                return $obj->calculate($this->_product, $args);
                
            } catch (MyFw_Exception $exc) {
                $exc->displayError();
            }
        }
    }


/**************
 * PRIVATE
 **************/

    // get instance of Strategy Class
    private function _getStrategyClass($method)
    {
        // create Class name in base a metodo e context
        $className = "Model_Prodotto_Costo_Strategy_".$method . $this->_context;
        // controllo se la classe esiste
        if(class_exists($className))
        {
            return new $className();
        } else {
            throw new MyFw_Exception("The method _getStrategyClass (". $method . $this->_context . ") NON esiste!");
        }
    }


}

