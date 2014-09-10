<?php
/**
 * This is class to manage the Composite CATEGORIE pattern
 */
class Model_Builder_Categorie
{
    /**
     * @var Model_Produttori_Prodotti_Categorie
     */
    protected $_categorie = null;

    /**
     * @param mixed (stdClass|array) $values
     * @return void
     */
    public function initDatiByObject($values)
    {
        if(is_object($values)) {
            $this->_addElement($values);
        } else if(is_array($values) && count($values) > 0) {
            foreach ($values AS $v) {
                $this->_addElement($v);
            }
        }
    }
    
    /**
     * return the Categorie composite object
     * @return Model_Produttori_Prodotti_Categorie
     */
    public function getAll()
    {
        return $this->_categorie;
    }
    
    /**
     * return the category list in a String format
     * @return mixed (null|string)
     */
    public function getCatList() {
        try {
            if(!is_null($this->_categorie)) {
                $cats = $this->_categorie->getChildren();
                if(count($cats) > 0) {
                    $myCat = array();
                    foreach ($cats as $value) {
                        $myCat[] = $value->getDescrizione();
                    }
                    return implode(", ", $myCat);
                }
            }
        } catch (MyFw_Exception $exc) {
            $exc->displayError();
        }
        return null;
    }

    /**
     * render the complete array (with all fields!)
     * @return array
     */
    public function render()
    {
        return $this->_categorie->render();
    }
    
    
    
/*  *************************************************************
 *  PRIVATE METHODS
 */
    
    /**
     * add Element to the Composite TREE
     * @param stdClass $v
     */
    private function _addElement(stdClass $v)
    {
        // ADD Cat
        $cat = $this->_initCat($v);
        if(!is_null($cat)) {
            // ADD SubCat to Cat
            $subcat = $this->_initSubCat($v, $cat);
            if(!is_null($subcat)) {
                // ADD PRODUCTS to SubCat
                $this->_initProdotto($v, $subcat);
            }    
        }
    }
    
    /**
     * add CAT to the Composite TREE
     * @param stdClass $v
     * @return null
     */
    private function _initCat(stdClass $v)
    {
        if(isset($v->idcat)) {
            $catObj = $this->_getCompositeCategorie();
            if(is_null($catObj->getChild($v->idcat))) {
                // ADD CATEGORY Element
                $cn = isset($v->categoria) ? $v->categoria : "";
                $catObj->add(new Model_Produttori_Prodotti_Categorie($v->idcat, $cn));
            }
            return $catObj->getChild($v->idcat);
        }
        return null;
    }
    
    /**
     * add SUBCAT to the Composite tree
     * @param stdClass $v
     * @param Model_Produttori_Prodotti_Categorie_Element $cat
     * @return mixed (null|Model_Produttori_Prodotti_Categorie_Element)
     */
    private function _initSubCat(stdClass $v, Model_Produttori_Prodotti_Categorie_Element $cat)
    {
        if(isset($v->idsubcat)) {
            if(is_null($cat->getChild($v->idsubcat))) {
                // ADD SUB-CATEGORY Element
                $scn = isset($v->categoria_sub) ? $v->categoria_sub : "";
                $cat->add(new Model_Produttori_Prodotti_Categorie_SubcatElement($v->idsubcat, $scn));
            }
            return $cat->getChild($v->idsubcat);
        }
        return null;
    }
    
    /**
     * add PRODOTTO to the Composite tree
     * @param stdClass $v
     * @param Model_Produttori_Prodotti_Categorie_Element $subcat
     * @return mixed (null|Model_Produttori_Prodotti_Categorie_Element)
     */
    private function _initProdotto(stdClass $v, Model_Produttori_Prodotti_Categorie_Element $subcat)
    {
        if(isset($v->idprodotto)) {
            if(is_null($subcat->getChild($v->idprodotto))) {
                // ADD SUB-CATEGORY Element
                $scp = isset($v->descrizione) ? $v->descrizione : "";
                $subcat->add(new Model_Produttori_Prodotti_Categorie_ProdottoElement($v->idprodotto, $scp));
            }
            return $subcat->getChild($v->idprodotto);
        }
        return null;
    }
    
    
    
    /**
     * build the composite pattern for categories
     * 
     * @return Model_Produttori_Prodotti_Categorie
     */
    private function _getCompositeCategorie()
    {
        if(is_null($this->_categorie))
        {
            $this->_categorie = new Model_Produttori_Prodotti_Categorie(0, "Categorie");
        }
        return $this->_categorie;
    }
        
    
}
