<?php
/**
 * Description of Utenti
 * 
 * @author gullo
 */
class Model_Ordini_Calcoli_Utenti 
    extends Model_Ordini_Calcoli_AbstractCalcoli {
    
    private $_arProdUtenti = null;
    
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
                {
                    $t += $objProd->getTotale_ByIduser($iduser);
                }
            }
        }
        return $t;
    }
    
    // TOTALE INCLUSO SPEDIZIONE
    public function getTotaleConExtraByIduser($iduser) 
    {
        if($this->getSpeseExtra()->has()) 
        {
            $totaleExtra = 0;
            foreach($this->getSpeseExtra()->get() AS $extra)
            {
                $totaleExtra += $extra->getParzialeByIduser($this, $iduser);
            }
            return ($this->getTotaleByIduser($iduser) + $totaleExtra);
        } else {
            return $this->getTotaleByIduser($iduser);
        }
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
 * Private Misc, init settings
 */

    private function _getArProdottiUtenti() 
    {
        if(is_null($this->_arProdUtenti))
        {
            // get ELENCO users del Gruppo
            $usersValues = $this->_getArUsersValues();
            // start to smazzulate prodotti...
            if(count($this->getProdotti()) > 0) 
            {
                foreach ($this->getProdotti() as $prodotto) 
                {
                    $users = $prodotto->getUsers();
                    if(count($users) > 0)
                    {
                        foreach($users AS $iduser)
                        {
                            if(!isset($this->_arProdUtenti[$iduser])) 
                            {
                                $this->_arProdUtenti[$iduser] = array(
                                    'nome'    => $usersValues[$iduser]->nome,
                                    'cognome' => $usersValues[$iduser]->cognome,
                                    'email'   => $usersValues[$iduser]->email
                                );
                            }
                            // set Products
                            $idprodotto = $prodotto->getIdProdotto();
                            if(!isset($this->_arProdUtenti[$idprodotto])) {
                                $this->_arProdUtenti[$iduser]["prodotti"][$idprodotto] = $prodotto;
                            }
                        }
                    }
                }
            }
        }
        //Zend_Debug::dump($this->_arProdUtenti);die;
        return $this->_arProdUtenti;
    }    
    
}