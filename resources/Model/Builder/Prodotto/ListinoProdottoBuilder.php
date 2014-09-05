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
    public function addProdotto()
    {
        parent::addProdotto();
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
    public function addListino(){
        parent::addListino();
    }    
    
    /**
     * @return void
     */
    public function addOrdine() { }

    /**
     * @return void
     */
    public function addUserOrdine() { }
    
    /**
     * @return void
     */
    public function createProdotto()
    {
        $this->prodotto = new Model_Builder_Prodotto_Parts_Listino();
    }

}
