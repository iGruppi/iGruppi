<?php
/**
 * This is a Concrete Product CATEGORIE for LISTINO
 */
class Model_AF_Listino_Categorie extends Model_AF_Categorie
{
    
    
    function getCatList() {
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
}
