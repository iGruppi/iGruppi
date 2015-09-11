<?php
/**
 * Description of CalcoliAbstract
 * 
 * @author Davide Gullo <gullo at m4ss.net>
 * 
 */
abstract class Model_Ordini_CalcoliAbstract
    implements Model_Ordini_CalcoliDecoratorInterface 
{

    /**
     * The Model_Ordini_Ordine object
     * @var Model_Ordini_Ordine
     */
    protected $_ordine;

    /**
     * Array of all the group members
     * @var (null|array)
     */
    protected $_arUsers = null;    
    
    /**
     * Array Utenti con Prodotti
     * @var (null|array)
     */
    protected $_arProdUtenti = null;
    
    /**
     * It CAN ONLY decorate the Model_Ordini_Ordine
     * @param Model_Ordini_Ordine $ordine
     */
    public function __construct(Model_Ordini_Ordine $ordine) {
        $this->_ordine = $ordine;
    }
    
    /**
     * Route all other method calls directly to Model_Ordini_Ordine
     * @param type $method
     * @param type $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        // you could also add method_exists check here
        return call_user_func_array(array($this->_ordine, $method), $args);
    }
    
    /**
     * Set Prodotti Ordinati by every Iduser
     * @param array $prodotti
     */
    public function setProdottiOrdinati(array $prodotti)
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

    
    
    
/***********************************************
 *  TOTALE - METHODS
 */
    
    /**
     * TOTALE ORDINE (Senza Extra e IVA COMPRESA)
     * @return float
     */
    public function getTotale() 
    {
        $t = 0;
        if(count($this->getProdotti()) > 0) 
        {
            foreach ($this->getProdotti() as $idprodotto => $objProd) 
            {
                if($objProd->isDisponibile())
                {
                    $t += $objProd->getTotale();
                }
            }
        }
        return $t;
    }
    
    /**
     * TOTALE SENZA IVA
     * @return type
     */
    public function getTotaleSenzaIva() 
    {
        $t = 0;
        if(count($this->getProdotti()) > 0) 
        {
            foreach ($this->getProdotti() as $idprodotto => $objProd) 
            {
                if($objProd->isDisponibile())
                {
                    $t += $objProd->getTotaleSenzaIva();
                }
            }
        }
        return $t;
    }
    
/*
 *  TODO:
 *  Questo metodo è da sistemare perchè con la nuova implementazione Pezzatura/Taglio e la qta_reale
 *  il numero risultante potrebbe avere dei decimali, il che non ha senso
 *  Conviene visualizzare un riepilogo basato sui vari Udm, es: Totale Confezioni, Totale Pezzi, Totale Kg, ecc.
 * 
 *  Per ora, per non visualizzare decimali, lascio la sommatoria dei qta (quantità ordinata)
 */    
    public function getTotaleColli() {
        $c = 0;
        if(count($this->getProdotti()) > 0) {
            foreach ($this->getProdotti() as $idprodotto => $objProd) {
                if($objProd->isDisponibile())
                {
                    $c += $objProd->getQta();
                }
            }
        }
        return $c;
    }    
    
    
/************************************************************
 * UTENTI - METHODS
 */
    /**
     * Array UTENTI e PRODOTTI ordinati
     * @return array
     */
    public function getProdottiUtenti()
    {
        return $this->_getArProdottiUtenti();
    }
    
    
    /**
     * Elenco PRODOTTI per UTENTE
     * @param int $iduser
     * @return array
     */
    public function getProdottiByIduser($iduser) {
        $prodotti = $this->getProdottiUtenti();
        return isset($prodotti[$iduser]) ? $prodotti[$iduser]["prodotti"] : null;
    }
    
    /**
     * TOTALE per UTENTE
     * @param int $iduser
     * @return float
     */
    public function getTotaleByIduser($iduser) {
        $t = 0;
        $arProd = $this->getProdottiByIduser($iduser);
        if(!is_null($arProd) && count($arProd) > 0) {
            foreach ($arProd AS $objProd) {
                if($objProd->isDisponibile())
                {
                    $t += $objProd->getTotale_ByIduser($iduser);
                }
            }
        }
        return $t;
    }
    
    /**
     * Elenco utenti
     * @return array
     */
    public function getElencoUtenti()
    {
        $prodotti = $this->getProdottiUtenti();
        $users = array();
        if(count($prodotti) > 0)
        {
            foreach ($prodotti as $iduser => $value)
            {
                $users[$iduser] = $value["user"];
            }
        }
        return $users;
    }
    
/***********************************************
 *  USER METHODS
 */
    
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
            if(isset($this->_arUsers[$iduser]))
            {
                return $this->_arUsers[$iduser];
            }
        }
        return null;
    }    
    

/***********************************************
 *  PRIVATE METHODS
 */
    
    /**
     * GET Array dati UTENTI
     * It sets the array once time
     * @return array
     */
    private function _getArUsersValues()
    {
        if(is_null($this->_arUsers))
        {
            // get ELENCO users del Gruppo
            $this->_userSessionVal = new Zend_Session_Namespace('userSessionVal');
            $userModel = new Model_Db_Ordini();
            $users = $userModel->getUsersWithAlmostOneProductOrderedByIdOrdine($this->_ordine->getIdOrdine());
            $this->_arUsers = $users;
        }
        return $this->_arUsers;
    }
    
    
    /**
     * GET Array UTENTI con PRODOTTI
     * It sets the array once time
     * @return array
     */
    private function _getArProdottiUtenti() 
    {
        if(is_null($this->_arProdUtenti))
        {
            // get ELENCO users del Gruppo
            $usersValues = $this->_getArUsersValues();
            if(count($this->getProdotti()) > 0) 
            {
                foreach ($this->getProdotti() as $prodotto) 
                {
                    $users = $prodotto->getUsers();
                    if(count($users) > 0)
                    {
                        foreach($users AS $iduser)
                        {
                            // set User
                            if(!isset($this->_arProdUtenti[$iduser])) 
                            {
                                $this->_arProdUtenti[$iduser]["user"] = $usersValues[$iduser];
                            }

                            // set Products
                            $idprodotto = $prodotto->getIdProdotto();
                            if(!isset($this->_arProdUtenti[$iduser]["prodotti"][$idprodotto])) {
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