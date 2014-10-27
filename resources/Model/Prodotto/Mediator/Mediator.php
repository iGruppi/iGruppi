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
    

    
}
