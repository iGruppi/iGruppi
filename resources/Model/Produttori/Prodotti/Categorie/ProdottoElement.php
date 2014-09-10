<?php
/**
 * Class SubcatElement
 */
class Model_Produttori_Prodotti_Categorie_SubcatElement 
    extends Model_Produttori_Prodotti_Categorie_CategoryElement
{

    protected $_idsubcat;
    
    /**
     * build a Subcat element
     * @param type $id
     * @param type $descrizione
     */
    public function __construct($id, $descrizione)
    {
        $this->_idsubcat = $id;
        $this->_descrizione = $descrizione;
    }
    
    /**
     * None of this batch of methods are used by Leaf because this element has not children
     * However in order to correctly implement the interface we need some kind of implementation
     */
    public function add(Model_Produttori_Prodotti_Categorie_CategoryElement $element) {}
    public function remove($id) {}
    public function getChild($id) {}
    public function getChildren() {}

    /**
     * renders the Subcat element
     * @return mixed|string
     */
    public function render()
    {
        return array('idsubcat' => $this->_idsubcat, 'descrizione' => $this->_descrizione, 'products' => $this->products);
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
