<?php
/**
 * Description of CalcoliDecorator, it is a DECORATOR of Model_Ordini_Ordine
 * 
 * @author Davide Gullo <gullo at m4ss.net>
 * 
 */
class Model_Ordini_CalcoliDecorator extends Model_Ordini_CalcoliAbstract
{
    /**
     * IdGroup for this Ordine
     * @var int
     */
    protected $_idgroup;
    
    /**
     * SET idgroup to use by default
     * @param int $idgroup
     */
    public function setIdgroup($idgroup)
    {
        $this->_idgroup = $idgroup;
    }
    
    
    /**
     * TOTALE ORDINE (incluso Extra)
     * @return float
     */
    public function getTotaleConExtra() 
    {
        if($this->getSpeseExtra()->has()) 
        {
            $totaleExtra = 0;
            foreach($this->getSpeseExtra()->get() AS $extra)
            {
                $totaleExtra += $extra->getTotaleGruppo($this);
            }
            return ($this->getTotale() + $totaleExtra);
        } else {
            return $this->getTotale();
        }
    }
    
    /**
     * TOTALE per UTENTE (incluso Extra)
     * @param type $iduser
     * @return type 
     */
    public function getTotaleConExtraByIduser($iduser) 
    {
        if($this->getSpeseExtra()->has()) 
        {
            $totaleExtra = 0;
            foreach($this->getSpeseExtra()->get() AS $extra)
            {
                $totaleExtra += $extra->getParzialeByIduser($this, $iduser);
            }
            return ($this->getTotaleByIduser($iduser) + $totaleExtra);
        } else {
            return $this->getTotaleByIduser($iduser);
        }
    }
    
/***********************************************
 *  EXTRA
 */
    
    /**
     * Return Array with TOTALE SPESE EXTRA
     * @return array
     */
    public function getSpeseExtra_Totale()
    {
        $arExtra = array();
        if($this->getSpeseExtra()->has()) {
            foreach ($this->getSpeseExtra()->get() AS $extra) {
                $arExtra[] = array(
                    'descrizione' => $extra->getDescrizione(),
                    'descrizioneTipo' => $extra->getDescrizioneTipo(),
                    'totale'      => $extra->getTotaleGruppo($this)
                );
            }
        }
        return $arExtra;
    }

    /**
     * Return Array with TOTALE SPESE EXTRA for any UTENTE
     * @return array
     */
    public function getSpeseExtra_Utente($iduser)
    {
        $arExtra = array();
        if($this->getSpeseExtra()->has()) {
            foreach ($this->getSpeseExtra()->get() AS $extra) {
                $arExtra[] = array(
                    'descrizione' => $extra->getDescrizione(),
                    'descrizioneTipo' => $extra->getDescrizioneTipo(),
                    'parziale_utente'      => $extra->getParzialeByIduser($this, $iduser)
                );
            }
        }
        return $arExtra;
    }

    
}