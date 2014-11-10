<?php

/**
 * This is the Debugger of the Observers
 * 
 * @author Davide Gullo <gullo at m4ss.net>
 * 
 */
class Model_Prodotto_Observer_Debugger {
    
    /**
     * Enable DEBUG
     * @return void
     */
    static public function enable()
    {
        Model_Prodotto_Observer_Anagrafica::getInstance()->enableDebug();
        Model_Prodotto_Observer_Listino::getInstance()->enableDebug();
        Model_Prodotto_Observer_Ordine::getInstance()->enableDebug();
    }
    
}
