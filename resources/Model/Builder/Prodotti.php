<?php

/**
 * Description of Builder_Prodotti
 * 
 * @author Davide Gullo <gullo at m4ss.net>
 * 
 */
abstract class Model_Builder_Prodotti {
    
    protected $_prodotti = array();
    protected $_categories = null;
    
    public function addProdotto(stdClass $v) { 
        // set Cat and SubCat
        $this->_addToCategoryTree($v);
    }
    
    public function count()
    {
        return count($this->_prodotti);
    }

    public function getProdottoById($idprodotto)
    {
        if(isset($this->_prodotti[$idprodotto]))
        {
            return $this->_prodotti[$idprodotto];
        }
        return null;
    }
    
    public function getProdottiByIdsubcat($idsubcat)
    {
        $arProd = array();
        if(count($this->_prodotti) > 0) {
            foreach ($this->_prodotti as $prodotto) {
                if($prodotto->getIdSubcat() == $idsubcat) {
                    $arProd[] = $prodotto;
                }
            }
        }
        return $arProd;
    }
    
    
    public function getCategoryTree()
    {
        if(is_null($this->_categories))
        {
            $this->_categories = new Model_Produttori_Prodotti_Categorie(0, "Categorie");
        }
        return $this->_categories;
    }
        
    private function _addToCategoryTree($v)
    {
        if(isset($v->idcat) && isset($v->idsubcat)) {
            $catObj = $this->getCategoryTree();
            if(is_null($catObj->getChild($v->idcat))) {
                // ADD CATEGORY Element
                $catObj->add(new Model_Produttori_Prodotti_Categorie($v->idcat, $v->categoria));
            }
            $cat = $catObj->getChild($v->idcat);
            if(is_null($cat->getChild($v->idsubcat))) {
                // ADD SUB-CATEGORY Groups
                $cat->add(new Model_Produttori_Prodotti_Categorie_SubcatElement($v->idsubcat, $v->categoria_sub));
            }
        }
    }
    
}
