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
        
        $this->addField('attivo', array(
                        'type'      => 'select',
                        'label'     => 'Disponibile',
                        'options'   => array('S' => 'SI', 'N' => 'NO')
            ));
        
        // UDM 
        $udmObj = new Model_Prodotto_UdM();
        $this->addField('udm', array(
                        'type'      => 'select',
                        'label'     => 'Prezzo per',
                        'options'   => $udmObj->getArUdm(),
                        'required'  => true
            ));
        // MOLTIPLICATORE
        $this->addField('moltiplicatore', array(
                        'type'      => 'text',
                        'label'     => 'Pezzatura/Taglio',
                        'size'      => 10,
                        'required'  => true,
                        'class'     => 'is_Number',
                        'note'      => '-'
            ));
        
        // COSTO
        $this->addField('costo', array(
                        'type'      => 'text',
                        'label'     => 'Prezzo',
                        'size'      => 10,
                        'required'  => true,
                        'class'     => 'is_Number',
                        'note'      => '&nbsp;&euro;'
            ));
        // IVA
        $ivaObj = new Model_Prodotto_IVA();
        $this->addField('aliquota_iva', array(
                        'type'      => 'select',
                        'label'     => 'IVA',
                        'options'   => $ivaObj->getArIVA(),
                        'required'  => true,
                        'onchange'  => 'setCostoLabel();'
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