<?php
/**
 * This is a PRODOTTO for ORDINI
 */
class Model_Prodotto_Mediator_Ordine 
    extends Model_Prodotto_Mediator_AbstractProduct
{

    /**
     * Ordine context
     * @var string
     */
    protected $_context = "Ordine";
    
    protected $_data = array(
            'idordine',
            'costo_ordine',
            'offerta_ordine',
            'sconto_ordine',
            'disponibile_ordine'
        );
    
    
    /**
     * @return bool
     */    
    public function isDisponibile() {
        return $this->getDisponibileOrdine();
    }
 
    
    
/* ***********************************************
 *  SET & GET for ALL properties
 */
    
    /**
     * @param mixed $id
     */
    public function setIdOrdine($id)
    {
        $this->_setValue("idordine", $id);
    }
    
    /**
     * @return mixed
     */
    public function getIdOrdine()
    {
        return $this->_getValue("idordine");
    }

    /**
     * @param float $c
     */
    public function setCostoOrdine($c)
    {
        $this->_setValue("costo_ordine", $c);
    }
    
    /**
     * @return float
     */
    public function getCostoOrdine()
    {
        return $this->_getValue("costo_ordine");
    }
    
    /**
     * @param mixed $flag
     */
    public function setOffertaOrdine($flag)
    {
        $this->_setValue("offerta_ordine", $this->filterFlag($flag));
    }
    
    /**
     * @return bool
     */
    public function getOffertaOrdine()
    {
        return $this->_getValue("offerta_ordine");
    }

    /**
     * @param int $c
     */
    public function setScontoOrdine($c)
    {
        $this->_setValue("sconto_ordine", $c);
    }
    
    /**
     * @return int
     */
    public function getScontoOrdine()
    {
        return $this->_getValue("sconto_ordine");
    }
    
    /**
     * @param mixed $flag
     */
    public function setDisponibileOrdine($flag)
    {
        $this->_setValue("disponibile_ordine", $this->filterFlag($flag));
    }
    
    /**
     * @return bool
     */
    public function getDisponibileOrdine()
    {
        return $this->_getValue("disponibile_ordine");
    }    
}
