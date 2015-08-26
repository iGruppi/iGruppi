<?php
/**
 * This is the product GROUP of my Builder, it must be extended
 */
abstract class Model_Builder_GroupSharing_Parts_Group
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function setPart($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->data["id"]->set($id);
    }
    
    /**
     * @return int
     */    
    public function getId()
    {
        return $this->data["id"]->get();
    }

/**************************
 * GROUPS: SLAVE and MASTER
 */        
    
    /**
     * @param int $idgroup
     */
    public function setIdGroupMaster($idgroup)
    {
        $this->data["idgroup_master"]->set($idgroup);
    }
    
    /**
     * @return int
     */    
    public function getIdGroupMaster()
    {
        return $this->data["idgroup_master"]->get();
    }
    
    /**
     * @param int $idgroup
     */
    public function setIdGroup($idgroup)
    {
        $this->data["idgroup_slave"]->set($idgroup);
    }
    
    /**
     * @return int
     */    
    public function getIdGroup()
    {
        return $this->data["idgroup_slave"]->get();
    }
    
    /**
     * @param string $group_nome
     */
    public function setGroupName($group_nome)
    {
        $this->data["group_nome"]->set($group_nome);
    }
    
    /**
     * @return string
     */    
    public function getGroupName()
    {
        return $this->data["group_nome"]->get();
    }

/**********************
 * REFERENTE PRODUTTORE
 */    
    
    /**
     * Set Incaricato by Nome and Cognome
     * @param string $nome
     * @param string $cognome
     */
    public function setReferente($iduser, $nome="", $cognome="")
    {
        $this->data["iduser_referente"]->set($iduser);
        $this->data["nome_referente"]->set($nome);
        $this->data["cognome_referente"]->set($cognome);
    }
    
    /**
     * @return int
     */
    public function getIdUser_Referente()
    {
        return $this->data["iduser_referente"]->get();
    }

    /**
     * @return string
     */
    public function getReferente()
    {
        return $this->data["nome_referente"]->get() . " " . $this->data["cognome_referente"]->get();
    }

    
/*******************
 * INCARICATO ORDINE
 */    
    
    /**
     * Set Incaricato by Nome and Cognome
     * @param string $nome
     * @param string $cognome
     */
    public function setIncaricato($iduser, $nome="", $cognome="")
    {
        $this->data["iduser_incaricato"]->set($iduser);
        $this->data["nome_incaricato"]->set($nome);
        $this->data["cognome_incaricato"]->set($cognome);
    }
    
    /**
     * @return int
     */
    public function getIdUser_Incaricato()
    {
        return $this->data["iduser_incaricato"]->get();
    }

    /**
     * @return string
     */
    public function getIncaricato()
    {
        return $this->data["nome_incaricato"]->get() . " " . $this->data["cognome_incaricato"]->get();
    }
    

    
/**************
 * OTHER FIELDS
 */ 
    
    /**
     * @param mixed  $flag
     */
    public function setVisibile($flag)
    {
        $this->data["visibile"]->set($flag);
    }
    
    /**
     * @return Model_Builder_GroupSharing_Parts_FlagSN
     */    
    public function getVisibile()
    {
        return $this->data["visibile"];
    }
    
    /**
     * @param string date $dal
     * @param string date $al
     */
    public function setValidita($dal, $al)
    {
        $this->data["validita"]->set($dal, $al);
    }
    
    /**
     * @return object Model_Builder_GroupSharing_Parts_Validita
     */    
    public function getValidita()
    {
        return $this->data["validita"];
    }

    /**
     * @param string $e
     */
    public function setExtra($e)
    {
        $this->data["extra"]->set($e);
    }
    
    /**
     * @return Model_Ordini_Extra_Spese
     */    
    public function getExtra()
    {
        return $this->data["extra"];
    }
    
    /**
     * @param string $note
     */
    public function setNoteConsegna($note)
    {
        $this->data["note_consegna"]->set($note);
    }
    
    /**
     * @return string
     */    
    public function getNoteConsegna()
    {
        return $this->data["note_consegna"]->get();
    }
    
    /**
     * @return array
     */    
    public function dumpValuesForDB() { }
    
}
