<?php

/**
 * This is the Factory to manage the ANAGRAFICA PRODOTTI
 * 
 * @author gullo
 */
class Model_AnagraficaProdotti extends Model_AF_AbstractCoR 
{
    /**
     * set AnagraficaFactory as factory class
     * @return void
     */
    public function __construct() {
        $this->factoryClass = new Model_AF_AnagraficaFactory();
    }

    
}
