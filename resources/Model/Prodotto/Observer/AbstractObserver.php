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
    
    public function update(Model_Prodotto_Mediator_MediatorInterface $prodotto)
    {
        $id = spl_object_hash($prodotto);
        if(!isset($this->_updated[$id]))
        {
            $this->_updated[$id] = $prodotto;
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
}
