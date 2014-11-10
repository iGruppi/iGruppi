<?php
/**
 * This is the concrete Mediator
 * 
 * @author Davide Gullo <gullo at m4ss.net>
 * 
 */
class Model_Prodotto_Mediator_Mediator implements Model_Prodotto_Mediator_MediatorInterface
{

    /**
     * @var Model_Prodotto_Mediator_Anagrafica
     */
    protected $anagrafica = null;

    /**
     * @var Model_Prodotto_Mediator_Listino
     */
    protected $listino = null;

    /**
     * @var Model_Prodotto_Mediator_Ordine
     */
    protected $ordine = null;

    /**
     * init the Class with all the Colleague
     */
    public function __construct() {
        $this->anagrafica = new Model_Prodotto_Mediator_Anagrafica($this);
        $this->listino = new Model_Prodotto_Mediator_Listino($this);
        $this->ordine = new Model_Prodotto_Mediator_Ordine($this);
    }

    /*
    public function attachToColleagues(Model_Prodotto_Observer_AbstractSubject $subject)
    {
        $this->anagrafica->attach($subject);
        $this->listino->attach($subject);
        $this->ordine->attach($subject);
    }
     */
    
    public function initByObject($obj)
    {
        $this->anagrafica->initValuesByObject($obj);
        $this->listino->initValuesByObject($obj);
        $this->ordine->initValuesByObject($obj);
    }
    
    public function __call($name, $arguments) {
        try {
            foreach(array('anagrafica', 'listino', 'ordine') AS $colleague)
            {
                if(method_exists($this->$colleague, $name)) {
                    return call_user_func_array(array($this->$colleague, $name), $arguments);
                }
            }
            
            // The moethod does NOT exists, ERROR
            throw new Model_Prodotto_Mediator_Exception("The method '$name' does NOT exists!");
            
        } catch (Model_Prodotto_Mediator_Exception $ex) {
            $ex->displayError();
        }
    }
    
    /**
     * Set IdProdotto for any colleagues
     * @param mixed $id
     */
    public function setIdProdotto($id)
    {
        $this->anagrafica->setIdProdotto($id);
        $this->listino->setIdProdotto($id);
        $this->ordine->setIdProdotto($id);
    }
    
    /**
     * Set IdListino for Listino and Ordine colleagues
     * @param mixed $id
     */
    public function setIdListino($id)
    {
        $this->listino->setIdListino($id);
        $this->ordine->setIdListino($id);
    }
    
    /**
     * Return array with ALL values of the colleagues
     * @return array
     */
    public function getValues()
    {
        return array_merge(
                    $this->getAnagraficaValues(),
                    $this->getListinoValues(),
                    $this->getOrdineValues()
                );
    }
    
    public function getAnagraficaValues()
    {
        return $this->anagrafica->getValuesArray();
    }
    public function getListinoValues()
    {
        return $this->listino->getValuesArray();
    }
    public function getOrdineValues()
    {
        return $this->ordine->getValuesArray();
    }
    
    
/* ******************************
 *  Pattern Strategy for Costo  *
 */    
    
    private $_context = "Anagrafica";
    
    const _C_ANAGRAFICA = "Anagrafica";
    const _C_LISTINO = "Listino";
    const _C_ORDINE = "Ordine";
    
    public function setDefaultStrategyContext_Anagrafica()
    {
        $this->_context = $this::_C_ANAGRAFICA;
    }
    public function setDefaultStrategyContext_Listino()
    {
        $this->_context = $this::_C_LISTINO;
    }
    public function setDefaultStrategyContext_Ordine()
    {
        $this->_context = $this::_C_ORDINE;
    }
    
    public function getCosto()
    {
        return $this->_callStrategyMethod("Costo");
    }
    
    public function getDescrizioneCosto()
    {
        return $this->_callStrategyMethod("DescrizioneCosto");
    }
    
    public function getCostoSenzaIva()
    {
        return $this->_callStrategyMethod("CostoSenzaIva");
    }
    
    public function getTotaleIva()
    {
        return $this->_callStrategyMethod("TotaleIva");
    }
    
    /**
     * This try to call a method in Costo_ContextStrategy
     * @param string $method
     * @param mixed $args
     * @return mixed
     */
    private function _callStrategyMethod( $method )
    {
        try {
            // get Strategy class
            $obj = $this->_getStrategyClass($method);
            return $obj->calculate($this);

        } catch (MyFw_Exception $exc) {
            $exc->displayError();
        }
    }
    
    /**
     * GET instance of Strategy Class
     * @param type $method
     * @return \className
     * @throws MyFw_Exception
     */
    private function _getStrategyClass($method)
    {
        // TRY to create Class name in base a metodo e context
        try {
            $className = "Model_Prodotto_Costo_".$method . $this->_context;
            return new $className();
        } catch (Exception $ex) {
            try {
                // try to get DEFAULT class
                 $className = "Model_Prodotto_Costo_".$method . "Default";
                 return new $className();
            } catch (Exception $ex) {
                die("The method _getStrategyClass (". $method . " - " . $this->_context . ") NON esiste!");
            }
        }
    }
    
    
    
    
}
