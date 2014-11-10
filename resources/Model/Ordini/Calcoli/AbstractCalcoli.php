<?php

/**
 * Description of AbstractCalcoli
 * 
 * @author Davide Gullo <gullo at m4ss.net>
 * 
 */
abstract class Model_Ordini_Calcoli_AbstractCalcoli {

    /**
     * The Model_Ordini_Ordine object
     * @var Model_Ordini_Ordine
     */
    protected $_ordine;
    
    /**
     * Prodotti ordered by any user for an order (comes from ordini_user_prodotti table)
     * @var array
     */
    protected $_prodotti;
    
    /**
     * it's the array of all the group members
     * @var (null|array)
     */
    protected $_arUsers = null;

    
    /**
     * Set Model_Ordini_Ordine object
     * @param Model_Ordini_Ordine $ordine
     */
    public function setOrdine(Model_Ordini_Ordine $ordine) {
        $this->_ordine = $ordine;
    }
    
    public function setProdottiOrdinati($prodotti)
    {
        if(count($prodotti) > 0)
        {
            foreach($prodotti AS $values)
            {
                $prodotto = $this->_ordine->getProdottoById($values->idprodotto);
                $prodotto->setQta($values->iduser, $values->qta);
                $prodotto->setQtaReale($values->iduser, $values->qta_reale);
            }
        }
    }
    
    public function getProdotti()
    {
        return $this->_ordine->getProdotti();
    }
    
    public function hasCostoSpedizione()
    {
        return $this->_ordine->hasCostoSpedizione();
    }
    
    public function getCostoSpedizione()
    {
        return $this->_ordine->getCostoSpedizione();
    }
    
    
    /**
     * Return data (from users table) of user by iduser
     * @param type $iduser
     * @return stdClass
     */
    public function getDatiUtenteById($iduser)
    {
        $arUsers = $this->_getArUsersValues();
        if(count($arUsers) > 0)
        {
            foreach ($arUsers as $key => $value) {
                if($value->iduser == $iduser)
                {
                    return $value;
                }
            }
        }
        return null;
    }    
    
    protected function _getArUsersValues()
    {
        if(is_null($this->_arUsers))
        {
            $this->_arUsers = array();
            
            // get ELENCO users del Gruppo
            $this->_userSessionVal = new Zend_Session_Namespace('userSessionVal');
            $userModel = new Model_Db_Users();
            $users = $userModel->getUsersByIdGroup($this->_userSessionVal->idgroup);
            foreach($users AS $user)
            {
                $this->_arUsers[$user->iduser] = $user;
            }
        }
        return $this->_arUsers;
    }
    
}