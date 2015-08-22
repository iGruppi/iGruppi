<?php
/**
 * Description of generic Spesa
 * @author gullo
 */
abstract class Model_Ordini_Extra_Spesa implements Model_Ordini_Extra_Interface 
{
    /**
     * Descrizione spesa extra
     * @var string
     */
    private $_descrizione;
    
    /**
     * Costo spesa extra
     * @var float
     */
    private $_costo;
    
    /**
     * Tipo Spesa Extra
     * @var string
     */
    private $_tipo;
    
    /**
     * Init values by serialized Array
     * @param string $descrizione
     * @param float $costo
     * @param string $tipo
     */
    public function __construct($descrizione, $costo, $tipo) 
    {
        $this->_descrizione = $descrizione;
        $this->_costo = $costo;
        $this->_tipo = $tipo;
    }
    
    
    /**
     * Return Descrizione of Extra
     * @return string
     */
    public function getDescrizione()
    {
        return $this->_descrizione;
    }
    
    /**
     * Return Costo of Extra
     * @return float
     */
    public function getCosto()
    {
        return $this->_costo;
    }
    
    /**
     * Return Tipo of Extra
     * @return string
     */
    public function getTipo()
    {
        return $this->_tipo;
    }
    
    public function getDescrizioneTipo()
    {
        return $this->_descrizioneTipo;
    }
    
}
