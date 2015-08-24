<?php
/**
 * Description of SpesaRipartitaImporto
 *
 * @author gullo
 */
class Model_Ordini_Extra_SpesaRipartitaImporto extends Model_Ordini_Extra_Spesa 
{
    /**
     * Descrizione Tipo di spesa extra
     * @var string
     */
    protected $_descrizioneTipo = 'Ripartita per Utente in % su Importo';

    /**
     * Call the constructor in Model_Ordini_Extra_Spesa
     * @param string $descrizione
     * @param float $costo
     */
    public function __construct($descrizione, $costo, $tipo) {
        parent::__construct($descrizione, $costo, $tipo);
    }
    
    /**
     * Return Totale Gruppo for this kind of Spesa Extra
     */
    public function getTotaleGruppo(Model_Ordini_CalcoliDecoratorInterface $ordine=null)
    {
        return $this->getCosto();
    }
    
    
    /**
     * Return Parziale for a user
     * @param int $iduser
     */
    public function getParzialeByIduser(Model_Ordini_CalcoliDecoratorInterface $ordine=null, $iduser=null)
    {
        $totale_ordine = $ordine->getTotale();
        $totale_utente = $ordine->getTotaleByIduser($iduser);
        if($totale_utente > 0)
        {
            return $totale_utente / $totale_ordine * $this->getCosto();
        }
        return 0;
    }
    
    
    
}
