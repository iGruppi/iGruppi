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
                    'placeholder' => 'Seleziona data...',
                    'filters'   => array('date' => array(
                                            'format'    => MyFw_Form_Filters_Date::_MYFORMAT_DATETIME_DB)
                                        )
        ));
        $this->addField('data_fine', array(
                    'label'     => 'Data chiusura',
                    'size'      => 20,
                    'required'  => true,
                    'readonly'  => true,
                    'placeholder' => 'Seleziona data...',
                    'filters'   => array('date' => array(
                                            'format'    => MyFw_Form_Filters_Date::_MYFORMAT_DATETIME_DB)
                                        )
        ));
        $this->addField('costo_spedizione', array(
                    'label'     => 'Costo spedizione',
                    'class'     => 'is_Number',
                    'size'      => 10,
                    'note'      => '&euro;'
        ));
        $this->addField('note_consegna', array(
                    'type'      => 'textarea',
                    'label'     => 'Note consegna',
                    'rows'      => 10,
                    'cols'      => 60,
        ));
        
        $this->addField('condivisione', array(
                        'label'     => 'Condivisione',
                        'type'      => 'select',
                        'options'   => array('PUB'=>'Pubblico','SHA'=>'Condiviso','PRI'=>'Privato')
            ));
        
        

    # HIDDEN FIELDS
        $this->addField('idordine', array( 'type' => 'hidden' ));
        
    }
    
}