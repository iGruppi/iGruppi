<?php
/**
 * Description of SpesaFissaUtente
 *
 * @author gullo
 */
class Model_Ordini_Extra_SpesaFissaUtente extends Model_Ordini_Extra_Spesa 
{
    /**
     * Descrizione Tipo di spesa extra
     * @var string
     */
    protected $_descrizioneTipo = 'Spesa fissa per Utente';
    
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
        $n_users = count($ordine->getElencoUtenti());
        return $this->getCosto() * $n_users;
    }
    
    
    /**
     * Return Parziale for a user
     * @param int $iduser
     */
    public function getParzialeByIduser(Model_Ordini_CalcoliDecoratorInterface $ordine=null, $iduser=null)
    {
        return $this->getCosto();
    }
    
    
    
}
