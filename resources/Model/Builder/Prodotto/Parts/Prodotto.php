<?php
/**
 * This is a PRODOTTO for ANAGRAFICA prodotti
 */
class Model_Builder_Prodotto_Parts_Prodotto
    extends Model_Builder_Prodotto_Parts_Product

{
    
    /**
     * @return string
     */    
    public function getDescrizioneCosto()
    {
        return number_format($this->getCosto(), 2, ",", ".") . " " . $this->getUdmDescrizione();
    }
    
    /**
     * @return string
     */    
    public function getDescrizionePezzatura()
    {
        $arUdm = Model_Produttori_Prodotti_UdM::getArWithMultip();
        $pp = "";
        if( $this->hasPezzatura() ) {
            $pp .= round($this->getMoltiplicatore(), $arUdm[$this->getUdm()]["ndec"]) . " " . $arUdm[$this->getUdm()]["label"];
        }
        return $pp;
    }
    
    /**
     * @return bool
     */    
    public function hasPezzatura()
    {
        $arUdm = Model_Produttori_Prodotti_UdM::getArWithMultip();
        return ( isset($arUdm[$this->getUdm()]) && $this->getMoltiplicatore() != 1 ) ? true : false;
    }
    
    /**
     * @return string
     */    
    public function getUdmDescrizione()
    {
        $fpz = ($this->hasPezzatura()) ? "**" : "";
        return "&euro; / " . $this->getUdm() . $fpz;
    }
        
    /**
     * @return float
     */    
    public function getCostoSenzaIva() 
    {
        if($this->getAliquotaIva() > 0) {
            $cc =  ($this->getCosto() / ($this->getAliquotaIva() / 100 + 1));
            return round( $cc, 2, PHP_ROUND_HALF_UP);
        } else {
            return $this->getCosto();
        }
    }
    
    /**
     * @return string
     */    
    public function getAliquotaIva() 
    {
        if($this->hasAliquotaIva()) 
        {
            return $this->getIva();
        }
        return 0;
    }
    
    /**
     * @return string
     */    
    public function hasAliquotaIva() 
    {
        return (!is_null($this->getIva()) && $this->getIva() > 0);
    }

    /**
     * 
     * @return array
     */    
    public function dumpValuesForDB() { 
        
        return array();
    }
    
}