<?php
/**
 * This is the Client for Categorie Composite pattern
 */
class Model_Categorie extends Model_AF_AbstractHandlerCoR
{
    /**
     * @var Model_Categorie
     */
    protected $_categorie = null;

    /**
     * @param mixed (stdClass|array) $values
     * @return void
     */
    public function initCategorie_ByObject($values)
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
     * get Root Categorie composite object
     * @return Model_Categorie
     */
    public function getRoot()
    {
        if(is_null($this->_categorie))
        {
            $this->_categorie = new Model_Categorie_CatElement(0, "Root");
        }
        return $this->_categorie;
    }
    
    public function getProduttoriList()
    {
        $ar = array();
        $iterator = new RecursiveIteratorIterator( 
                            $this->getRoot()->createIterator(),
                                RecursiveIteratorIterator::SELF_FIRST,
                                RecursiveIteratorIterator::CATCH_GET_CHILD
                );
        foreach($iterator AS $child) {
            if( $child instanceof Model_Categorie_ProduttoreElement) {
                $ar[] = $child->getDescrizione();
            }
        }
        return array_unique($ar);
    }
    
    public function getListaDescrizioniCategorie()
    {
        $ar = array();
        foreach($this->getRoot()->createIterator() AS $categoria)
        {
            $ar[] = $categoria->getDescrizione();
        }
        return $ar;
    }
    /**
     * @todo  
     * It does not work here because it needs to call 'getProdottoById' method that here does not exists!
     */
    public function getProdottiWithCategoryArray()
    {
        $iterator = new RecursiveIteratorIterator( 
                            $this->getRoot()->createIterator(),
                                RecursiveIteratorIterator::SELF_FIRST,
                                RecursiveIteratorIterator::CATCH_GET_CHILD
                );
        if($iterator->getSubIterator()->count()) {
            foreach($iterator AS $child) {
                if( $child instanceof Model_Categorie_ProdottoElement) {
                    $child->setProdotto( $this->getCoR()->getProdottoById($child->getId()) );
                }
            }
            return $iterator->getSubIterator()->getArrayCopy();
        }
        return array();   
    }
    
    
/*  *************************************************************
 *  PRIVATE METHODS to BUILD Elements
 */
    
    /**
     * add Element to the Composite TREE
     * @param stdClass $v values
     */
    private function _addElement(stdClass $v)
    {
        // ADD Cat
        $catRoot = $this->getRoot();
        $cat = $this->_initCat($v, $catRoot);
        if(!is_null($cat)) {
            // ADD SubCat to Cat
            $subcat = $this->_initSubCat($v, $cat);
            // ADD PRODUCTS & PRODUTTORE to SubCat or Cat (ONLY if they exist)
            if(!is_null($subcat)) 
            {
                $this->_initProdotto($v, $subcat);
                $this->_initProduttore($v, $subcat);
            } else {
                $this->_initProdotto($v, $cat);
                $this->_initProduttore($v, $cat);
            }
        }
    }
    
    /**
     * add CAT to the Composite TREE
     * @param stdClass $v values
     * @return null
     */
    private function _initCat(stdClass $v, Model_Categorie_Element $cat)
    {
        if(isset($v->idcat)) {
            if(is_null($cat->getChild($v->idcat))) {
                // ADD CATEGORY Element
                $cn = isset($v->categoria) ? $v->categoria : "";
                $cat->add(new Model_Categorie_CatElement($v->idcat, $cn));
            }
            return $cat->getChild($v->idcat);
        }
        return null;
    }
    
    /**
     * add SUBCAT to the Composite tree
     * @param stdClass $v values
     * @param Model_Categorie_Element $cat
     * @return mixed (null|Model_Categorie_Element)
     */
    private function _initSubCat(stdClass $v, Model_Categorie_Element $cat)
    {
        if(isset($v->idsubcat)) {
            if(($e = $cat->getChild($v->idsubcat)) === null || !$e instanceof Model_Categorie_SubcatElement) {
                // ADD SUB-CATEGORY Element
                $scn = isset($v->categoria_sub) ? $v->categoria_sub : "";
                $cat->add(new Model_Categorie_SubcatElement($v->idsubcat, $scn));
            }
            return $cat->getChild($v->idsubcat);
        }
        return null;
    }
    
    /**
     * add PRODOTTO to the Composite tree
     * @param stdClass $v values
     * @param Model_Categorie_Element $subcat
     * @return mixed (null|Model_Categorie_Element)
     */
    private function _initProdotto(stdClass $v, Model_Categorie_Element $subcat)
    {
        if(isset($v->idprodotto)) {
            if(($e = $subcat->getChild($v->idprodotto)) === null || !$e instanceof Model_Categorie_ProdottoElement) {
                // ADD PRODOTTO Element
                $scp = isset($v->descrizione_prodotto) ? $v->descrizione_prodotto : "";
                $subcat->add(new Model_Categorie_ProdottoElement($v->idprodotto, $scp));
            }
            return $subcat->getChild($v->idprodotto);
        }
        return null;
    }
    
    /**
     * add PRODUTTORE to the Composite tree
     * @param stdClass $v values
     * @param Model_Categorie_Element $subcat
     * @return mixed (null|Model_Categorie_Element)
     */
    private function _initProduttore(stdClass $v, Model_Categorie_Element $subcat)
    {
        if(isset($v->idproduttore)) {
            if(is_null($subcat->getChild($v->idproduttore))) {
                // ADD SUB-CATEGORY Element
                $scp = isset($v->ragsoc_produttore) ? $v->ragsoc_produttore : "";
                $subcat->add(new Model_Categorie_ProduttoreElement($v->idproduttore, $scp));
            }
            return $subcat->getChild($v->idproduttore);
        }
        return null;
    }
            
}
