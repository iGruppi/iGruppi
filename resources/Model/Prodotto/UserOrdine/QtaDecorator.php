<?php
/**
 * Description of QtaDecorator
 * 
 * @author Davide Gullo <gullo at m4ss.net>
 * 
 */
class Model_Prodotto_UserOrdine_QtaDecorator
    implements Model_Prodotto_UserOrdine_QtaDecoratorInterface, Model_Prodotto_Mediator_MediatorInterface
{
    /**
     * Store the Prodotto_Mediator object
     * @var Model_Prodotto_Mediator_Mediator
     */
    private $_prodotto;
    
    /**
     * This class CAN ONLY decorate a Mediator_Prodotto
     * @param Model_Prodotto_Mediator_Mediator $prodotto
     */
    public function __construct(Model_Prodotto_Mediator_Mediator $prodotto) 
    {
        $this->_prodotto = $prodotto;
    }
    
    /**
     * Route all other method calls directly to Prodotto_Mediator
     * @param type $method
     * @param type $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        // you could also add method_exists check here
        return call_user_func_array(array($this->_prodotto, $method), $args);
    }
    
    /**
     * 
     * @var array
     */
    private $_arQta = array();
    
    /**
     * set qta field per user
     * @param type $iduser
     * @param type $qta
     */
    public function setQta($iduser, $qta)
    {
        $this->_arQta[$iduser]["qta"] = $qta;
    }
    
    /**
     * set qta_reale per user
     * @param type $iduser
     * @param type $qta_reale
     */
    public function setQtaReale($iduser, $qta_reale)
    {
        $this->_arQta[$iduser]["qta_reale"] = $qta_reale;
    }
    
    /**
     * return array with users list
     * @return array
     */
    public function getUsers()
    {
        $users = array();
        if(count($this->_arQta) > 0)
        {
            foreach($this->_arQta AS $iduser => $values)
            {
                $users[] = $iduser;
            }
        }
        return $users;
    }
    
    
/******************
 * TOTALI METHODS *
 */
    
    public function getQtaReale()
    {
        $qta_reale = 0;
        if(count($this->_arQta) > 0)
        {
            foreach($this->_arQta AS $iduser => $values)
            {
                $qta_reale += $values["qta_reale"];
            }
        }
        return $qta_reale;
    }
    
    public function getQta()
    {
        $qta = 0;
        if(count($this->_arQta) > 0)
        {
            foreach($this->_arQta AS $iduser => $values)
            {
                $qta += $values["qta"];
            }
        }
        return $qta;
    }
    
    /**
     * @return float
     */    
    public function getTotale() 
    {
        return $this->getCostoOrdine() * $this->getQtaReale();
    }
    
    /**
     * @return float
     */    
    public function getTotaleSenzaIva() 
    {
        return $this->getCostoSenzaIva() * $this->getQtaReale();
    }    

    
/********************
 * PER USER METHODS *
 */
    
    public function getQtaReale_ByIduser($iduser)
    {
        if(isset($this->_arQta[$iduser])) 
        {
            return $this->_arQta[$iduser]["qta_reale"];
        }
        return 0;
    }
    
    public function getQta_ByIduser($iduser)
    {
        if(isset($this->_arQta[$iduser])) 
        {
            return $this->_arQta[$iduser]["qta"];
        }
        return 0;
    }
    
    /**
     * @return float
     */    
    public function getTotale_ByIduser($iduser) 
    {
        return $this->getCostoOrdine() * $this->getQtaReale_ByIduser($iduser);
    }
    
    /**
     * @return float
     */    
    public function getTotaleSenzaIva_ByIduser($iduser) 
    {
        return $this->getCostoSenzaIva() * $this->getQtaReale_ByIduser($iduser);
    }    
    
    
}
