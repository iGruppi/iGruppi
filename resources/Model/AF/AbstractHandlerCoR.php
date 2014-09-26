<?php

/**
 * Description of AbstractHandlerCoR
 * 
 * @author Davide Gullo <gullo at m4ss.net>
 * 
 */
abstract class Model_AF_AbstractHandlerCoR {
    
    private $_cor;
    
    public function setCoR(Model_AF_AbstractCoR $cor)
    {
        $this->_cor = $cor;
        return $this;
    }
    
    public function getCoR()
    {
        return $this->_cor;
    }
    
}
