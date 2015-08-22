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

        $this->addField('descrizione', array(
                        'label'     => 'Descrizione',
                        'size'      => 40,
                        'maxlength' => 50
            ));
        
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
        
        $this->addField('visibile', array(
                    'label'     => 'Visibile',
                    'type'      => 'select',
                    'options'   => array('S'=>'SI','N'=>'NO')
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
        
        // get Groups
        $grObj = new Model_Db_Groups();
        $groups = $grObj->convertToSingleArray($grObj->getAll(true), "idgroup", "nome");
        $this->addField("groups", array(
                    'type'      => 'checkbox',
                    'options'   => $groups
        ));
        
        // get IdUsers array for this group
        $userSessionVal = new Zend_Session_Namespace('userSessionVal');
        $objU = new Model_Db_Users();
        $arVal = $objU->convertToSingleArray($objU->getUsersByIdGroup($userSessionVal->idgroup, true), "iduser", "nominativo");
        $arVal[0] = 'Seleziona...';
        $this->addField('iduser_ref', array(
                        'label'     => 'Incaricato ordine',
                        'type'      => 'select',
                        'options'   => $arVal,
                        'required'  => true
            ));
        

    # HIDDEN FIELDS
        $this->addField('idordine', array( 'type' => 'hidden' ));
        
    }
    
}