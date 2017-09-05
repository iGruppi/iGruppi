<?php
/**
 * Description of Form_User
 * 
 * @author gullo
 */
class Form_User extends MyFw_Form {
    
    function __construct() {
        parent::__construct();
    }
    
    function createFields() {
        
        $this->addField('nome', array(
                        'label'     => 'Nome',
                        'size'      => 30,
                        'maxlength' => 45,
                        'required'  => true
            ));
        $this->addField('cognome', array(
                        'label'     => 'Cognome',
                        'size'      => 30,
                        'maxlength' => 45,
                        'required'  => true
            ));

        $this->addField('num_members', array(
                        'label'     => 'Num. nucleo familiare',
                        'size'      => 10,
                        'maxlength' => 2,
                        'required'  => true
            ));

        $this->addField('email', array(
                        'label'     => 'Email',
                        'size'      => 40,
                        'maxlength' => 100,
                        'required'  => true
            ));

        $this->addField('password', array(
                        'type'      => 'password',
                        'label'     => 'Password',
                        'size'      => 20,
                        'maxlength' => 12,
                        'required'  => true
            ));

        $this->addField('password2', array(
                        'type'      => 'password',
                        'label'     => 'Ripeti Password',
                        'size'      => 20,
                        'maxlength' => 12,
                        'required'  => true
            ));
        
        $this->addField('tel', array(
                        'label'     => 'Telefono',
                        'size'      => 20,
                        'maxlength' => 30
            ));

        $this->addField('indirizzo', array(
                        'label'     => 'Indirizzo',
                        'size'      => 30,
                        'maxlength' => 50
            ));

        $this->addField('localita', array(
                        'label'     => 'Località',
                        'size'      => 20,
                        'maxlength' => 45
            ));

        // PROVINCE
        $objProv = new Model_Db_Province();
        $arValP = $objProv->convertToSingleArray($objProv->getAll(), "provincia", "provdesc");
        $arValP[0] = 'Seleziona...';
        $this->addField('provincia', array(
                        'type'      => 'select',
                        'label'     => 'Provincia',
                        'options'   => $arValP,
                        'required'  => true
            ));

        $this->addField('in_prova', array(
                        'type'      => 'select',
                        'label'     => 'In prova',
                        'options'   => array('S' => 'SI', 'N' => 'NO'),
                        'required'  => true
            ));

        $this->addField('note', array(
                        'type'      => 'textarea',
                        'label'     => 'Note',
                        'cols'      => 60,
                        'rows' => 4
            ));


        // GRUPPI
        $objGr = new Model_Db_Groups();
        $arVal = $objGr->convertToSingleArray($objGr->getAll(), "idgroup", "nome");
        $arVal[0] = 'Seleziona...';
        $this->addField('idgroup', array(
                        'type'      => 'select',
                        'label'     => 'Gruppo',
                        'options'   => $arVal,
                        'required'  => true
            ));
        
        // Hidden field iduser
        $this->addField('iduser', array( 'type' => 'hidden' ));
        
        
        // USERS_GROUP fields
        $this->addField('attivo', array(
                    'type'      => 'select',
                    'label'     => 'Abilitato',
                    'options'   => array('S' => 'SI', 'N' => 'NO'),
                    'required'  => true
        ));
        $this->addField('fondatore', array(
                    'type'      => 'select',
                    'label'     => 'Fondatore',
                    'options'   => array('S' => 'SI', 'N' => 'NO'),
                    'required'  => true
        ));
        $this->addField('contabile', array(
                    'type'      => 'select',
                    'label'     => 'Contabile',
                    'options'   => array('S' => 'SI', 'N' => 'NO'),
                    'required'  => true
        ));
        

    }
}