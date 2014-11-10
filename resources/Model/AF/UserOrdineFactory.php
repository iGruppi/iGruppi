<?php
/**
 * This is a Concrete Factory for USER-ORDINE level
 */
class Model_AF_UserOrdineFactory extends Model_AF_AbstractFactory
{
    /**
     * Creates DATI component
     * @return Dati
     */
    public function createDati()
    {
        return new Model_AF_Ordine_Dati();
    }

    /**
     * Creates GRUPPI component
     * @return Gruppi
     */
    public function createGruppi()
    {
        return new Model_AF_Ordine_Gruppi();
    }

    /**
     * Creates PRODOTTI component
     * @return Prodotti
     */
    public function createProdotti()
    {
        return new Model_AF_Ordine_UserProdotti();
    }

}
