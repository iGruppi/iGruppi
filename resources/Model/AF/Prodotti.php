<?php
/**
 * This is the Abstract Product for PRODOTTI
 */
abstract class Model_AF_Prodotti implements Model_AF_AbstractProductInterface
{
    /**
     * @var array
     */
    private $_prodotti = array();
    /**
     * @var array
     */
    private $_categories = null;
    

    /**
     * add Products by an array of data
     * @param array $listProd
     */
    public function initDatiByObject($listProd)
    {
        if(count($listProd) > 0)
        {
            foreach ($listProd as $values)
            {
                // It calls the addProdotto method of the superclass
                $this->addProdotto($values);
            }
        }
    }
    
    /**
     * get the number of the products available
     * @return int
     */
    public function count()
    {
        return count($this->_prodotti);
    }

    /**
     * return Prodotto in base a $idprodotto
     * 
     * @param mixed $idprodotto
     * @return mixed (null | Model_Builder_Prodotto_Parts_Product)
     */
    public function getProdottoById($idprodotto)
    {
        if(isset($this->_prodotti[$idprodotto]))
        {
            return $this->_prodotti[$idprodotto];
        }
        return null;
    }

    /**
     * used from the parent class to ADD products to the array
     * It MUST be protected because the parent class has to instantiate the Builder before adding it
     * @param Model_Builder_Prodotto_Parts_Product $prodotto
     */
    protected function _setProdotto(Model_Builder_Prodotto_Parts_Product $prodotto)
    {
        $this->_prodotti[$prodotto->getIdProdotto()] = $prodotto;
    }

    /**
     * called by the superclass for some shared steps
     * @param stdClass $values
     */
    protected function addProdotto(stdClass $values) {
        // set Cat and SubCat
        $this->_addToCategoryTree($values);
    }

    /**
     * get array Products
     * @return array
     */
    public function getProdotti()
    {
        return $this->_prodotti;
    }
    /**
     * build the composite pattern for categories
     * 
     * @return Model_Produttori_Prodotti_Categorie
     */
    public function getCategorie()
    {
        if(is_null($this->_categories))
        {
            $this->_categories = new Model_Produttori_Prodotti_Categorie(0, "Categorie");
        }
        return $this->_categories;
    }
        
    /**
     * add a leaf (category) to the composite (categories tree)
     * @param stdClass $v Object of Product fields
     * @return void
     */
    private function _addToCategoryTree(stdClass $v)
    {
        if(isset($v->idcat) && isset($v->idsubcat)) {
            $catObj = $this->getCategorie();
            if(is_null($catObj->getChild($v->idcat))) {
                // ADD CATEGORY Element
                $catObj->add(new Model_Produttori_Prodotti_Categorie($v->idcat, $v->categoria));
            }
            $cat = $catObj->getChild($v->idcat);
            if(is_null($cat->getChild($v->idsubcat))) {
                // ADD SUB-CATEGORY Groups
                $cat->add(new Model_Produttori_Prodotti_Categorie_SubcatElement($v->idsubcat, $v->categoria_sub));
            }
            $subcat = $cat->getChild($v->idsubcat);
            // ADD PRODUCTS to Cat and SubCat
            $cat->addIdProdotto($v->idprodotto);
            $subcat->addIdProdotto($v->idprodotto);
        }
    }
    
}
