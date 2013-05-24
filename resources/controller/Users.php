<?php
/**
 * Description of Users
 * 
 * @author gullo
 */
class Controller_Users extends MyFw_Controller {
    
    function _init() { }
    
    function indexAction() {
        
    }
    
    function manageAction() {
        $this->view->title_2 = "start Action manage - Param: ".$this->getParam("pinco");
        $db = Zend_Registry::get("db");
        $sth = $db->prepare("SELECT * FROM users ORDER BY dt");
        $sth->execute();
        $this->view->users = $sth->fetchAll(PDO::FETCH_CLASS);
    }
    
    function addAction() {
        
        $name = $this->getParam("name");
        $surname = $this->getParam("surname");
        $data = date("Y-m-d H:i:s");
        $db = Zend_Registry::get("db");
        $db->query("INSERT INTO users SET name='$name', surname='$surname', dt='$data'");
        
        $this->forward('users', 'manage');
    }
    
    
}