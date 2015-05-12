<?php
/**
 * Class Subcat Element
 */
class Model_Categorie_SubcatElement 
    extends Model_Categorie_CompositeElement
{
    
    /**
     * build a Subcat element
     * @param type $id
     * @param type $descrizione
     */
    public function __construct($id, $descrizione)
    {
        $this->id = $id;
        $this->descrizione = $descrizione;
        $this->elements = array();        
    }
    
    
    public function getProdotti()
    {
        $ar = array();
        $iterator = $this->createIterator();
        if($iterator->hasChildren())
        {
            foreach($iterator AS $ch)
            {
                if($ch instanceof Model_Categorie_ProdottoElement)
                {
                    $ar[] = $ch;
                }
            }
        }
        return $ar;
    }
    
    public function getProduttori()
    {
        $ar = array();
        $iterator = $this->createIterator();
        if($iterator->hasChildren())
        {
            foreach($iterator AS $ch)
            {
                if($ch instanceof Model_Categorie_ProduttoreElement)
                {
                    $ar[] = $ch;
                }
            }
        }
        return $ar;
    }
    
    public function getProdottoById($id)
    {
        foreach($this->createIterator() as $ch)
        {
            if($ch instanceof Model_Categorie_ProdottoElement && $ch->getId() === $id) {
                return $ch;
            }
        }
        return null;
    }
    
    public function getProduttoreById($id)
    {
        foreach($this->createIterator() as $ch)
        {
            if($ch instanceof Model_Categorie_ProduttoreElement && $ch->getId() === $id) {
                return $ch;
            }
        }
        return null;
    }
}
