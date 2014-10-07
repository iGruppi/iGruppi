<?php
/**
 * Description of IVA
 * 
 * @author gullo
 */
class Model_Prodotto_IVA {
    
    private $_arIVA = array(
        '0'     => "Non voglio gestire l'IVA",
        '4'     => '4 %',
        '10'    => '10 %',
        '22'    => '22%'
    );
    
    function getArIVA(){
        return $this->_arIVA;
    }
    
}