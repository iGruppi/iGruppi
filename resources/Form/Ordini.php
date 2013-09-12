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
                    'size'      => 15,
                    'required'  => true,
        ));
        $this->addField('data_fine', array(
                    'label'     => 'Data chiusura',
                    'size'      => 15,
                    'required'  => true,
        ));
        $this->addField('archiviato', array(
                    'label'     => 'Archiviato',
                    'type'      => 'select',
                    'options'   => array('S'=>'SI','N'=>'NO')
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
    
    
}