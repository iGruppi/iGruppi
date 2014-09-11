<?php
/**
 * Class SubcatElement
 */
class Model_Prodotti_Categorie_SubcatElement 
    extends Model_Prodotti_Categorie_Element
{
    /**
     * IdSubCat ID Sub-Categoria
     * @var mixed
     */
    protected $_idsubcat;
    
    /**
     * build a Subcat element
     * @param type $id
     * @param type $descrizione
     */
    public function __construct($id, $descrizione)
    {
        $this->_idsubcat = $id;
        $this->descrizione = $descrizione;
        $this->elements = array();        
    }
    
    /**
     * renders the Subcat elements
     * @return mixed|string
     */
    public function render()
    {
        $ar = array();
        if(count($this->elements) > 0) {
            $ar = array('idsubcat' => $this->_idsubcat, 'descrizione' => $this->descrizione, 'elements' => array());
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
        return $this->_idsubcat;
    }
    
}
