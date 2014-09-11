<?php
/**
 * This is a Concrete Factory for ANAGRAFICA
 */
class Model_AF_AnagraficaFactory extends Model_AF_AbstractFactory
{
    /**
     * There is NO DATI for Anagrafica
     * @return void
     */
    public function createDati() { }

    /**
     * There is NO GROUPS for Anagrafica
     * @return Gruppi
     */
    public function createGruppi() { }

    /**
     * Creates PRODOTTI component
     * @return Prodotti
     */
    public function createProdotti()
    {
        return new Model_AF_Anagrafica_Prodotti();
    }

}
