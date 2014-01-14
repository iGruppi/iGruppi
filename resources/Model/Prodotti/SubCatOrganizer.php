<?php

/**
 * Description of SubCatOrganizer
 * 
 * @author gullo
 */
class Model_Prodotti_SubCatOrganizer {
    
    private $_pList;
    
    function __construct($pl) {
        $this->_pList = $pl;
    }
    
    function getListProductsCategorized() {
        $prodotti = array();
        if(count($this->_pList) > 0) {
            foreach ($this->_pList as $key => $value) {
                $prodotti[$value->idcat][$value->idsubcat][$value->idprodotto] = $value;
            }
        }
        return $prodotti;
    }
    
    function getListCategories() {
        $subCat = array();
        if(count($this->_pList) > 0) {
            foreach ($this->_pList as $key => $value) {
                $subCat[$value->idcat]["categoria"] = $value->categoria;
                $subCat[$value->idcat]["subcat"][$value->idsubcat] = $value->categoria_sub;
            }
        }
        return $subCat;
    }
    
}