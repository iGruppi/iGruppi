<?php
/**
 * Class Category Element
 */
abstract class Model_Categorie_Element
{
    /**
     * Id
     * @var mixed
     */
    protected $id;
    
    /**
     * Descrizione element
     * @var string
     */
    protected $descrizione;
    
    /**
     * Elements array
     * @var array
     */
    protected $elements;
        
    
    /**
     * add element
     * @param Category Element $element
     * @return void
     */
    abstract public function add(Model_Categorie_Element $element);
    
    /**
     * remove an element from the tree
     * @param $id int
     * @return bool
     */
    abstract public function remove($id);

    /**
     * get child by id
     * @param $id int
     * @return Model_Categorie_Element
     */
    abstract public function getChild($id);
    
    /**
     * create an Iterator for this composite
     * @return Model_Categorie_Iterator
     */
    public function createIterator()
    {
        try {
            return new Model_Categorie_Iterator($this->elements);
            
        } catch (MyFw_Exception $e) {
            $e->displayError();
        }
    }
    
    
    /**
     * return id of the element
     * @return id
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * return descrizione
     * @return string
     */
    public function getDescrizione() 
    {
        return $this->descrizione;
    }
        
}
