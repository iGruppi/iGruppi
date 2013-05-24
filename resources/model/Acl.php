<?php

class Model_Acl
    extends Zend_Acl
{
    function __construct(Zend_Auth $auth)
    {
        // create roles
        $this->addRole('Guest');
        $this->addRole('User', 'Guest');
        $this->addRole('Premium', 'User');
        $this->addRole('Admin', 'Premium');

        $this->addResource('Index');
        $this->addResource('Error');
        $this->addResource('Auth');			// operazioni di login/logout
        $this->addResource('Info');
        // Logged Resource
        $this->addResource('Dashboard');
        $this->addResource('Users');
        $this->addResource('JxDomains');
        $this->addResource('Ordini');
        $this->addResource('Gruppo');
        $this->addResource('Produttori');
        $this->addResource('Prodotti');
        $this->addResource('GestioneOrdini');
        
        // NEGO tutto per tutti
        $this->deny(null, null); 

        // GUEST
        $this->allow('Guest', 'Index');
        $this->allow('Guest', 'Error');
        $this->allow('Guest', 'Auth');
        $this->allow('Guest', 'Info');

        // Free vede tutto quello che vede Guest più questi sotto
        $this->allow('User', 'Dashboard');
        $this->allow('User', 'Users');
        $this->allow('User', 'Ordini');
        $this->allow('User', 'Gruppo');
        $this->allow('User', 'Produttori');
        $this->allow('User', 'Prodotti');
        $this->allow('User', 'GestioneOrdini');
        
//        $this->allow('Free', 'utenza');
//        $this->allow('Free', 'utenze');
//        $this->allow('Free', 'verifica');
//        $this->allow('Free', 'previsione');

        // Administrator: TUTTO è PERMESSO!!
        $this->allow('Admin');
    }

}