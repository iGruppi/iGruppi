<?php

/**
 * This is a Facade pattern to manage the LISTINO
 * 
 * @author gullo
 */
class Model_Listini_Listino {
    
    private $_dati = null;
    private $_groups = null;
    private $_prodotti = null;
    private $_categorie = null;
    
    
    function createListino(Model_AF_AbstractFactory $afListino)
    {
        $this->_dati = $afListino->createDati();
        $this->_groups = $afListino->createGruppi();
        $this->_prodotti = $afListino->createProdotti();
    }
    
    /**
     * @return Model_AF_Listino_Dati
     */
    public function getDati()
    {
        return $this->_dati;
    }
    
    /**
     *  set Dati in Model_AF_Listino_Dati
     * @param mixed (array|stdClass) $dati
     * @return Model_AF_Listino_Dati
     */
    public function setDati($dati)
    {
        $this->_dati->initDatiByObject($dati);
        return $this->_dati;
    }
    
    
    /**
     * @return Model_AF_Listino_Gruppi
     */
    public function getGroups()
    {
        return $this->_groups;
    }
    
    /**
     *  set Dati in Model_AF_Listino_Gruppi
     * @param mixed (array|stdClass) $groups
     * @return Model_AF_Listino_Gruppi
     */
    public function setGroups($groups)
    {
        $this->_groups->initDatiByObject($groups);
        return $this->_groups;
    }
    
    
    /**
     * @return Model_AF_Listino_Prodotti
     */
    public function getProdotti()
    {
        return $this->_prodotti;
    }
    
    /**
     *  set Dati in Model_AF_Listino_Prodotti
     * @param mixed (array|stdClass) $prodotti
     * @return Model_AF_Listino_Prodotti
     */
    public function setProdotti($prodotti)
    {
        $this->_prodotti->initDatiByObject($prodotti);
        return $this->_prodotti;
    }

/*  **************************************************************************
 *  CATEGORIE
 */    
    /**
     * return the Categorie Builder
     * @return mixed (Model_Builder_Categorie|null)
     */
    public function getAllCategorie()
    {
        if($this->_categorie instanceof Model_Builder_Categorie) {
            return $this->_categorie->getAll();
        }
        return null;
    }
    
    /**
     * return the Categorie Builder
     * @return Model_Builder_Categorie
     */
    public function getCategorie()
    {
        return $this->_categorie;
    }
    
    /**
     * set Model_Builder_Categorie
     * @param Model_Builder_Categorie $categorie
     */
    public function setCategorie(Model_Builder_Categorie $categorie)
    {
        $this->_categorie = $categorie;
    }
    
    
    
/*  **************************************************************************
 *  PERMISSION
 */    
    
    private function isReferenteProduttore()
    {
        $userSessionVal = new Zend_Session_Namespace('userSessionVal');
        return $userSessionVal->refObject->is_Referente($this->getDati()->getIdProduttore());
    }
    
    private function isOwner()
    {
        return ($this->getGroups()->getMyIdGroup() == $this->getGroups()->getMasterGroup()->getIdGroup());
    }
    
    public function canManageListino()
    {
        return $this->isReferenteProduttore();
    }
    
    public function canManageCondivisione()
    {
        return $this->isOwner();
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
