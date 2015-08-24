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
     * List of the colleagues managed in this Mediator
     * @var array
     */
    private $colleagues = array('Anagrafica', 'Listino', 'Ordine');
    
    /**
     * @var Model_Prodotto_Mediator_Anagrafica
     */
    protected $Anagrafica = null;

    /**
     * @var Model_Prodotto_Mediator_Listino
     */
    protected $Listino = null;

    /**
     * @var Model_Prodotto_Mediator_Ordine
     */
    protected $Ordine = null;

    /**
     * init the Class with all the Colleague
     */
    public function __construct() {
        $this->Anagrafica = new Model_Prodotto_Mediator_Anagrafica($this);
        $this->Listino = new Model_Prodotto_Mediator_Listino($this);
        $this->Ordine = new Model_Prodotto_Mediator_Ordine($this);
    }

    public function initByObject($obj)
    {
        $this->Anagrafica->initValuesByObject($obj);
        $this->Listino->initValuesByObject($obj);
        $this->Ordine->initValuesByObject($obj);
    }
    
    public function __call($name, $arguments) {
        try {
            foreach($this->colleagues AS $colleague)
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
        $this->Anagrafica->setIdProdotto($id);
        $this->Listino->setIdProdotto($id);
        $this->Ordine->setIdProdotto($id);
    }
    
    /**
     * Set IdListino for Listino and Ordine colleagues
     * @param mixed $id
     */
    public function setIdListino($id)
    {
        $this->Listino->setIdListino($id);
        $this->Ordine->setIdListino($id);
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
        return $this->Anagrafica->getValuesArray();
    }
    public function getListinoValues()
    {
        return $this->Listino->getValuesArray();
    }
    public function getOrdineValues()
    {
        return $this->Ordine->getValuesArray();
    }
    
/* ******************************
 *  Persist data
 */    
    
    public function saveToDB_Prodotto()
    {
        try {
            $prodottiModel = new Model_Db_Prodotti();
            foreach($this->colleagues AS $colleague)
            {
                // check if something is changed
                if($this->$colleague->isChanged())
                {
                    $prodottiModel->updateProdotti($colleague, $this);
                }
            }
            return true;
            
        } catch (Model_Prodotto_Mediator_Exception $ex) {
            $ex->displayError();
        }        
    }
    
    
/* ******************************
 *  Pattern Strategy for Costo  *
 */    
    
    /**
     * The default context if you do not set anyone
     * @var string
     */
    private $_context = "Anagrafica";
    
    
    public function setDefaultStrategyContext_Anagrafica()
    {
        $this->_context = "Anagrafica";
    }
    public function setDefaultStrategyContext_Listino()
    {
        $this->_context = "Listino";
    }
    public function setDefaultStrategyContext_Ordine()
    {
        $this->_context = "Ordine";
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
