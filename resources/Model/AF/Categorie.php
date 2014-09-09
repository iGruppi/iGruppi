<?php
/**
 * This is the Abstract Product for CATEGORIE
 */
abstract class Model_AF_Categorie implements Model_AF_AbstractProductInterface
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
     * add Element to the Composite TREE
     * @param stdClass $v
     */
    private function _addElement(stdClass $v)
    {
        $cat = $this->_initCat($v);
        if(!is_null($cat)) {
            if(isset($v->idprodotto)) {
                // ADD PRODUCTS to Cat
                $cat->addIdProdotto($v->idprodotto);
            }
            $subcat = $this->_initSubCat($v, $cat);
            if(!is_null($subcat) && isset($v->idprodotto)) {
                // ADD PRODUCTS to SubCat
                $subcat->addIdProdotto($v->idprodotto);
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
     * @param Model_Produttori_Prodotti_Categorie_CategoryElement $cat
     * @return mixed (null|Model_Produttori_Prodotti_Categorie_CategoryElement)
     */
    private function _initSubCat(stdClass $v, Model_Produttori_Prodotti_Categorie_CategoryElement $cat)
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
