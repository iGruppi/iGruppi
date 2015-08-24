<?php
/**
 * class AbstractFactory
 * 
 * @author Davide Gullo <gullo at m4ss.net>
 * 
 */
abstract class Model_AF_AbstractFactory
{
    /**
     * Creates DATI component
     * @return Dati
     */
    abstract public function createDati();

    /**
     * Creates GRUPPI component
     * @return Gruppi
     */
    abstract public function createGruppi();

    /**
     * Creates PRODOTTI component
     * @return Prodotti
     */
    abstract public function createProdotti();
}
