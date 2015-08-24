<?php
/**
 * Description of Movimento
 *
 * @author gullo
 */
class Model_Cassa_Movimento implements Model_Cassa_InterfaceMovimento 
{
    
    private $_values;
    
    public function __construct($values) {
        $this->_values = $values;
    }
    
    public function getId()
    {
        return $this->_values["idmovimento"];
    }
    
    public function getImporto()
    {
        return $this->_values["importo"];
    }
    
    /**
     * Return Descrizione Movimento
     */
    public function getDescrizione()
    {
        return $this->_values["descrizione"];
    }
    
    /**
     * Return Data
     */
    public function getData($format = null)
    {
        if(is_null($format))
        {
            return $this->_values["data"];
        } else {
            $dt = DateTime::createFromFormat("Y-m-d H:i:s", $this->_values["data"]);
            return $dt->format($format);
        }
    }
    
    /**
     * Return User (nome + cognome)
     */
    public function getUser()
    {
        return $this->_values["nome"] . " " . $this->_values["cognome"];
    }
    
    public function getUserEmail()
    {
        return $this->_values["email"];
    }
    
    public function getIdUser()
    {
        return $this->_values["iduser"];
    }
    
    
    
    /**
     * Return TRUE if the movimento is related to ORDINE
     * @return bool
     */
    public function isRelatedToOrdine()
    {
        return !is_null($this->getIdOrdine());
    }
    
    /**
     * Return IdOrdine if it is related to ORDINE, FALSE if not
     * @return int|null
     */
    public function getIdOrdine()
    {
        return $this->_values["idordine"];
    }
    
    /**
     * Return Data Ordine, if it is related to an order
     * @return date|null
     */
    public function getDataOrdine()
    {
        return $this->_values["data_inizio"];
    }
    

    
}
