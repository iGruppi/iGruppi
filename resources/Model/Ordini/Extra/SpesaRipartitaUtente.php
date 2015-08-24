<?php
/**
 * Description of SpesaRipartitaUtente
 *
 * @author gullo
 */
class Model_Ordini_Extra_SpesaRipartitaUtente extends Model_Ordini_Extra_Spesa 
{
    /**
     * Descrizione Tipo di spesa extra
     * @var string
     */
    protected $_descrizioneTipo = 'Ripartita per Utente';

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
        $n_users = count($ordine->getElencoUtenti());
        if($n_users > 0)
        {
            return $this->getCosto() / $n_users;
        }
        return 0;
    }
    
    
    
}
