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
    const _CONTEXT = "Ordine";

    /**
     * Table ordini_prodotti fields
     * @var array
     */
    protected $_data = array(
            'idordine',
            'idlistino',
            'idprodotto',        
            'costo_ordine',
            'offerta_ordine',
            'sconto_ordine',
            'disponibile_ordine'
        );
    
    
    public function __construct(Model_Prodotto_Mediator_MediatorInterface $medium) 
    {
        parent::__construct($medium);
        // attach the Anagrafica Observer
        $this->attach( Model_Prodotto_Observer_Ordine::getInstance());
    }    
    
    /**
     * @return bool
     */    
    public function isDisponibile() {
        return ($this->getDisponibileOrdine() == "S") ? true : false;
    }

    
    
/* ***********************************************
 *  GET properties
 */
    
    /**
     * @return mixed
     */
    public function getIdOrdine()
    {
        return $this->_getValue("idordine");
    }

    /**
     * @return float
     */
    public function getCostoOrdine()
    {
        return (float)$this->_getValue("costo_ordine");
    }
    
    /**
     * @return bool
     */
    public function getOffertaOrdine()
    {
        return ($this->_getValue("offerta_ordine") == "S") ? true : false;
    }

    /**
     * @return int
     */
    public function getScontoOrdine()
    {
        return (int)$this->_getValue("sconto_ordine");
    }
    
    /**
     * @return bool
     */
    public function getDisponibileOrdine()
    {
        return ($this->_getValue("disponibile_ordine") == "S") ? true : false;
    }    
    
    
/* ***********************************************
 *  SET properties
 */
    
    /**
     * @param mixed $id
     */
    public function setIdOrdine($id)
    {
        $this->_setValue("idordine", $id);
    }
    
    /**
     * @param mixed $id
     */
    public function setIdListino($id)
    {
        $this->_setValue("idlistino", $id);
    }
    
    /**
     * @param mixed $id
     */
    public function setIdProdotto($id)
    {
        $this->_setValue("idprodotto", $id);
    }
    
    /**
     * @param float $c
     */
    public function setCostoOrdine($c)
    {
        $this->_setValue("costo_ordine", $c);
    }
    
    /**
     * @param mixed $flag
     */
    public function setOffertaOrdine($flag)
    {
        $this->_setValue("offerta_ordine", $this->filterFlag($flag));
    }
    
    /**
     * @param int $c
     */
    public function setScontoOrdine($c)
    {
        $this->_setValue("sconto_ordine", $c);
    }
    
    /**
     * @param mixed $flag
     */
    public function setDisponibileOrdine($flag)
    {
        $this->_setValue("disponibile_ordine", $this->filterFlag($flag));
    }
    
    
    
}
