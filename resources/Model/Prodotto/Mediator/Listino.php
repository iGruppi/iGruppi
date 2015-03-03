<?php
/**
 * This is a PRODOTTO for LISTINO
 */
class Model_Prodotto_Mediator_Listino
    extends Model_Prodotto_Mediator_AbstractProduct
{
    /**
     * Listino context
     * @var string
     */
    const _CONTEXT = "Listino";

    /**
     * Table listini_prodotti fields
     * @var array
     */
    protected $_data = array(
            'idlistino',
            'idprodotto',
            'descrizione_listino',
            'costo_listino',
            'note_listino',
            'attivo_listino'
        );
    
    public function __construct(Model_Prodotto_Mediator_MediatorInterface $medium) 
    {
        parent::__construct($medium);
    }
    
    /**
     * Verifica se il prodotto è nel Listino (se NON esiste il record in listini_prodotti idlistino è NULL!)
     * @return bool
     */
    public function isInListino() 
    {
        return is_null($this->getIdListino()) ? false : true;
    }
    
    
/* ***********************************************
 *  GET properties
 */
    
    /**
     * @return mixed
     */
    public function getIdListino()
    {
        return $this->_getValue("idlistino");
    }
    
    /**
     * @return string
     */    
    public function getDescrizioneListino()
    {
        return $this->_getValue("descrizione_listino");
    }

    /**
     * @return float
     */
    public function getCostoListino()
    {
        return $this->_getValue("costo_listino");
    }
    
    /**
     * @return string
     */
    public function getNoteListino()
    {
        return $this->_getValue("note_listino");
    }

    /**
     * @return bool
     */
    public function getAttivoListino()
    {
        return $this->_getValue("attivo_listino");
    }

/* ***********************************************
 *  SET properties
 */

    
    /**
     * @param mixed $id
     */
    public function setIdListino($id)
    {
        $this->_setValue("idlistino", $id);
    }
    
    /**
     * @param mixed $id
     */
    public function setIdProdotto($id)
    {
        $this->_setValue("idprodotto", $id);
    }
    
    
    /**
     * @param string $d
     */
    public function setDescrizioneListino($d)
    {
        $this->_setValue("descrizione_listino", $d);
    }
    
    /**
     * @param float $c
     */
    public function setCostoListino($c)
    {
        $this->_setValue("costo_listino", $c);
    }
    
    /**
     * @param string $note
     */
    public function setNoteListino($note)
    {
        $this->_setValue("note_listino", $note);
    }
    
    /**
     * @param mixed $flag
     */
    public function setAttivoListino($flag)
    {
        $this->_setValue("attivo_listino", $this->filterFlag($flag));
    }
    
    
}
