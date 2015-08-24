<?php
/**
 * Description of Movimento
 *
 * @author gullo
 */
interface Model_Cassa_InterfaceMovimento {
    
    
    public function getId();
    
    /**
     * Return Importo Movimento
     */
    public function getImporto();
    
    /**
     * Return Descrizione Movimento
     */
    public function getDescrizione();
    
    /**
     * Return Data
     */
    public function getData();
    
    /**
     * Return User (nome + cognome)
     */
    public function getUser();
    
    public function getUserEmail();
    
    public function getIdUser();
    
    
    /**
     * Return TRUE if the movimento is related to ORDINE
     */
    public function isRelatedToOrdine();
    
    /**
     * Return IdOrdine if it is related to ORDINE, FALSE if not
     */
    public function getIdOrdine();
    
    /**
     * Return Data Ordine, if it is related to an order
     */
    public function getDataOrdine();
    
    
}
