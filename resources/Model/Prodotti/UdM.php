<?php
/**
 * Description of UdM
 * 
 * @author gullo
 */
class Model_Prodotti_UdM {
    
    const _CONFEZIONE = "Confezione";
    const _PEZZO      = "Pezzo";
    const _KG         = "Kg";
    const _LITRO      = "Litro";
    
    private $_arUdM = array(
        self::_CONFEZIONE => 'Confezione',
        self::_PEZZO      => 'Pezzo',
        self::_KG         => 'Kg',
        self::_LITRO      => 'Litro'
    );
    
    private $_arUdMWithMultip = array(
        self::_PEZZO      => array('label' => 'Pezzi', 'step' => '1'),
        self::_KG         => array('label' => 'Kg',    'step' => '0.01'),
        self::_LITRO      => array('label' => 'Litri', 'step' => '0.01')
    );

    function getArUdm(){
        return $this->_arUdM;
    }
    
    function getArWithMultip()
    {
        return $this->_arUdMWithMultip;
    }
}