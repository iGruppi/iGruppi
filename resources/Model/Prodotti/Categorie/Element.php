<?php
/**
 * Class Category Element
 */
abstract class Model_Prodotti_Categorie_Element
{
    /**
     * Descrizione element
     * @var string
     */
    protected $descrizione;
    
    /**
     * @var array|CategoryElement[]
     */
    protected $elements;
    
    
    /**
     * renders the category code
     * @return mixed
     */
    abstract public function render();
    
    /**
     * add element
     * @param Category Element $element
     * @return void
     */
    public function add(Model_Prodotti_Categorie_Element $element)
    {
        $this->elements[] = $element;
    }
    

    /**
     * get child by id
     * @param $id int
     * @return Model_Prodotti_Categorie_Element
     */
    public function getChild($id)
    {
        if(count($this->elements) > 0) {
            foreach ($this->elements AS $value) {
                if($value->getId() == $id) {
                    return $value;
                }
            }
        }
        return null;
    }
    
    
    /**
     * get array childred
     * @return array
     */    
    public function getChildren() {
        return $this->elements;
    }

    /**
     * remove an element from the tree
     * @param $id int
     * @return bool
     */
    public function remove($id)
    {
        if(count($this->elements) > 0) {
            foreach ($this->elements as $key => $value) {
                if($value->getId() == $id) {
                    unset($this->elements[$key]);
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * return id of the element
     * @return id
     */
    abstract public function getId();            
    
    /**
     * return descrizione
     * @return string
     */
    public function getDescrizione() 
    {
        return $this->descrizione;
    }
        
}
