<?php
/**
 * Description of newPHPClass
 *
 * @author gullo
 */
class Model_Categorie_Iterator extends RecursiveArrayIterator {
    
    /**
     * Elements array
     * @var array
     */
    protected $elements;
        

    public function __construct($elements) 
    {
        parent::__construct($elements);
    }
    
    public function getChildren ()
    {
        return $this->current()->createIterator();
    }
 
    
    
}
