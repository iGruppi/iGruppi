<?php
/**
 * Description of UdM
 * 
 * @author gullo
 */
class Model_Prodotto_UdM {
    
    const _BOTTIGLIA  = "Bottiglia";
    const _CONFEZIONE = "Confezione";
    const _PEZZO      = "Pezzo";
    const _KG         = "Kg";
    const _LITRO      = "Litro";
    
    static private $_arUdM = array(
        self::_BOTTIGLIA  => 'Bottiglia',
        self::_CONFEZIONE => 'Confezione',
        self::_PEZZO      => 'Pezzo',
        self::_KG         => 'Kg',
        self::_LITRO      => 'Litro'
    );
    
    static private $_arUdMWithMultip = array(
        self::_PEZZO      => array('label' => 'Pezzi', 'ndec' => '0'),
        self::_KG         => array('label' => 'Kg',    'ndec' => '2'),
        self::_LITRO      => array('label' => 'Litri', 'ndec' => '2')
    );

    public static function getArUdm(){
        return self::$_arUdM;
    }
    
    public static function getArWithMultip()
    {
        return self::$_arUdMWithMultip;
    }
}