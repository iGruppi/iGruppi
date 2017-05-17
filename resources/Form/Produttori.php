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

        $this->addField('p_iva', array(
                        'label'     => 'P. IVA',
                        'size'      => 20,
                        'maxlength' => 16,
                        'required'  => true,
                        'validators'=> array('Unique' => array('produttori', 'p_iva'))
            ));

        $this->addField('indirizzo', array(
                        'label'     => 'Indirizzo',
                        'size'      => 50
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
                        'size'      => 40
            ));

        $this->addField('url_www', array(
                        'label'     => 'Sito web',
                        'size'      => 60,
                        'maxlength' => 255
        ));

        $this->addField('note', array(
                        'type'      => 'textarea',
                        'label'     => 'Note',
                        'rows'      => 10,
                        'cols'      => 60,
        ));

        $this->addField('desc_abstract', array(
                        'type'      => 'textarea',
                        'label'     => 'Abstract',
                        'rows'      => 10,
                        'cols'      => 60,
        ));

        $this->addField('desc_presentazione', array(
            'type'      => 'textarea',
            'label'     => 'Presentazione',
            'rows'      => 10,
            'cols'      => 60,
        ));

        $this->addField('desc_storia', array(
            'type'      => 'textarea',
            'label'     => 'Storia',
            'rows'      => 10,
            'cols'      => 60,
        ));

        $this->addField('desc_certificazioni', array(
            'type'      => 'textarea',
            'label'     => 'Certificazioni',
            'rows'      => 10,
            'cols'      => 60,
        ));

        $this->addField('desc_ambiente', array(
            'type'      => 'textarea',
            'label'     => 'Attenzioni all\'ambiente',
            'rows'      => 10,
            'cols'      => 60,
        ));

        $this->addField('desc_servizi', array(
            'type'      => 'textarea',
            'label'     => 'Servizi',
            'rows'      => 10,
            'cols'      => 60,
        ));

        $this->addField('desc_scelto', array(
            'type'      => 'textarea',
            'label'     => 'Scelto perchè...',
            'rows'      => 10,
            'cols'      => 60,
        ));

    # HIDDEN FIELDS

        $this->addField('idproduttore', array(
                    'type'      => 'hidden',
        ));

    }
}