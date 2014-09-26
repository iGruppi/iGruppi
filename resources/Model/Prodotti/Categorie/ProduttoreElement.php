<?php
/**
 * Class ProductElement
 */
class Model_Prodotti_Categorie_ProduttoreElement 
    extends Model_Prodotti_Categorie_Element
{
    /**
     * build a Produttore element
     * @param type $id
     * @param type $descrizione
     */
    public function __construct($id, $descrizione)
    {
        $this->id = $id;
        $this->descrizione = $descrizione;
        $this->elements = null;
    }
    
    /**
     * None of this batch of methods are used by Leaf because this element has not children
     * However in order to correctly implement the interface we need some kind of implementation
     */
    public function add(Model_Prodotti_Categorie_Element $element) 
    {
        throw new MyFw_Exception('Produttore is a leaf, does not have children');
    }
    public function remove($id)
    {
        throw new MyFw_Exception('Produttore is a leaf, does not have children');
    }
    public function getChild($id)
    {
        throw new MyFw_Exception('Produttore is a leaf, does not have children');
    }
}
