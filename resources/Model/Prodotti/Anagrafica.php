<?php

/**
 * This is the Factory to manage the ANAGRAFICA PRODOTTI
 * 
 * @author gullo
 */
class Model_Prodotti_Anagrafica extends Model_AF_AbstractManipulator 
{

    /**
     * Create Listino by Factory method
     */
    public function __construct() {
        parent::create(new Model_AF_AnagraficaFactory());
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
