<?php
/**
 * Class ProductElement
 */
class Model_Produttori_Prodotti_Categorie_ProdottoElement 
    extends Model_Produttori_Prodotti_Categorie_Element
{
    /**
     * IdProdotto
     * @var mixed
     */
    protected $_idprodotto;
    
    /**
     * build a Prodotto element
     * @param type $id
     * @param type $descrizione
     */
    public function __construct($id, $descrizione)
    {
        $this->_idprodotto = $id;
        $this->descrizione = $descrizione;
    }
    
    /**
     * None of this batch of methods are used by Leaf because this element has not children
     * However in order to correctly implement the interface we need some kind of implementation
     */
    public function add(Model_Produttori_Prodotti_Categorie_Element $element) {}
    public function remove($id) {}
    public function getChild($id) {}
    public function getChildren() {}

    /**
     * renders the Subcat element
     * @return mixed|string
     */
    public function render()
    {
        return array('idprodotto' => $this->_idprodotto, 'descrizione' => $this->descrizione);
    }
    
    /**
     * return the idsubcat
     * @return id
     */
    public function getId()
    {
        return $this->_idprodotto;
    }
    
}
