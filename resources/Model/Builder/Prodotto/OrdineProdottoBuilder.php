<?php
/**
 * This builds PRODOTTO for ORDINI
 */
class Model_Builder_Prodotto_OrdineProdottoBuilder 
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
    public function addCategories() {
        parent::addCategories();
    }
    
    /**
     * @return void
     */
    public function addDatiListino(){
        parent::addDatiListino();
    }    
    
    /**
     * @return void
     */
    public function addDatiOrdine() { 
        parent::addDatiOrdine();
    }

    /**
     * @return void
     */
    public function createProdotto()
    {
        $this->prodotto = new Model_Builder_Prodotto_Parts_Ordine();
    }

}
