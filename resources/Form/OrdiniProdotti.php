<?php
/**
 * Description of Form_Prodotti
 * 
 * @author gullo
 */
class Form_OrdiniProdotti extends MyFw_Form {
    
    
    function __construct() {
        parent::__construct();
    }
    
    function createFields() {
        
        $this->addField('costo_ordine', array(
                        'type'      => 'text',
                        'label'     => 'Prezzo',
                        'size'      => 10,
                        'required'  => true,
                        'class'     => 'is_Number',
                        'note'      => '&nbsp;&euro;'
            ));

        $this->addField('disponibile_ordine', array(
                        'type'      => 'select',
                        'label'     => 'Disponibile',
                        'options'   => array('S' => 'SI', 'N' => 'NO')
            ));
        
        $this->addField('offerta_ordine', array(
                        'type'      => 'select',
                        'label'     => 'Offerta',
                        'options'   => array('S' => 'SI', 'N' => 'NO')
            ));
        
        $this->addField('sconto_ordine', array(
                        'type'      => 'text',
                        'label'     => 'Sconto (%)',
                        'size'      => 10
            ));

    # HIDDEN FIELDS

        $this->addField('idordine', array( 'type' => 'hidden' ));
        $this->addField('idlistino', array( 'type' => 'hidden' ));
        $this->addField('idprodotto', array( 'type' => 'hidden' ));

    }
}