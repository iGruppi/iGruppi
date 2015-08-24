<?php
/**
 * This is the Abstract Product for PRODOTTI
 */
abstract class Model_AF_Prodotti extends Model_AF_AbstractHandlerCoR
{
    /**
     * @var array
     */
    protected $_prodotti = array();
    

    /**
     * add Products by an array of data
     * @param array $listProd
     */
    public function initProdotti_ByObject($listProd)
    {
        if(count($listProd) > 0)
        {
            foreach ($listProd as $values)
            {
                // It calls the addProdotto method of the child class
                $this->_addProdotto($values);
            }
        }
    }

    /**
     * add Prodotto to the list
     * @param stdClass $values
     */
    protected function _addProdotto(stdClass $values)
    {
        $prodotto = $this->_buildProdotto();
        $prodotto->initByObject($values);
//        Zend_Debug::dump($prodotto);die;
        if($prodotto->getIdProdotto() > 0) 
        {
            // add prodotto to the list
            $this->_prodotti[$prodotto->getIdProdotto()] = $prodotto;
            
        } else {
            throw new MyFw_Exception("IdProdotto not exists!");
        }
    }
    
    
    /**
     * get the number of the products available
     * @return int
     */
    public function countProdotti()
    {
        return count($this->_prodotti);
    }

    /**
     * return Prodotto in base a $idprodotto
     * 
     * @param mixed $idprodotto
     * @return mixed (null | Model_Prodotto_Mediator_MediatorInterface)
     */
    public function getProdottoById($idprodotto)
    {
        if(isset($this->_prodotti[$idprodotto]))
        {
            return $this->_prodotti[$idprodotto];
        }
        return null;
    }

    /**
     * get array Products
     * @return array
     */
    public function getProdotti()
    {
        return $this->_prodotti;
    }
    

    
    /**
     * Save Prodotti to DB
     * It calls the UPDATE for any part: Anagrafica, Listino, Ordine
     * @return void
     */
    public function saveToDB_Prodotti()
    {
        if($this->countProdotti() > 0)
        {
            foreach($this->getProdotti() AS $prodotto)
            {
                $prodotto->saveToDB_Prodotto();
            }
        }
    }
    
}
