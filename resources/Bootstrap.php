<?php
/**
 * Description of Bootstrap
 * 
 * @author gullo
 */
class Bootstrap {
    
    public function run() {
        $this->startConfigIni();
        $this->startSession();
        $this->startLocale();
        $this->startDB();
        $this->startLayout();
        $this->startView();
        $this->startPlugin();
    }

    private function startConfigIni () {
        $appConfig = new Zend_Config_Ini(APPLICATION_PATH . '/resources/Config/application.ini', APPLICATION_ENV);
        Zend_Registry::set('appConfig', $appConfig);
    }
    
    private function startDB () {
        $db = new MyFw_DB();
        Zend_Registry::set('db', $db);
    }
    
    private function startSession () {
        $userSessionVal = new Zend_Session_Namespace('userSessionVal');
        $userSessionVal->idgroup = 1; // TODO: The users should be choose it! 
    }
    
    private function startLocale () {
    	date_default_timezone_set('Europe/Rome');
    	setlocale(LC_TIME, 'it_IT');
    }
    
    private function startLayout() {
        $l = new MyFw_Layout();
        Zend_Registry::set('layout', $l);
    }

    private function startView() {
        // init view
        $appConfig = Zend_Registry::get('appConfig');
        $view = new MyFw_View(array('template_path' => $appConfig->template->path_view));
        Zend_Registry::set('view', $view);
    }

    private function startPlugin() {
        $pObj = new MyFw_Plugin();
        $pObj->loadPlugin('Auth');
        Zend_Registry::set('plugin', $pObj);
    }

}