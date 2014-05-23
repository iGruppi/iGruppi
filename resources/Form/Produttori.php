<?php
/**
 * Description of Form_Produttori
 * 
 * @author gullo
 */
class Form_Produttori extends MyFw_Form {
    
    
    function __construct() {
        parent::__construct();
    }
    
    function createFields() {
        
        $this->addField('ragsoc', array(
                        'label'     => 'Ragione Sociale',
                        'size'      => 50,
                        'maxlength' => 255,
                        'required'  => true
            ));
        
        $this->addField('indirizzo', array(
                        'label'     => 'Indirizzo',
                        'size'      => 60
            ));
        
        $this->addField('comune', array(
                        'label'     => 'Comune',
                        'size'      => 40
            ));

        $this->addField('provincia', array(
                        'label'     => 'Provincia',
                        'size'      => 3,
                        'maxlength' => 2,
            ));
        
        $this->addField('telefono', array(
                        'label'     => 'Telefono',
                        'size'      => 40
            ));

        $this->addField('email', array(
                        'label'     => 'Email',
                        'size'      => 40,
                        'required'  => true
            ));

        $this->addField('note', array(
                        'type'      => 'textarea',
                        'label'     => 'Note',
                        'rows'      => 10,
                        'cols'      => 60,
        ));

    # HIDDEN FIELDS

        $this->addField('idproduttore', array(
                    'type'      => 'hidden',
        ));

    }
}