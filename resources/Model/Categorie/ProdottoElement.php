<?php
/**
 * Class ProductElement
 */
class Model_Categorie_ProdottoElement 
    extends Model_Categorie_Element
{
    
    private $_prodotto;
    
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
     * set Prodotto object
     * @param Model_Builder_Prodotto_Parts_Product $p
     * @return void
     */
    
    public function setProdotto(Model_Builder_Prodotto_Parts_Product $p)
    {
        $this->_prodotto = $p;
    }
    
    /**
     * return the Model_Builder_Prodotto_Parts_Product object
     * @return Model_Builder_Prodotto_Parts_Product
     */
    public function getProdotto()
    {
        return $this->_prodotto;
    }
    /**
     * None of this batch of methods are used by Leaf because this element has not children
     * However in order to correctly implement the interface we need some kind of implementation
     */
    public function add(Model_Categorie_Element $element) 
    {
        throw new MyFw_Exception('Prodotto is a leaf, does not have children');
    }
    public function remove($id)
    {
        throw new MyFw_Exception('Prodotto is a leaf, does not have children');
    }
    public function getChild($id)
    {
        throw new MyFw_Exception('Prodotto is a leaf, does not have children');
    }
}
