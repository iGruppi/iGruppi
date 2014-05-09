<?php

/**
 * Description of Utente
 * 
 * @author gullo
 */
class Model_Ordini_Calcoli_Utente
    extends Model_Ordini_Ordine {
    
    private $_arProdUtente = null;    
    private $_iduser;
    private $spedObj = null;
    
    function __construct($iduser) {
        $this->_iduser = $iduser;
    }
    
/*
 *  UPDATE setProdotti
 *      Keep only qta of _iduser
 */
    
    function setProdotti($listProd) 
    {
        parent::setProdotti($listProd);
        // SET qta for _iduser
        if(count($this->_arProdOriginal) > 0) {
            foreach ($this->_arProdOriginal AS $value) {
                if($value->iduser == $this->_iduser)
                    $this->getProdotto($value->idprodotto)->setQtaReale($value->qta);
            }
        }
    }
    
    function getProdottiUtente() 
    {
        return $this->_getArProdottiUtente();
    }
    
    
/************************************************************
 *  CALCOLI UTENTE 
 */
    
    // TOTALE ORDINE (Senza costo di spedizione)
    function getTotale() {
        $t = 0;
        $prodottiUt = $this->getProdottiUtente();
        if(!is_null($prodottiUt) && count($prodottiUt) > 0) {
            foreach ($prodottiUt AS $objProd) {
                if($objProd->isDisponibile())
                    $t += $objProd->getTotale();
            }
        }
        return $t;
    }
        
    
/************************************************************
 * SPEDIZIONI
 */

    function getSpedizione() 
    {
        if(is_null($this->spedObj))
        {
            $ocuObj = new Model_Ordini_Calcoli_Utenti();
            $ocuObj->setOrdObj($this->getOrdObj());
            $ocuObj->setProdotti($this->_arProdOriginal);
            $this->spedObj = new Model_Ordini_Calcoli_CostoSpedizione($ocuObj);
        }
        return $this->spedObj;
    }
    
    // TOTALE INCLUSO SPEDIZIONE
    function getTotaleConSpedizione() {
        return ($this->getTotale() + $this->getCostoSpedizioneRipartito());
    }
    
    function getCostoSpedizioneRipartito() {
        return $this->getSpedizione()->getCostoSpedizioneRipartitoByIduser($this->_iduser);
    }
    
    
/************************************************************
 * Private Misc, init settings
 */

    private function _getArProdottiUtente() {
        if(is_null($this->_arProdUtente)) {
            if(count($this->_arProdOriginal) > 0) {
                // Create instance Model_Ordini_Prodotto for any Product
                foreach ($this->_arProdOriginal as $value) {
                    // get ONLY iduser products
                    $iduser = $value->iduser;
                    if( $iduser == $this->_iduser ) {
                        // set Products
                        $idprodotto = $value->idprodotto;
                        if(!isset($this->_arProdUtente[$idprodotto])) {
                            $this->_arProdUtente[$idprodotto] = new Model_Ordini_Prodotto($value);
                        }
                    }
                }
            }
        }
        return $this->_arProdUtente;
    }
    
}