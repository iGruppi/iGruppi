<?php
/**
 * Description of Form_Prodotti
 * 
 * @author gullo
 */
class Form_Prodotti extends MyFw_Form {
    
    
    function __construct() {
        parent::__construct();
    }
    
    function createFields() {
        
        $this->addField('codice', array(
                        'label'     => 'Codice',
                        'size'      => 10,
                        'maxlength' => 20,
                        'required'  => true
            ));
        $this->addField('descrizione', array(
                        'label'     => 'Descrizione',
                        'size'      => 60,
                        'maxlength' => 255,
                        'required'  => true
            ));
        
        // CATEGORIA
        $this->addField('idsubcat', array(
                        'type'      => 'select',
                        'label'     => 'Categoria',
                        'options'   => array(),
                        'required'  => true
            ));
        
        // UDM 
        $udmObj = new Model_Prodotti_UdM();
        $this->addField('udm', array(
                        'type'      => 'select',
                        'label'     => 'Unità di misura',
                        'options'   => $udmObj->getArUdm(),
                        'required'  => true
            ));
        
        $this->addField('attivo', array(
                        'type'      => 'select',
                        'label'     => 'Disponibile',
                        'options'   => array('S' => 'SI', 'N' => 'NO')
            ));

        $this->addField('costo', array(
                        'label'     => 'Prezzo (&euro;)',
                        'size'      => 10,
                        'required'  => true,
                        'class'     => 'is_Number'
            ));
        
        $this->addField('aliquota_iva', array(
                        'label'     => 'IVA (%)',
                        'size'      => 3,
                        'class'     => 'is_Number',
                        'note'      => "(Inserisci 0 per non gestire l'IVA)"
            ));

        $this->addField('offerta', array(
                        'type'      => 'select',
                        'label'     => 'Offerta',
                        'options'   => array('S' => 'SI', 'N' => 'NO')
            ));
        
        $this->addField('sconto', array(
                        'label'     => 'Sconto (%)',
                        'size'      => 10
            ));

        $this->addField('note', array(
                        'type'      => 'textarea',
                        'label'     => 'Note',
                        'rows'      => 10,
                        'cols'      => 60,
        ));

    # HIDDEN FIELDS

        $this->addField('idprodotto', array( 'type' => 'hidden' ));
        $this->addField('idproduttore', array( 'type' => 'hidden' ));

    }
}