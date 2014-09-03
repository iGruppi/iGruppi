<?php
/**
 * Class CategoryElement
 */
abstract class Model_Produttori_Prodotti_Categorie_CategoryElement
{
    
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
     * remove element
     * @return void
     */
//    abstract public function remove(Model_Produttori_Prodotti_Categorie_CategoryElement $element);
    
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
     * return id of the element
     * @return id
     */
    abstract public function getId();            
    
    /**
     * return descrizione
     * @return string
     */
    abstract public function getDescrizione();
    
}
