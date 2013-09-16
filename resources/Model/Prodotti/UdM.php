<?php
/**
 * Description of UdM
 * 
 * @author gullo
 */
class Model_Prodotti_UdM {
    
    private $_arUdM = array(
        'Confezione' => 'Confezione',
        'Pezzo'      => 'Pezzo',
        'Kg'         => 'Kg',
        'Litro'      => 'Litro'
    );
    
    function getArUdm(){
        return $this->_arUdM;
    }
    
}