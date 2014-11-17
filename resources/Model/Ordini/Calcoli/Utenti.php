<?php

/**
 * Description of Utenti
 * 
 * @author gullo
 */
class Model_Ordini_Calcoli_Utenti 
    extends Model_Ordini_Ordine {
    
    private $_arProdUtenti = null;
    private $spedObj = null;
    
    function getProdottiUtenti()
    {
        return $this->_getArProdottiUtenti();
    }
    
    function getProdottiByIduser($iduser) {
        $prodotti = $this->getProdottiUtenti();
        return isset($prodotti[$iduser]) ? $prodotti[$iduser]["prodotti"] : null;
    }
    
    function getTotaleByIduser($iduser) {
        $t = 0;
        $arProd = $this->getProdottiByIduser($iduser);
        if(!is_null($arProd) && count($arProd) > 0) {
            foreach ($arProd as $idprodotto => $objProd) {
                if($objProd->isDisponibile())
                    $t += $objProd->getTotale();
            }
        }
        return $t;
    }
    
    function getElencoUtenti()
    {
        $prodotti = $this->getProdottiUtenti();
        $users = array();
        if(count($prodotti) > 0)
        {
            foreach ($prodotti as $iduser => $value)
            {
                $users[$iduser] = array(
                                'nome'      => $value["nome"],
                                'cognome'   => $value["cognome"],
                                'email'     => $value["email"],
                );
            }
        }
        return $users;
    }
    
    function isThereSomeProductsOrdered() {
        return (count($this->getProdottiUtenti()) > 0) ? true : false;
    }
    
    
    
/************************************************************
 * SPEDIZIONI
 */

    function getSpedizione() 
    {
        if(is_null($this->spedObj))
        {
            $this->spedObj = new Model_Ordini_Calcoli_CostoSpedizione($this);
        }
        return $this->spedObj;
    }
    
    function getTotaleConSpedizioneByIduser($iduser) {
        return ($this->getTotaleByIduser($iduser) + $this->getSpedizione()->getCostoSpedizioneRipartitoByIduser($iduser));
    }
    
/************************************************************
 * Private Misc, init settings
 */

    private function _getArProdottiUtenti() {
        if(is_null($this->_arProdUtenti)) 
        {
            if(count($this->_arProdOriginal) > 0) 
            {
                // Create instance Model_Ordini_Prodotto for any Product
                foreach ($this->_arProdOriginal as $value) 
                {
                    $iduser = $value->iduser;
                    if( !is_null($iduser) ) 
                    {
                        if(!isset($this->_arProdUtenti[$iduser])) 
                        {
                            $this->_arProdUtenti[$iduser] = array(
                                'nome'    => $value->nome,
                                'cognome' => $value->cognome,
                                'email'   => $value->email
                            );
                        }
                        // set Products
                        $idprodotto = $value->idprodotto;
                        if(!isset($this->_arProdUtenti[$iduser]["prodotti"][$idprodotto])) {
                            $this->_arProdUtenti[$iduser]["prodotti"][$idprodotto] = new Model_Ordini_Prodotto($value);
                        }
                    }
                }
            }
        }
//        Zend_Debug::dump($this->_arProdUtenti);die;
        return $this->_arProdUtenti;
    }    
    
    
}