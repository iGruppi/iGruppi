<?php
/**
 * Product is the product of the Builder, it must be extended
 */
abstract class Model_Prodotto_Mediator_AbstractProduct
{
    
/*************
 * MEDIATOR
 */
    
    /**
     * Is private, this ensures no change in subclasses
     * @var Model_Prodotto_Mediator_MediatorInterface
     */
    private $mediator;

    /**
     * Get mediator so any colleague can talk with the others through the Mediator
     * @return MediatorInterface
     */
    protected function getMediator()
    {
        return $this->mediator;
    }

    /**
     * in this way, we are sure the concrete colleague knows the mediator
     * @param Model_Prodotto_Mediator_MediatorInterface $medium
     */
    public function __construct(Model_Prodotto_Mediator_MediatorInterface $medium)
    {
        $this->mediator = $medium;
    }    
    
/*************
 * OBSERVER
 */
    
    /**
     * This array stores all the observers attached
     * @var array
     */
    protected $observers = array();
    
    /**
     * Accepts an observer
     * @param Model_Prodotto_Observer_Interface $observer
     */
    public function attach(Model_Prodotto_Observer_Interface $observer)
    {
        $this->observers[] = $observer;
    }
    
    /**
     * Notify the Observers
     * It is public because I prefer that the Client can control the notification generation
     * @return void
     */
    public function _notify()
    {
        foreach ($this->observers as $observer)
        {
            $observer->save($this);
        }
    }
    

/*************************
 * INIT DATA, GET and SET
 */
    protected $_data = array();
    protected $_values = array();
    
    /**
     * Init the Product object by stdClass or Array (only the fields available in data)
     * @param (stdClass|array) $obj
     * @return void
     */
    public function initValuesByObject($obj)
    {
        try {
            // Convert Array to Object
            if(is_array($obj)) {
                $obj = (object)$obj;
            }
            
            foreach ($obj AS $f => $v) 
            {
                // exclude the unknown fields
                if(in_array($f, $this->_data) ) {
                    $this->_values[$f] = $v;
                }
            }
        } catch (MyFw_Exception $exc) {
            $exc->displayError();
        }
    }
            
    
    /**
     * Set Value in a Field and keep trace of every change
     * @param type $f Field name
     * @param type $v Value
     */
    protected function _setValue($f, $v)
    {
        // Check if the field exists
        if(in_array($f, $this->_data) ) {
            // Check if the value is changed
            if( !isset($this->_values[$f]) || $v != $this->_values[$f] )
            {
                $this->_values[$f] = $v;
                // notify the Observers
                // $this->_notify();
                // $this->getMediator()->getObserver()->notify($this);
            }
        }
    }
    
    protected function _getValue($f)
    {
        if( isset($this->_values[$f]) ) {
            return $this->_values[$f];
        }/* else {
            throw new MyFw_Exception("Cannot GET '$f', it does NOT exists in Product!");
        }*/
        return null;
    }
    
    public function getValuesArray()
    {
        return $this->_values;
    }

/* *******************************************
 *  GET Strategy for Costo
 */    
    
    /*
    * Overloading __call
    * This try to call a method in Costo_ContextStrategy
    public function __call ( $method, $args )
    {
        // controllo esistenza metodo
        if( method_exists( $this, $method ) )
        {
            call_user_func_array(array($this, $method), $args);
        } else {
            try {
                // get StrategyContext
                $sc = new Model_Prodotto_Costo_ContextStrategy($this);
                $sc->setContext($this->_context);
                return $sc->$method($args);
                
            } catch (MyFw_Exception $exc) {
                $exc->displayError();
            }
        }
    }
    
       */
 

    
    
    
    
/* *******************************************
 *  MISCELLANEOUS
 */  
    
    protected function filterFlag($flag) {
        if(is_string($flag) ) {
            return ($flag == "S") ? true : false;
        } elseif (is_bool($flag)) {
            return $flag;
        } else {
            return (bool)$flag;
        }
    }
    
}
