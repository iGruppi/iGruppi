<?php
/**
 * This builds PRODOTTO for ANAGRAFICA Prodotti
 */
class Model_Builder_Prodotto_AnagraficaProdottoBuilder 
    extends Model_Builder_Prodotto_BuilderInterface
{

    /**
     * @return void
     */
    public function addDatiProdotto()
    {
        parent::addDatiProdotto();
    }
    
    /**
     * @return void
     */
    public function addCategorie() {
        parent::addCategorie();
    }
    
    /**
     * @return void
     */
    public function addDatiListino(){ }    
    
    /**
     * @return void
     */
    public function addDatiOrdine() { }

    /**
     * @return void
     */
    public function createProdotto()
    {
        $this->prodotto = new Model_Builder_Prodotto_Parts_Anagrafica();
    }

}
