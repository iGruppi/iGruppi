<?php
/**
 * Class SubcatElement
 */
class Model_Produttori_Prodotti_Categorie_SubcatElement 
    extends Model_Produttori_Prodotti_Categorie_CategoryElement
{
    
    private $_idsubcat;
    private $_descrizione;
    
    public function __construct($id, $descrizione)
    {
        $this->_idsubcat = $id;
        $this->_descrizione = $descrizione;
    }
    
    /* None of this batch of methods are used by Leaf */
    /* However in order to correctly implement the interface */
    /* you need some kind of implementation */
    public function add(Model_Produttori_Prodotti_Categorie_CategoryElement $element){}
    public function remove(Model_Produttori_Prodotti_Categorie_CategoryElement $element){}
    public function getChild($id){}
    
    /**
     * renders the Subcat element
     * @return mixed|string
     */
    public function render()
    {
        return array('idsubcat' => $this->_idsubcat, 'descrizione' => $this->_descrizione);
    }
    
    /**
     * This element has not children
     * @return null
     */    
    public function getChildren() {
        return null;
    }

    /**
     * return the idsubcat
     * @return id
     */
    public function getId()
    {
        return $this->_idsubcat;
    }
    
    /**
     * return descrizione
     * @return string
     */
    public function getDescrizione() 
    {
        return $this->_descrizione;
    }
    
}
