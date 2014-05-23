<?php

/**
 * Description of Form_Login
 * 
 * @author gullo
 */
class Form_Login extends MyFw_Form {
    
    
    function __construct() {
        parent::__construct();
    }
    
    function createFields() {
        
        $this->addField('email', array(
                        'label'     => 'Email',
                        'size'      => 40,
                        'maxlength' => 100,
                        'required'  => true
            ));
        
        $this->addField('password', array(
                        'type'      => 'password',
                        'label'     => 'Password',
                        'size'      => 40,
                        'maxlength' => 12,
                        'required'  => true
            ));
        
    }
}