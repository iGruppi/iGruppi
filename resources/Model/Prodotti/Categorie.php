<?php
/**
 * This is the composite object
 */
class Model_Prodotti_Categorie 
    extends Model_Prodotti_Categorie_Element
{
    
    /**
     * IdCat ID Categoria
     * @var mixed
     */
    private $_idcat;

    public function __construct($id, $descrizione)
    {
        $this->_idcat = $id;
        $this->descrizione = $descrizione;
        $this->elements = array();
    }
    
    /**
     * runs through all elements and calls render() on them, then returns the 
     * complete array of the tree categories and subcategories
     *
     * @return array
     */
    public function render()
    {
        // init array for this Category
        $ar = array('idcat' => $this->_idcat, 'descrizione' => $this->descrizione, 'elements' => array());
        // render sub elements
        if(count($this->elements) > 0) {
            foreach ($this->elements as $element) {
                $ar['elements'][] = $element->render();
            }
        }
        return $ar;
    }

    
    /**
     * return the idsubcat
     * @return id
     */
    public function getId()
    {
        return $this->_idcat;
    }
    
}
