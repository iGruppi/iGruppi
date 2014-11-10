<?php

/**
 * Description of Ordine
 * 
 * @author Davide Gullo <gullo at m4ss.net>
 * 
 */
class Model_Prodotto_Observer_Ordine
    extends Model_Prodotto_Observer_AbstractObserver
{
    
    static protected $_instance = null;
    
    /**
     * SINGLETON
     * @return Model_Prodotto_Observer_Listino
     */
    static public function getInstance()
    {
        if(is_null(self::$_instance))
        {
            self::$_instance = new Model_Prodotto_Observer_Ordine();
        }
        return self::$_instance;
    }
    
}
