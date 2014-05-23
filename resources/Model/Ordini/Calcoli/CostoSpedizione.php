<?php

/**
 * Description of CostoSpedizione
 * 
 * @author gullo
 */
class Model_Ordini_Calcoli_CostoSpedizione {
    
    private $ocuObj;
    private $cs = null;
    
    function __construct(Model_Ordini_Calcoli_Utenti &$ocuObj) {
        $this->ocuObj = $ocuObj;
    }
    
    function getCostoSpedizioneRipartito() {
        if(is_null($this->cs))
        {
            $this->cs = 0;
            if($this->ocuObj->hasCostoSpedizione() && count($this->ocuObj->getProdottiUtenti()) > 0) 
            {
                $numUsers = 0;
                foreach($this->ocuObj->getProdottiUtenti() AS $iduser => $arU) 
                {
                    $csu = $this->ocuObj->getTotaleByIduser($iduser);
                    if($csu > 0)
                        $numUsers++;
                }
                if($numUsers > 0) {
                    $this->cs = ($this->ocuObj->getCostoSpedizione() / $numUsers);
                }
            }
        }
        return $this->cs;
    }
    
    function getCostoSpedizioneRipartitoByIduser($iduser) 
    {
        // Paga Costo Spedizione solo se il totale ordinato è > 0
        // questo per evitare che paghi solo le spese di spedizione quando ordina 1 solo prodotto che poi risulta NON disponibile
        return ($this->ocuObj->hasCostoSpedizione() && $this->ocuObj->getTotaleByIduser($iduser) > 0) ? $this->getCostoSpedizioneRipartito() : 0;
    }
    
}