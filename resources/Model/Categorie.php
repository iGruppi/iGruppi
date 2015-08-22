<?php
/**
 * This is the Client for Categorie Composite pattern
 */
class Model_Categorie extends Model_AF_AbstractHandlerCoR
{
    /**
     * @var Model_Categorie_CatElement
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
     * IF not exists it will init a new Model_Categorie_CatElement for ROOT
     * @return Model_Categorie_CatElement
     */
    public function getRoot()
    {
        if(is_null($this->_categorie))
        {
            $this->_categorie = new Model_Categorie_CatElement(0, "Root");
        }
        return $this->_categorie;
    }
    
    /**
     * Recursive Iterator to get PRODUTTORI LIST
     * @return array
     */
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
    
    /**
     * Recursive Iterator to get CATEGORIE LIST
     * @return array
     */
    public function getListaDescrizioniCategorie()
    {
        $ar = array();
        // in the first level of root we have only CATEGORIES
        foreach($this->getRoot()->createIterator() AS $categoria)
        {
            $ar[] = $categoria->getDescrizione();
        }
        return $ar;
    }
    /**
     * Recursive Iterator to get PRODOTTI in CATEGORY array
     * @return array  
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
        if(!is_null($cat))
        {
            // I produttori sono solo in categorie
            $this->_initProduttore($v, $cat);
            
            // ADD SubCat to Cat
            $subcat = $this->_initSubCat($v, $cat);
            if (!is_null($subcat))
            {
                // I prodotti sono solo in sottocategorie
                $this->_initProdotto($v, $subcat);
            }
        }
    }
    
    /**
     * add CAT to the Composite TREE
     * @param stdClass $v values
     * @return mixed (null|Model_Categorie_CatElement)
     */
    private function _initCat(stdClass $v, Model_Categorie_CatElement $root)
    {
        if(isset($v->idcat)) {
            $cat = $root->getCatById($v->idcat);
            if(is_null($cat)) {
                // ADD CATEGORY Element
                $cn = isset($v->categoria) ? $v->categoria : "";
                $cat = new Model_Categorie_CatElement($v->idcat, $cn);
                $root->add($cat);
            }
            return $cat;
        }
        return null;
    }
    
    /**
     * add SUBCAT to the Composite tree
     * @param stdClass $v values
     * @param Model_Categorie_CatElement $cat
     * @return mixed (null|Model_Categorie_SubcatElement)
     */
    private function _initSubCat(stdClass $v, Model_Categorie_CatElement $cat)
    {
        if(isset($v->idsubcat)) {
            $subcat = $cat->getSubCatById($v->idsubcat);
            if(is_null($subcat)) {
                // ADD SUB-CATEGORY Element
                $scn = isset($v->categoria_sub) ? $v->categoria_sub : "";
                $subcat = new Model_Categorie_SubcatElement($v->idsubcat, $scn);
                $cat->add($subcat);
            }
            return $subcat;
        }
        return null;
    }
    
    /**
     * add PRODOTTO to the Composite tree
     * @param stdClass $v values
     * @param Model_Categorie_Element $subcat
     */
    private function _initProdotto(stdClass $v, Model_Categorie_SubcatElement $subcat)
    {
        if(isset($v->idprodotto)) {
            if (is_null($subcat->getProdottoById($v->idprodotto))) {
                // ADD PRODOTTO Element
                $scp = isset($v->descrizione_prodotto) ? $v->descrizione_prodotto : "";
                $subcat->add(new Model_Categorie_ProdottoElement($v->idprodotto, $scp));
            }
        }
    }
    
    /**
     * add PRODUTTORE to the Composite tree
     * @param stdClass $v values
     * @param Model_Categorie_Element $subcat
     */
    private function _initProduttore(stdClass $v, Model_Categorie_CatElement $subcat)
    {
        if(isset($v->idproduttore)) {
            if (is_null($subcat->getProduttoreById($v->idproduttore))) {
                // ADD PRODUTTORE Element
                $scp = isset($v->ragsoc_produttore) ? $v->ragsoc_produttore : "";
                $subcat->add(new Model_Categorie_ProduttoreElement($v->idproduttore, $scp));
            }
        }
    }
            
}
