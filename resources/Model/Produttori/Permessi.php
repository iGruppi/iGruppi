<?php
/**
 * Description of Model_Produttori_Permessi
 * 
 * @author gullo
 */
class Model_Produttori_Permessi {
    
    /**
     * List PRODUTTORI per cui è GESTORE
     * @var array
     */
    private $_gestore;

    /**
     * List PRODUTTORI per cui è REFERENTE
     * @var array
     */
    private $_referente;
    
    /**
     * Imposta array per GESTORE e REFERENTE
     * @param array $ges
     * @param array $ref
     */
    function __construct($ges, $ref) 
    {
        $this->_gestore = $ges;
        $this->_referente = $ref;
    }
    
    function is_Referente($idproduttore) 
    {
        if( count($this->_referente) > 0 ) {
            foreach ($this->_referente as $value) {
                if( $value->idproduttore == $idproduttore) {
                    return true;
                }
            }
        }
        return false;
    }
    
    function is_Gestore($idproduttore) 
    {
        if( count($this->_gestore) > 0 ) {
            foreach ($this->_gestore as $value) {
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
        return $this->is_Gestore($idproduttore);
    }
    
    function canEditProdotti($idproduttore)
    {
        return $this->is_Gestore($idproduttore);
    }
    
    function canAddProdotti($idproduttore)
    {
        return $this->is_Gestore($idproduttore);
    }
    
    function canViewGestioneOrdini()
    {
        return true; // TODO
    }
    
    function canOpenNewOrdine()
    {
        return count($this->_referente);
    }
}