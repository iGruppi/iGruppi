<?php
/**
 * This is the Abstract class and it implements the Chain of Responsibility
 * to manage the Products come from AbstractFactory: 
 *  - Dati
 *  - Prodotti
 *  - Gruppi
 *  - Categorie (this is a Composite pattern in Model/Prodotti/Categorie)
 *  
 *  It simple tries to call a method requested in all the Chain of objects.
 *  You have to extend this class to create a specific class to manage all datas,
 *  so you will be able to compose it in the Controllers as you prefer and pass 
 *  just a single instance to the view that can call any methods directly in the objects
 * 
 * @author Davide Gullo <gullo at m4ss.net>
 * 
 */
abstract class Model_AF_AbstractCoR
{
    private $_debug = false;
    
    protected $factoryClass;
    
    protected $chain = array();
    
    /**
     * Append an object to the chain
     * @param object $object one of the Abstract Factory class
     * @return Model_AF_AbstractCoR
     */
    protected function append($idObj, $object)
    {
        $this->chain[$idObj] = $object;
        return $this;
    }
    
    public function appendDati()
    {
        return $this->append("Dati", $this->factoryClass->createDati()->setCor($this) );
    }
    
    public function appendGruppi()
    {
        return $this->append("Gruppi", $this->factoryClass->createGruppi()->setCor($this) );
    }

    public function appendProdotti()
    {
        return $this->append("Prodotti", $this->factoryClass->createProdotti()->setCor($this) );
    }
    
    public function appendCategorie()
    {
        $categorie = new Model_Categorie();
        $categorie->setCoR($this);
        return $this->append("Categorie", $categorie );
    }
    
    public function __call($name, $arguments) {
        try {
            if(count($this->chain) > 0)
            {
                foreach($this->chain AS $object)
                {
                    if($this->_debug) {
                        echo "Try to call '$name' in " . get_class($object) . "<br>";
                    }
                    if(method_exists($object, $name))
                    {
                        if($this->_debug) {
                            echo " ---> FOUND method '$name' in " . get_class($object) . "<br>";
                        }
                        return call_user_func_array(array($object, $name), $arguments);
                    }
                }
            }
            throw new MyFw_Exception("The method '$name' does not exists in the Chain of Responsibility!");
        } catch (MyFw_Exception $exc) {
            $exc->displayError();
        }
    }
    
    
    public function saveToDB()
    {
        try {
            if(count($this->chain) > 0)
            {
                foreach($this->chain AS $idObj => $object)
                {
                    $methodName = "saveToDB_" . $idObj;
                    if(method_exists($object, $methodName))
                    {
                        $object->$methodName();
                    }
                }
                return true;
            }
        } catch (MyFw_Exception $exc) {
            $exc->displayError();
        }
        return false;
    }
    
    
    public function enableDebug()
    {
        $this->_debug = true;
    }
}
