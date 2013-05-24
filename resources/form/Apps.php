<?php

/**
 * Description of Apps
 * 
 * @author gullo
 */
class Form_Apps extends MyFw_Form {
    
    function __construct() {
        parent::__construct();
    }
    
    function createFields() {

    # GENERAL SETTINGS
        
        $this->addField('appName', array(
                    'label'     => 'App name',
                    'size'      => 55,
                    'maxlength' => 50,
                    'required'  => true,
        ));
        $this->addField('yourName', array(
                    'label'     => 'Your name',
                    'size'      => 55,
                    'maxlength' => 50,
                    'required'  => true,
        ));
        $this->addField('description', array(
                    'label'     => 'Short description',
                    'size'      => 100,
                    'maxlength' => 255,
                    'required'  => true,
        ));
        $this->addField('long_description', array(
                    'type'      => 'textarea',
                    'label'     => 'Long description',
                    'rows'      => 10,
                    'cols'      => 60,
                    'required'  => true,
        ));
        $this->addField('url_app', array(
                    'label'     => 'URL App (Store)',
                    'size'      => 100,
                    'maxlength' => 255,
        ));
        $this->addField('url_home', array(
                    'label'     => 'URL Home (Website)',
                    'size'      => 100,
                    'maxlength' => 255,
        ));

    # APP TYPE SETTINGS
        $objType = new Model_Type();
        $this->addField('types', array(
                        'set_array' => 'types',
                        'type'      => 'checkbox',
                        'label'     => 'Available for',
                        'options'   => $objType->convertToSingleArray($objType->getTypes(), "idtype", "description")
            ));


    # EMAIL SETTINGS

        $this->addField('email_from', array(
                    'label'     => 'Your Email',
                    'size'      => 80,
                    'maxlength' => 120,
                    'required'  => true,
        ));
        $this->addField('email_subject', array(
                    'label'     => 'Subject',
                    'size'      => 80,
                    'maxlength' => 120,
                    'required'  => true,
        ));


    # HIDDEN FIELDS

        $this->addField('idapp', array(
                    'type'      => 'hidden',
        ));
/*

        $this->addField('email_smtp_name', array(
                    'label'     => 'SMTP Server',
                    'size'      => 80,
                    'maxlength' => 120,
                    'required'  => true,
        ));

        $this->addField('email_smtp_port', array(
                    'label'     => 'SMTP Port',
                    'size'      => 80,
                    'maxlength' => 120,
                    'required'  => true,
        ));

        $this->addField('email_smtp_auth', array(
                    'label'     => 'SMTP Auth Type',
                    'size'      => 80,
                    'maxlength' => 120,
                    'required'  => true,
        ));

        $this->addField('email_smtp_username', array(
                    'label'     => 'Username',
                    'size'      => 80,
                    'maxlength' => 120,
                    'required'  => true,
        ));

        $this->addField('email_smtp_password', array(
                    'label'     => 'Password',
                    'size'      => 80,
                    'maxlength' => 120,
                    'required'  => true,
        ));

*/
        
    
    }
    
    
}