<?php
/**
 * This is a PRODOTTO for ANAGRAFICA prodotti
 */
class Model_Prodotto_Mediator_Anagrafica
    extends Model_Prodotto_Mediator_AbstractProduct
{
    /**
     * Anagrafica context
     * @var string
     */
    protected $_context = "Anagrafica";
    
    
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
    protected $_tableName = "prodotti";
    
    /*
    public function __construct() {
        $this->attach(new Model_Prodotto_Observer_Anagrafica());
    }
    */

    
    
    /**
     * @return string
     */    
    public function getDescrizioneCosto()
    {
        return number_format($this->getCosto(), 2, ",", ".") . " " . $this->getUdmDescrizione();
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
 *  SET & GET for ALL properties
 */    
    /**
     * @param mixed $id
     */
    public function setIdProdotto($id)
    {
        $this->_setValue("idprodotto", $id);
    }
    
    /**
     * @return mixed
     */    
    public function getIdProdotto()
    {
        return $this->_getValue("idprodotto");
    }
    
    /**
     * @param mixed $id
     */
    public function setIdProduttore($id)
    {
        $this->_setValue("idproduttore", $id);
    }
    
    /**
     * @return mixed
     */    
    public function getIdProduttore()
    {
        return $this->_getValue("idproduttore");
    }

    /**
     * @param mixed $id
     */
    public function setIdCat($id)
    {
        $this->_setValue("idcat", $id);
    }
    
    /**
     * @return mixed
     */    
    public function getIdCat()
    {
        return $this->_getValue("idcat");
    }
    
    /**
     * @param mixed $id
     */
    public function setIdSubcat($id)
    {
        $this->_setValue("idsubcat", $id);
    }
    
    /**
     * @return mixed
     */    
    public function getIdSubcat()
    {
        return $this->_getValue("idsubcat");
    }

    /**
     * @param mixed $id
     */
    public function setIdUserCreator($id)
    {
        $this->_setValue("iduser_creator", $id);
    }
    
    /**
     * @return mixed
     */    
    public function getIdUserCreator()
    {
        return $this->_getValue("iduser_creator");
    }

    
    /**
     * @param string $c
     */
    public function setCodice($c)
    {
        $this->_setValue("codice", $c);
    }
    
    /**
     * @return string
     */    
    public function getCodice()
    {
        return $this->_getValue("codice");
    }

    /**
     * @param string $d
     */
    public function setDescrizione($d)
    {
        $this->_setValue("descrizione", $d);
    }
    
    /**
     * @return string
     */    
    public function getDescrizione()
    {
        return $this->_getValue("descrizione");
    }

    /**
     * @param string $udm
     */
    public function setUdm($udm)
    {
        $this->_setValue("udm", $udm);
    }
    
    /**
     * @return string
     */
    public function getUdm()
    {
        return $this->_getValue("udm");
    }

    /**
     * @param float $c
     */
    public function setCosto($c)
    {
        $this->_setValue("costo", $c);
    }
    
    /**
     * @return float
     */
    public function getCosto()
    {
        return $this->_getValue("costo");
    }
    
    /**
     * @param float $m
     */
    public function setMoltiplicatore($m)
    {
        $this->_setValue("moltiplicatore", $m);
    }
    
    /**
     * @return float
     */
    public function getMoltiplicatore()
    {
        return $this->_getValue("moltiplicatore");
    }
    
    /**
     * @param int $iva
     */
    public function setIva($iva)
    {
        $this->_setValue("aliquota_iva", $iva);
    }
    
    /**
     * @return int
     */    
    public function getIva()
    {
        return $this->_getValue("aliquota_iva");
    }
    
    /**
     * @param string $note
     */
    public function setNote($note)
    {
        $this->_setValue("note", $note);
    }
    
    /**
     * @return string
     */
    public function getNote()
    {
        return $this->_getValue("note");
    }

    /**
     * @param mixed $flag
     */
    public function setAttivo($flag)
    {
        $this->_setValue("attivo", $this->filterFlag($flag));
    }
    
    /**
     * @return bool
     */
    public function getAttivo()
    {
        return $this->_getValue("attivo");
    }

    /**
     * @param mixed $flag
     */
    public function setProduction($flag)
    {
        $this->_setValue("production", $this->filterFlag($flag));
    }
    
    /**
     * @return bool
     */
    public function getProduction()
    {
        return $this->_getValue("production");
    }

    /**
     * @param string $c
     */
    public function setCategoria($c)
    {
        $this->_setValue("categoria", $c);
    }
    
    /**
     * @return string
     */
    public function getCategoria()
    {
        return $this->_getValue("categoria");
    }

    /**
     * @param string $sc
     */
    public function setSubcategoria($sc)
    {
        $this->_setValue("categoria_sub", $sc);
    }
    
    /**
     * @return string
     */
    public function getSubcategoria()
    {
        return $this->_getValue("categoria_sub");
    }

    
    
}
