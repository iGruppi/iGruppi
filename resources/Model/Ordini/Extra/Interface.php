<?php
/**
 * Description of generic Spesa Interface
 * @author gullo
 */
interface Model_Ordini_Extra_Interface {
    
    /**
     * Init class creating spesa 
     */
    public function __construct($descrizione, $costo, $tipo);
    
    /**
     * Return Descrizione of Extra
     */
    public function getDescrizione();
    
    /**
     * Return Costo of Extra
     */
    public function getCosto();
    
    /**
     * Return Tipo of Extra
     */
    public function getTipo();

    /**
     * Return Totale Spesa per Gruppo
     */
    public function getTotaleGruppo(Model_Ordini_CalcoliDecoratorInterface $ordine=null);
    
    /**
     * Return Parziale by Iduser
     */
    public function getParzialeByIduser(Model_Ordini_CalcoliDecoratorInterface $ordine=null, $iduser=null);
}
