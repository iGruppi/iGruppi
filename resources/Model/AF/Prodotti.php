<?php
/**
 * This is the Abstract Product for PRODOTTI
 */
abstract class Model_AF_Prodotti extends Model_AF_AbstractHandlerCoR
{
    /**
     * @var array
     */
    private $_prodotti = array();
    

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
                $this->addProdotto($values);
            }
        }
    }
    
    /**
     * called by the superclass for some shared steps
     * @param stdClass $values
     */
    protected function addProdotto(stdClass $values) { }

    
    
    /**
     * get the number of the products available
     * @return int
     */
    public function count()
    {
        return count($this->_prodotti);
    }

    /**
     * return Prodotto in base a $idprodotto
     * 
     * @param mixed $idprodotto
     * @return mixed (null | Model_Builder_Prodotto_Parts_Product)
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
     * used from the parent class to ADD products to the array
     * It MUST be protected because the parent class has to instantiate the Builder before adding it
     * @param Model_Builder_Prodotto_Parts_Product $prodotto
     */
    protected function _setProdotto(Model_Builder_Prodotto_Parts_Product $prodotto)
    {
        $this->_prodotti[$prodotto->getIdProdotto()] = $prodotto;
    }

    /**
     * get array Products
     * @return array
     */
    public function getProdotti()
    {
        return $this->_prodotti;
    }
    
}
