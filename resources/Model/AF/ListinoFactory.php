<?php
/**
 * This is a Concrete Factory for LISTINO
 */
class Model_AF_ListinoFactory extends Model_AF_AbstractFactory
{
    /**
     * Creates DATI component
     * @return Dati
     */
    public function createDati() 
    {
        return new Model_AF_Listino_Dati();
    }

    /**
     * Creates GRUPPI component
     * @return Gruppi
     */
    public function createGruppi()
    {
        return new Model_AF_Listino_Gruppi();
    }

    /**
     * Creates CATEGORIE component
     * @return Categorie
     */
    public function createCategorie()
    {
        return new Model_AF_Listino_Categorie();
    }

    /**
     * Creates PRODOTTI component
     * @return Prodotti
     */
    public function createProdotti()
    {
        return new Model_AF_Listino_Prodotti();
    }

}
