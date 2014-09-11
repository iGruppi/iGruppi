<?php

/**
 * This is the Abstract Manipulator class for the Abstract Factory models
 * 
 * @author gullo
 */
abstract class Model_AF_AbstractManipulator {
    
    private $_dati = null;
    private $_groups = null;
    private $_prodotti = null;
    private $_categorie = null;    
    
    function create(Model_AF_AbstractFactory $af)
    {
        $this->_dati = $af->createDati();
        $this->_groups = $af->createGruppi();
        $this->_prodotti = $af->createProdotti();
    }
    
    /**
     * @return Model_AF_Dati
     */
    public function getDati()
    {
        return $this->_dati;
    }
    
    /**
     *  set Dati in Model_AF_Dati
     * @param mixed (array|stdClass) $dati
     * @return Model_AF_Dati
     */
    public function setDati($dati)
    {
        $this->_dati->initDatiByObject($dati);
        return $this->_dati;
    }
    
    
    /**
     * @return Model_AF_Gruppi
     */
    public function getGroups()
    {
        return $this->_groups;
    }
    
    /**
     *  set Dati in Model_AF_Gruppi
     * @param mixed (array|stdClass) $groups
     * @return Model_AF_Gruppi
     */
    public function setGroups($groups)
    {
        $this->_groups->initDatiByObject($groups);
        return $this->_groups;
    }
    
    
    /**
     * @return Model_AF_Prodotti
     */
    public function getProdotti()
    {
        return $this->_prodotti;
    }
    
    /**
     *  set Dati in Model_AF_Prodotti
     * @param mixed (array|stdClass) $prodotti
     * @return Model_AF_Prodotti
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
 *  SAVE
 */    

    
    /**
     * save data to DB
     */
    abstract public function save();
    
}
