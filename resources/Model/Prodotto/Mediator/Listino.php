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
    protected $_context = "Listino";
    
    /**
     * Table listini_prodotti fields
     * @var array
     */
    protected $_data = array(
            'idlistino',
            'descrizione_listino',
            'costo_listino',
            'note_listino',
            'attivo_listino'
        );
    
    /**
     * Verifica se il prodotto è nel Listino (se NON esiste il record in listini_prodotti idlistino è NULL!)
     * @return bool
     */
    public function isInListino() 
    {
        return is_null($this->getIdListino()) ? false : true;
    }
    
    
/* ***********************************************
 *  SET & GET for ALL properties
 */
    
    /**
     * @param mixed $id
     */
    public function setIdListino($id)
    {
        $this->_setValue("idlistino", $id);
    }
    
    /**
     * @return mixed
     */
    public function getIdListino()
    {
        return $this->_getValue("idlistino");
    }
    
    /**
     * @param string $d
     */
    public function setDescrizioneListino($d)
    {
        $this->_setValue("descrizione_listino", $d);
    }
    
    /**
     * @return string
     */    
    public function getDescrizioneListino()
    {
        return $this->_getValue("descrizione_listino");
    }

    /**
     * @param float $c
     */
    public function setCostoListino($c)
    {
        $this->_setValue("costo_listino", $c);
    }
    
    /**
     * @return float
     */
    public function getCostoListino()
    {
        return $this->_getValue("costo_listino");
    }
    
    /**
     * @param string $note
     */
    public function setNoteListino($note)
    {
        $this->_setValue("note_listino", $note);
    }
    
    /**
     * @return string
     */
    public function getNoteListino()
    {
        return $this->_getValue("note_listino");
    }

    /**
     * @param mixed $flag
     */
    public function setAttivoListino($flag)
    {
        $this->_setValue("attivo_listino", $this->filterFlag($flag));
    }
    
    /**
     * @return bool
     */
    public function getAttivoListino()
    {
        return $this->_getValue("attivo_listino");
    }

    
}
