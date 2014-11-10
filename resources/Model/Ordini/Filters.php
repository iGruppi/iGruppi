<?php
/**
 * Description of Filters
 * Create URLs based on selected FILTERS
 * 
 * @author gullo
 */
class Model_Ordini_Filters {
    
    private $_Filters = array();
    private $_urlBase = null;
    
    function setUrlBase($url) {
        $this->_urlBase = $url;
    }
    
    function getFilters() {
        return $this->_Filters;
    }
    
    function setFilterByField($field, $value) {
        if(!is_null($value)) {
            $this->_Filters[$field] = $value;
        }        
    }
    
    function getFilterByField($field) {
        if($this->hasFilterByField($field)) {
            return $this->_Filters[$field];
        }
        return null;
    }
    
    function hasFilterByField($field) {
        return isset($this->_Filters[$field]);
    }
    
    function createUrlForProduttore($idproduttore, $remove=false) {
        $url = $this->createUrlBaseByField("idproduttore");
        if(!$remove) { 
            $url .= "/idproduttore/$idproduttore";
        }
        return $url;
    }
    
    function createUrlForStato($stato, $remove=false) {
        $url = $this->createUrlBaseByField("stato");
        if(!$remove) {
            $url .= "/stato/$stato";
        }
        return $url;
    }
    /*
    function createUrlForPeriodo($periodo) {
        return $this->createUrlBaseByField("periodo") . "/periodo/$periodo";
    }
    */
    private function createUrlBaseByField($field) {
        $url = $this->_urlBase;
        $fProd = $this->_Filters;
        if(isset($fProd[$field])) { unset($fProd[$field]); }
        if( count($fProd) > 0 ) {
            foreach ($fProd as $kP => $vP) {
                $url .= "/$kP/$vP";
            }
        }
        return $url;
    }
    
}