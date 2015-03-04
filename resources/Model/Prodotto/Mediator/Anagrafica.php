<?php
/**
 * This is a PRODOTTO for ANAGRAFICA prodotti
 */
class Model_Prodotto_Mediator_Anagrafica
    extends Model_Prodotto_Mediator_AbstractProduct
{
    /**
     * Table prodotti fields
     * @var array
     */
    protected $_data = array(
            'idprodotto',
            'idproduttore',
            'idsubcat',
            'iduser_creator',
            'codice',
            'descrizione',
            'udm',
            'moltiplicatore',
            'costo',
            'aliquota_iva',
            'note',
            'attivo',
            'production'        
        );   
    

    public function __construct(Model_Prodotto_Mediator_MediatorInterface $medium) 
    {
        parent::__construct($medium);
    }
    
    
    /**
     * @return string
     */    
    public function getDescrizionePezzatura()
    {
        $arUdm = Model_Prodotto_UdM::getArWithMultip();
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
        $arUdm = Model_Prodotto_UdM::getArWithMultip();
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
     * @return bool
     */    
    public function hasIva() 
    {
        return ($this->getIva() > 0);
    }

        
/* *******************************************
 *  GET properties
 */ 
    
    /**
     * @return mixed
     */    
    public function getIdProdotto()
    {
        return $this->_getValue("idprodotto");
    }
    
    /**
     * @return mixed
     */    
    public function getIdProduttore()
    {
        return $this->_getValue("idproduttore");
    }

    /**
     * @return mixed
     */    
    public function getIdCat()
    {
        return $this->_getValue("idcat");
    }
    
    /**
     * @return mixed
     */    
    public function getIdSubcat()
    {
        return $this->_getValue("idsubcat");
    }

    /**
     * @return mixed
     */    
    public function getIdUserCreator()
    {
        return $this->_getValue("iduser_creator");
    }

    /**
     * @return string
     */    
    public function getCodice()
    {
        return $this->_getValue("codice");
    }

    /**
     * @return string
     */    
    public function getDescrizioneAnagrafica()
    {
        return $this->_getValue("descrizione");
    }

    /**
     * @return string
     */
    public function getUdm()
    {
        return $this->_getValue("udm");
    }

    /**
     * @return float
     */
    public function getCostoAnagrafica()
    {
        return $this->_getValue("costo");
    }
    
    /**
     * @return float
     */
    public function getMoltiplicatore()
    {
        return $this->_getValue("moltiplicatore");
    }
    
    /**
     * @return int
     */    
    public function getIva()
    {
        return $this->_getValue("aliquota_iva");
    }
    
    /**
     * @return string
     */
    public function getNoteAnagrafica()
    {
        return $this->_getValue("note");
    }

    /**
     * @return bool
     */
    public function getAttivoAnagrafica()
    {
        return $this->_getValue("attivo");
    }

    /**
     * @return bool
     */
    public function getProduction()
    {
        return $this->_getValue("production");
    }

    /**
     * @return string
     */
    public function getCategoria()
    {
        return $this->_getValue("categoria");
    }

    /**
     * @return string
     */
    public function getSubcategoria()
    {
        return $this->_getValue("categoria_sub");
    }

    
/* *******************************************
 *  SET properties
 */    

    /**
     * @param mixed $id
     */
    public function setIdProdotto($id)
    {
        $this->_setValue("idprodotto", $id);
    }
    
    /**
     * @param mixed $id
     */
    public function setIdProduttore($id)
    {
        $this->_setValue("idproduttore", $id);
    }
    
    /**
     * Cannot set Categoria
     * @param string $c
     * @return null
    public function setIdCat($id)
    {
        throw new Model_Prodotto_Mediator_Exception('Cannot set IdCat!');
    }
     */
    
    /**
     * @param mixed $id
     */
    public function setIdSubcat($id)
    {
        $this->_setValue("idsubcat", $id);
    }
    
    /**
     * @param mixed $id
     */
    public function setIdUserCreator($id)
    {
        $this->_setValue("iduser_creator", $id);
    }
    
    /**
     * @param string $c
     */
    public function setCodice($c)
    {
        $this->_setValue("codice", $c);
    }
    
    /**
     * @param string $d
     */
    public function setDescrizione($d)
    {
        $this->_setValue("descrizione", $d);
    }
    
    /**
     * @param string $udm
     */
    public function setUdm($udm)
    {
        $this->_setValue("udm", $udm);
    }
    
    /**
     * @param float $c
     */
    public function setCosto($c)
    {
        $this->_setValue("costo", $c);
    }
    
    /**
     * @param float $m
     */
    public function setMoltiplicatore($m)
    {
        $this->_setValue("moltiplicatore", $m);
    }
    
    /**
     * @param int $iva
     */
    public function setIva($iva)
    {
        $this->_setValue("aliquota_iva", $iva);
    }
    
    /**
     * @param string $note
     */
    public function setNote($note)
    {
        $this->_setValue("note", $note);
    }
    
    /**
     * @param mixed $flag
     */
    public function setAttivo($flag)
    {
        $this->_setValue("attivo", $this->filterFlag($flag));
    }
    
    /**
     * @param mixed $flag
     */
    public function setProduction($flag)
    {
        $this->_setValue("production", $this->filterFlag($flag));
    }
    
    /**
     * Cannot set Categoria
     * @param string $c
     * @return null
    public function setCategoria($c)
    {
        throw new Model_Prodotto_Mediator_Exception('Cannot set Categoria!');
    }
     */
    
    /**
     * Cannot set Categoria
     * @param string $cc
     * @return null
    public function setSubcategoria($sc)
    {
        throw new Model_Prodotto_Mediator_Exception('Cannot set SubCategoria!');
    }
     */
    
    
}
