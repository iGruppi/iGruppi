<?php
/**
 * Description of Auth
 *
 * @author gullo
 */
class Plugin_Auth {

    private $_acl;
    private $_auth;


    public function __construct ()
    {
        $this->_auth = Zend_Auth::getInstance();
        $this->_acl = new Model_Acl(Zend_Auth::getInstance());
    }


    public function preDispatch(MyFw_ControllerFront $cf)
    {
        if($this->_auth->hasIdentity())
        {
            $role = $this->_auth->getIdentity()->role;
        } else {
            $role = 'Guest';
        }
        
        // get Controller and Action
        $controller = $cf->getController();
        $action = $cf->getAction();
        
        // init Acl model and check Auth
        if( $this->_acl->has($controller) ) {
            if(!$this->_acl->isAllowed($role, $controller)) {
                $controller = 'Auth';
                $action = 'login';
            }
        } else {
            $controller = 'Index';
            $action = 'error';
            $cf->setParams(array("code" => 404));
        }
//echo "POST - Controller: $controller - Action: $action <br>";
        $cf->setController($controller);
        $cf->setAction($action);
    }

    public function  postDispatch(MyFw_ControllerFront $cf)
    {
        //echo "<br>post - Dispatch<br>";
        
    }
}