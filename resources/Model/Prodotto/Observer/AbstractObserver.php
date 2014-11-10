<?php

/**
 * This is the Abstract Subject for Prodotti Observer
 * 
 * @author Davide Gullo <gullo at m4ss.net>
 * 
 */
abstract class Model_Prodotto_Observer_AbstractObserver
{
    /**
     * Array of the updated Prodotto
     * @var array
     */
    protected $_updated = array();
    
    /**
     * Enable/Disable debugger
     * @var bool
     */
    protected $_debug = false;


    public function update(Model_Prodotto_Mediator_MediatorInterface $prodotto)
    {
        $id = spl_object_hash($prodotto);
        if(!isset($this->_updated[$id]))
        {
            $this->_updated[$id] = $prodotto;
            
            // DEBUG
            if($this->_debug)
            {
                echo "<h1>Prodotto Id: " . $prodotto->getIdProdotto() . "</h1>";
                echo "<h2>".get_called_class()."</h2>";
                Zend_Debug::dump( $prodotto->getValues() );
            }
        }
    }
    
    /**
     * Get the array of the updated collegaues
     * @return array
     */
    public function getUpdated()
    {
        return $this->_updated;
    }
    
    
    /**
     * Enable DEBUG
     * @return void
     */
    public function enableDebug()
    {
        $this->_debug = true;
    }
    
}
