<?php
/**
 * Class Cat Element
 */
class Model_Categorie_CatElement 
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
    
    
    public function getSubcat()
    {
        $ar = array();
        $iterator = $this->createIterator();
        if($iterator->hasChildren())
        {
            foreach($iterator AS $ch)
            {
                if($ch instanceof Model_Categorie_SubcatElement)
                {
                    $ar[] = $ch;
                }
            }
        }
        return $ar;
    }

    public function getCatById($id)
    {
        foreach($this->createIterator() as $ch)
        {
            if($ch instanceof Model_Categorie_CatElement && $ch->getId() === $id)
            {
                return $ch;
            }
        }
        return null;
    }

    public function getSubCatById($id)
    {
        foreach($this->createIterator() as $ch)
        {
            if($ch instanceof Model_Categorie_SubcatElement && $ch->getId() === $id)
            {
                return $ch;
            }
        }
        return null;
    }
}
