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
                        'type'      => 'input',
                        'label'     => 'Codice',
                        'size'      => 10,
                        'maxlength' => 20,
                        'required'  => true
            ));
        $this->addField('descrizione', array(
                        'type'      => 'input',
                        'label'     => 'Descrizione',
                        'size'      => 60,
                        'maxlength' => 255,
                        'required'  => true
            ));
        
        // CATEGORIA
        $objCat = new Model_Categorie();
        $this->addField('idcat', array(
                        'type'      => 'select',
                        'label'     => 'Categoria',
                        'options'   => $objCat->convertToSingleArray($objCat->getCategorie(), "idcat", "descrizione"),
                        'required'  => true
            ));
        
        
        $this->addField('udm', array(
                        'type'      => 'input',
                        'label'     => 'Unità di misura',
                        'size'      => 30,
                        'required'  => true
            ));
        
        $this->addField('attivo', array(
                        'type'      => 'select',
                        'label'     => 'Disponibile',
                        'options'   => array('S' => 'SI', 'N' => 'NO')
            ));

        $this->addField('costo', array(
                        'type'      => 'input',
                        'label'     => 'Costo (&euro;)',
                        'size'      => 10,
                        'required'  => true,
                        'validators' => array('Number')
            ));

        $this->addField('offerta', array(
                        'type'      => 'select',
                        'label'     => 'Offerta',
                        'options'   => array('S' => 'SI', 'N' => 'NO')
            ));
        
        $this->addField('sconto', array(
                        'type'      => 'input',
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