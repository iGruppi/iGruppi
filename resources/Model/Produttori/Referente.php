<?php
/**
 * Description of Model_Produttori_Referente
 * 
 * @author gullo
 */
class Model_Produttori_Referente {
    
    private $_globalRef;
    private $_ref;
    
    function __construct($globalRef, $ref) 
    {
        $this->_globalRef = $globalRef;
        $this->_ref = $ref;
    }
    
    function is_Referente($idproduttore) 
    {
        if( count($this->_ref) > 0 ) {
            foreach ($this->_ref as $value) {
                if( $value->idproduttore == $idproduttore) {
                    return true;
                }
            }
        }
        return false;
    }
    
    function is_GlobalReferente($idproduttore) 
    {
        if( count($this->_globalRef) > 0 ) {
            foreach ($this->_globalRef as $value) {
                if( $value->idproduttore == $idproduttore) {
                    return true;
                }
            }
        }
        return false;
    }

/*
 *  PERMISSIONS
 */
    
    function canManageProduttore($idproduttore)
    {
        return $this->is_Referente($idproduttore);
    }
    
    function canEditProdotti($idproduttore)
    {
        return $this->is_GlobalReferente($idproduttore);
    }
    
    function canAddProdotti($idproduttore)
    {
        return $this->is_Referente($idproduttore);
    }
    
}