<?php

/**
 * Description of Ordini
 * 
 * @author gullo
 */
class Form_Ordini extends MyFw_Form {
    
    function __construct() {
        parent::__construct();
    }
    
    function createFields() {

        $this->addField('data_inizio', array(
                    'label'     => 'Data apertura',
                    'size'      => 20,
                    'required'  => true,
                    'readonly'  => true,
                    'placeholder' => 'Seleziona data...'
        ));
        $this->addField('data_fine', array(
                    'label'     => 'Data chiusura',
                    'size'      => 20,
                    'required'  => true,
                    'readonly'  => true,
                    'placeholder' => 'Seleziona data...'
        ));
        $this->addField('archiviato', array(
                    'label'     => 'Archiviato',
                    'type'      => 'select',
                    'options'   => array('S'=>'SI','N'=>'NO')
        ));
        $this->addField('costo_spedizione', array(
                    'label'     => 'Costo spedizione',
                    'size'      => 15
        ));
        $this->addField('note_consegna', array(
                    'type'      => 'textarea',
                    'label'     => 'Note consegna',
                    'rows'      => 10,
                    'cols'      => 60,
        ));

    # HIDDEN FIELDS
        $this->addField('idordine', array( 'type' => 'hidden' ));
        $this->addField('idgroup', array( 'type' => 'hidden' ));
        $this->addField('idproduttore', array( 'type' => 'hidden' ));
        
    }
    
    function setFieldData_View($field, $d)
    {
        $dt = new Zend_Date($d, "YYYY-MM-dd HH:mm:ss");
        $value = $dt->toString("dd/MM/YYYY HH:mm");
        $this->setValue($field, $value);
    }
    
    function setFieldData_Save($field, $d)
    {
        $dt = new Zend_Date($d, "dd/MM/YYYY HH:mm");
        $value = $dt->toString("YYYY-MM-dd HH:mm:ss");
        $this->setValue($field, $value);
    }
    
}