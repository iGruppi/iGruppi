<?php
/**
 * Class CompositeElement
 */
abstract class Model_Prodotti_Categorie_CompositeElement 
    extends Model_Prodotti_Categorie_Element
{

    
    /**
     * add new element
     * @param $element Model_Prodotti_Categorie_AbstractElement
     * @return void
     */
    public function add(Model_Prodotti_Categorie_Element $element)
    {
        //$this->elements->append($element);
        $this->elements[] = $element;
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
                    //$this->elements->offsetUnset($key);
                    unset($this->elements[$key]);
                    return true;
                }
            }
        }
        return false;
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
    
}
