<?php
/**
 * Product is the product of the Builder, it must be extended
 */
abstract class Model_Builder_Prodotto_Parts_Product
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @param string $key
     * @param mixed  $value
     * @return void
     */
    public function setPart($key, $value)
    {
        $this->data[$key] = $value;
    }
    
    /**
     * @param stdClass $obj
     * @return void
     * 
     * @todo
     * I know! 
     * It should be set step-by-step in the super classes. I will do it...
     */
    public function setDataByObject(stdClass $obj)
    {
        try {
            foreach ($obj AS $f => $v) 
            {
                $this->_setValue($f, $v);
            }
        } catch (MyFw_Exception $exc) {
            $exc->displayError();
        }
    }
    
    private function _setValue($f, $v)
    {
        if( isset($this->data[$f]) ) {
            $this->data[$f]->set($v);
        } else {
            throw new MyFw_Exception("Part '$f' NOT exists in Product!");
        }
    }

/* *******************************************
 *  GET Strategy for Costo
 */    
    
    /*
    * Overloading __call
    * This try to call a method in Costo_ContextStrategy
    */
    public function __call ( $method, $args )
    {
        // controllo esistenza metodo
        if( method_exists( $this, $method ) )
        {
            call_user_func_array(array($this, $method), $args);
        } else {
            try {
                // get StrategyContext
                $sc = new Model_Prodotti_Costo_ContextStrategy($this);
                $sc->setContext($this->_context);
                return $sc->$method($args);
                
            } catch (MyFw_Exception $exc) {
                $exc->displayError();
            }
        }
    }
    
    
    
/* *******************************************
 *  SET & GET for ALL properties
 */    
    /**
     * @param mixed $id
     */
    public function setIdProdotto($id)
    {
        $this->data["idprodotto"]->set($id);
    }
    
    /**
     * @return mixed
     */    
    public function getIdProdotto()
    {
        return $this->data["idprodotto"]->get();
    }
    
    /**
     * @param mixed $id
     */
    public function setIdProduttore($id)
    {
        $this->data["idproduttore"]->set($id);
    }
    
    /**
     * @return mixed
     */    
    public function getIdProduttore()
    {
        return $this->data["idproduttore"]->get();
    }

    /**
     * @param mixed $id
     */
    public function setIdCat($id)
    {
        $this->data["idcat"]->set($id);
    }
    
    /**
     * @return mixed
     */    
    public function getIdCat()
    {
        return $this->data["idcat"]->get();
    }
    
    /**
     * @param mixed $id
     */
    public function setIdSubcat($id)
    {
        $this->data["idsubcat"]->set($id);
    }
    
    /**
     * @return mixed
     */    
    public function getIdSubcat()
    {
        return $this->data["idsubcat"]->get();
    }

    /**
     * @param mixed $id
     */
    public function setIdUserCreator($id)
    {
        $this->data["iduser_creator"]->set($id);
    }
    
    /**
     * @return mixed
     */    
    public function getIdUserCreator()
    {
        return $this->data["iduser_creator"]->get();
    }

    
    /**
     * @param string $c
     */
    public function setCodice($c)
    {
        $this->data["codice"]->set($c);
    }
    
    /**
     * @return string
     */    
    public function getCodice()
    {
        return $this->data["codice"]->get();
    }

    /**
     * @param string $d
     */
    public function setDescrizione($d)
    {
        $this->data["descrizione"]->set($d);
    }
    
    /**
     * @return string
     */    
    public function getDescrizione()
    {
        return $this->data["descrizione"]->get();
    }

    /**
     * @param string $udm
     */
    public function setUdm($udm)
    {
        $this->data["udm"]->set($udm);
    }
    
    /**
     * @return string
     */
    public function getUdm()
    {
        return $this->data["udm"]->get();
    }

    /**
     * @param float $c
     */
    public function setCosto($c)
    {
        $this->data["costo"]->set($c);
    }
    
    /**
     * @return float
     */
    public function getCosto()
    {
        return $this->data["costo"]->get();
    }
    
    /**
     * @param float $m
     */
    public function setMoltiplicatore($m)
    {
        $this->data["moltiplicatore"]->set($m);
    }
    
    /**
     * @return float
     */
    public function getMoltiplicatore()
    {
        return $this->data["moltiplicatore"]->get();
    }
    
    /**
     * @param int $iva
     */
    public function setIva($iva)
    {
        $this->data["aliquota_iva"]->set($iva);
    }
    
    /**
     * @return int
     */    
    public function getIva()
    {
        return $this->data["aliquota_iva"]->get();
    }
    
    /**
     * @param string $note
     */
    public function setNote($note)
    {
        $this->data["note"]->set($note);
    }
    
    /**
     * @return string
     */
    public function getNote()
    {
        return $this->data["note"]->get();
    }

    /**
     * @param mixed $flag
     */
    public function setAttivo($flag)
    {
        $this->data["attivo"]->set($flag);
    }
    
    /**
     * @return bool
     */
    public function getAttivo()
    {
        return $this->data["attivo"]->getBool();
    }

    /**
     * @param mixed $flag
     */
    public function setProduction($flag)
    {
        $this->data["production"]->set($flag);
    }
    
    /**
     * @return bool
     */
    public function getProduction()
    {
        return $this->data["production"]->getBool();
    }

    /**
     * @param string $c
     */
    public function setCategoria($c)
    {
        $this->data["categoria"]->set($c);
    }
    
    /**
     * @return string
     */
    public function getCategoria()
    {
        return $this->data["categoria"]->get();
    }

    /**
     * @param string $sc
     */
    public function setSubcategoria($sc)
    {
        $this->data["categoria_sub"]->set($sc);
    }
    
    /**
     * @return string
     */
    public function getSubcategoria()
    {
        return $this->data["categoria_sub"]->get();
    }

    
    
/* ***********************************************
 *  LISTINO VALUES
 */
    
    /**
     * @param mixed $id
     */
    public function setIdListino($id)
    {
        $this->data["idlistino"]->set($id);
    }
    
    /**
     * @return mixed
     */
    public function getIdListino()
    {
        return $this->data["idlistino"]->get();
    }
    
    /**
     * @param string $d
     */
    public function setDescrizioneListino($d)
    {
        $this->data["descrizione_listino"]->set($d);
    }
    
    /**
     * @return string
     */    
    public function getDescrizioneListino()
    {
        return $this->data["descrizione_listino"]->get();
    }

    /**
     * @param float $c
     */
    public function setCostoListino($c)
    {
        $this->data["costo_listino"]->set($c);
    }
    
    /**
     * @return float
     */
    public function getCostoListino()
    {
        return $this->data["costo_listino"]->get();
    }
    
    /**
     * @param string $note
     */
    public function setNoteListino($note)
    {
        $this->data["note_listino"]->set($note);
    }
    
    /**
     * @return string
     */
    public function getNoteListino()
    {
        return $this->data["note_listino"]->get();
    }

    /**
     * @param mixed $flag
     */
    public function setAttivoListino($flag)
    {
        $this->data["attivo_listino"]->set($flag);
    }
    
    /**
     * @return bool
     */
    public function getAttivoListino()
    {
        return $this->data["attivo_listino"]->getBool();
    }

    
    
    
/* ***********************************************
 *  ORDINE VALUES
 */
    
    /**
     * @param mixed $id
     */
    public function setIdOrdine($id)
    {
        $this->data["idordine"]->set($id);
    }
    
    /**
     * @return mixed
     */
    public function getIdOrdine()
    {
        return $this->data["idordine"]->get();
    }

    /**
     * @param float $c
     */
    public function setCostoOrdine($c)
    {
        $this->data["costo_ordine"]->set($c);
    }
    
    /**
     * @return float
     */
    public function getCostoOrdine()
    {
        return $this->data["costo_ordine"]->get();
    }
    
    /**
     * @param mixed $flag
     */
    public function setOffertaOrdine($flag)
    {
        $this->data["offerta_ordine"]->set($flag);
    }
    
    /**
     * @return bool
     */
    public function getOffertaOrdine()
    {
        return $this->data["offerta_ordine"]->getBool();
    }

    /**
     * @param int $c
     */
    public function setScontoOrdine($c)
    {
        $this->data["sconto_ordine"]->set($c);
    }
    
    /**
     * @return int
     */
    public function getScontoOrdine()
    {
        return $this->data["sconto_ordine"]->get();
    }
    
    /**
     * @param mixed $flag
     */
    public function setDisponibileOrdine($flag)
    {
        $this->data["disponibile_ordine"]->set($flag);
    }
    
    /**
     * @return bool
     */
    public function getDisponibileOrdine()
    {
        return $this->data["disponibile_ordine"]->getBool();
    }
    

    
    /**
     * 
     * @return array
     */    
    public function dumpValuesForDB() { }
    
}
