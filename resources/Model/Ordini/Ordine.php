<?php
/**
 * This is the Factory to manage the ORDINE
 * 
 * @author gullo
 */
class Model_Ordini_Ordine extends Model_AF_AbstractManipulator 
{

    /**
     * Create Listino Factory
     */
    public function __construct() {
        parent::create(new Model_AF_OrdineFactory());
    }
            
    
    
/*  **************************************************************************
 *  PERMISSION
 */    
    
    private function isReferenteOrdine()
    {
        return true;
    }
    
    public function canManageOrdine()
    {
        return $this->isReferenteOrdine();
    }
    
    public function canManageCondivisione()
    {
        return $this->isReferenteOrdine();
    }

    
/*  **************************************************************************
 *  SAVE CHANGES TO DB
 */    
    
    public function save()
    {
        // save Dati
        $res1 = $this->getDati()->saveToDB();
        // save Groups
        $res2 = $this->getGroups()->saveToDB();
        
        return ($res1 && $res2);
    }
    
}
