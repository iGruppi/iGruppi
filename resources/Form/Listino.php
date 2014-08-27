<?php

/**
 * Description of Form_Listino
 * 
 * @author gullo
 */
class Form_Listino extends MyFw_Form {
    
    
    function __construct() {
        parent::__construct();
    }
    
    function createFields() {
        
        $this->addField('descrizione', array(
                        'label'     => 'Descrizione',
                        'size'      => 60,
                        'maxlength' => 100,
                        'required'  => true
            ));
        
        $this->addField('valido_dal', array(
                        'label'     => 'Valido dal',
                        'size'      => 20,
                        'readonly'  => true,
                        'placeholder' => 'Seleziona data...',
                        'filters'   => array('date' => array(
                                                'canBeNull' => true,
                                                'format'    => MyFw_Form_Filters_Date::_MYFORMAT_DATE_DB)
                                            )
            ));
        $this->addField('valido_al', array(
                        'label'     => 'Valido al',
                        'size'      => 20,
                        'readonly'  => true,
                        'placeholder' => 'Seleziona data...',
                        'filters'   => array('date' => array(
                                                'canBeNull' => true,
                                                'format'    => MyFw_Form_Filters_Date::_MYFORMAT_DATE_DB)
                                            )
            ));
        
        $this->addField('condivisione', array(
                        'label'     => 'Condivisione',
                        'type'      => 'select',
                        'options'   => array('PUB'=>'Pubblico','SHA'=>'Condiviso','PRI'=>'Privato')
            ));
        
        $this->addField('visibile', array(
                        'label'     => 'Visibile',
                        'type'      => 'select',
                        'options'   => array('S'=>'SI','N'=>'NO')
            ));
        
        
    # HIDDEN FIELDS
        $this->addField('idproduttore', array( 'type' => 'hidden' ));
        $this->addField('idlistino', array( 'type' => 'hidden' ));
        
    }
}