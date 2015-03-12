<?php
/**
 * Description of AbstractCalcoli, it is a DECORATOR of Model_Ordini_Ordine
 * 
 * @author Davide Gullo <gullo at m4ss.net>
 * 
 */
abstract class Model_Ordini_Calcoli_AbstractCalcoli
    implements Model_Ordini_Calcoli_CalcoliDecoratorInterface 
{

    /**
     * The Model_Ordini_Ordine object
     * @var Model_Ordini_Ordine
     */
    protected $_ordine;
    
    /**
     * it's the array of all the group members
     * @var (null|array)
     */
    protected $_arUsers = null;    
    
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
    
    protected function _getArUsersValues()
    {
        if(is_null($this->_arUsers))
        {
            $this->_arUsers = array();
            
            // get ELENCO users del Gruppo
            $this->_userSessionVal = new Zend_Session_Namespace('userSessionVal');
            $userModel = new Model_Db_Users();
            $users = $userModel->getUsersByIdGroup($this->_userSessionVal->idgroup, true);
            foreach($users AS $user)
            {
                $this->_arUsers[$user->iduser] = $user;
            }
        }
        return $this->_arUsers;
    }
    
}