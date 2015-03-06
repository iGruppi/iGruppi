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

    /**
     * @param int $iduser
     */
    public function setRefIdUser($iduser)
    {
        $this->data["ref_iduser"]->set($iduser);
    }
    
    /**
     * @return int
     */
    public function getRefIdUser()
    {
        return $this->data["ref_iduser"]->get();
    }

    /**
     * @param string $ref_nome
     * @param string $ref_cognome
     */
    public function setRefNome($ref_nome, $ref_cognome)
    {
        $this->data["ref_nome"]->set($ref_nome);
        $this->data["ref_cognome"]->set($ref_cognome);
    }
    
    /**
     * @return string
     */
    public function getRefNome()
    {
        return $this->data["ref_nome"]->get() . " " . $this->data["ref_cognome"]->get();
    }
    
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
     * @param float $cs
     */
    public function setCostoSpedizione($cs)
    {
        $this->data["costo_spedizione"]->set($cs);
    }
    
    /**
     * @return float
     */    
    public function getCostoSpedizione()
    {
        return $this->data["costo_spedizione"]->get();
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
