<?php
/**
 * This builds PRODOTTO for LISTINO
 */
class Model_Builder_Prodotto_ListinoProdottoBuilder 
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
    public function addDatiOrdine() { }
    
    /**
     * @return void
     */
    public function createProdotto()
    {
        $this->prodotto = new Model_Builder_Prodotto_Parts_Listino();
    }

}
