<?php

/**
 * Description of Form_Cassa
 * 
 * @author gullo
 */
class Form_Cassa extends MyFw_Form {
    
    
    function __construct() {
        parent::__construct();
    }
    
    function createFields() {
        
        $this->addField('descrizione', array(
                        'label'     => 'Descrizione',
                        'size'      => 45,
                        'maxlength' => 100,
                        'required'  => true
            ));
        
        $this->addField('data', array(
                        'label'     => 'Data',
                        'size'      => 20,
                        'readonly'  => true,
                        'placeholder' => 'Seleziona data...',
                        'filters'   => array('date' => array(
                                                'canBeNull' => true,
                                                'format'    => MyFw_Form_Filters_Date::_MYFORMAT_DATETIME_DB)
                                            )
            ));
        
        $this->addField('importo', array(
                        'label'     => 'Importo',
                        'size'      => 15,
                        'maxlength' => 10,
                        'required'  => true
            ));

        // get IdUsers for Referente
        $objU = new Model_Db_Users();
        $userSessionVal = new Zend_Session_Namespace('userSessionVal');
        $arVal = $objU->convertToSingleArray($objU->getUsersByIdGroup($userSessionVal->idgroup, true), "iduser", "nominativo");
        $arVal[0] = 'Seleziona...';
        $this->addField('iduser', array(
                        'label'     => 'Utente',
                        'type'      => 'select',
                        'options'   => $arVal,
                        'required'  => true
            ));        
        
    # HIDDEN FIELDS
        $this->addField('idmovimento', array( 'type' => 'hidden' ));
        $this->addField('idordine', array( 'type' => 'hidden' ));
        
    }
}