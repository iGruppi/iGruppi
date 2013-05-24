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
                        'type'      => 'input',
                        'label'     => 'Ragione Sociale',
                        'size'      => 50,
                        'maxlength' => 255,
                        'required'  => true
            ));
        
        $this->addField('indirizzo', array(
                        'type'      => 'input',
                        'label'     => 'Indirizzo',
                        'size'      => 60
            ));
        
        $this->addField('comune', array(
                        'type'      => 'input',
                        'label'     => 'Comune',
                        'size'      => 40
            ));

        $this->addField('provincia', array(
                        'type'      => 'input',
                        'label'     => 'Provincia',
                        'size'      => 3,
                        'maxlength' => 2,
            ));
        
        $this->addField('telefono', array(
                        'type'      => 'input',
                        'label'     => 'Telefono',
                        'size'      => 40
            ));

        $this->addField('email', array(
                        'type'      => 'input',
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