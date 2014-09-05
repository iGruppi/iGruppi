<?php
/**
 * This is the composite object
 */
class Model_Produttori_Prodotti_Categorie 
    extends Model_Produttori_Prodotti_Categorie_CategoryElement
{
    /**
     * @var array|CategoryElement[]
     */
    protected $elements;

    public function __construct($id, $descrizione)
    {
        $this->_idcat = $id;
        $this->_descrizione = $descrizione;
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
        $ar = array();
        if(count($this->elements) > 0) {
            $ar = array('idcat' => $this->_idcat, 'descrizione' => $this->_descrizione, 'products' => $this->products, 'subcat' => array());
            foreach ($this->elements as $element) {
                $ar['subcat'][] = $element->render();
            }
        }
        return $ar;
    }

    /**
     * @param CategoryElement $element
     * @return void
     */
    public function add(Model_Produttori_Prodotti_Categorie_CategoryElement $element)
    {
        $this->elements[] = $element;
    }
    
    /**
     * @param $id int
     * @return Model_Produttori_Prodotti_Categorie_CategoryElement
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
     * This element has not children
     * @return null
     */    
    public function getChildren() {
        return $this->elements;
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
