<?php
/**
 * Class CategoryElement
 */
abstract class Model_Produttori_Prodotti_Categorie_CategoryElement
{
    protected $_descrizione;
    
    /**
     * @var array of IdProducts
     */
    protected $products = array();
    
    /**
     * renders the category code
     * @return mixed
     */
    abstract public function render();
    
    /**
     * add element
     * @return void
     */
    abstract public function add(Model_Produttori_Prodotti_Categorie_CategoryElement $element);

    /**
     * add IdProduct to the array
     * @param type $idproduct
     */
    public function addIdProdotto($idproduct)
    {
        if(!in_array($idproduct, $this->products)) 
        {
            $this->products[] = $idproduct;
        }
    }
    

    /**
     * remove element by id
     * @return void
     */
    abstract public function remove($id);
    
    /**
     * get child by id
     * @return Model_Produttori_Prodotti_Categorie_CategoryElement
     */    
    abstract public function getChild($id);    
    
    /**
     * get array childred
     * @return array
     */    
    abstract public function getChildren();    

    /**
     * get array products
     * @return array
     */    
    public function getProdotti()
    {
        return $this->products;
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
        return $this->_descrizione;
    }
        
}
