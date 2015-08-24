<?php
class Model_TimeDebugger {
    
    static private $_instance = null;
    static private $_steps = array();
    static private $_last;
    static private $_start;
    
    // Singleton
    static public function getInstance()
    {
        if(is_null(self::$_instance))
        {
            self::$_instance = new Model_TimeDebugger();
            self::$_last = self::$_start = microtime();
        }
        return self::$_instance;
    }
    
    
    function setStep($descrizione)
    {
        self::$_steps[] = array(
                'descrizione' => $descrizione,
                'delay'       => $this->_getDelay(),
                'progressivo' => $this->_getProgressivo()
        );
        self::$_last = microtime();
    }
    
    
    private function _getDelay()
    {
        return $this->_microtime_diff(self::$_last, microtime());
    }
    
    private function _getProgressivo()
    {
        return $this->_microtime_diff(self::$_start, microtime());
    }
    
/*
* Funzione per il calcolo delle differenze microtime 
* (ritorna risultato in secondi)
*/
    private function _microtime_diff($a, $b) {
       list($a_dec, $a_sec) = explode(" ", $a);
       list($b_dec, $b_sec) = explode(" ", $b);
       $diff = $b_sec - $a_sec + $b_dec - $a_dec;
       return round($diff, 4);
    }    
    
    
    
    
    public function dump($t='text')
    {
        $this->setStep("END (called dump)");
        
        if(APPLICATION_ENV == "development") 
        {
            echo "<pre>";
            echo "<h3>Tempo totale di esecuzione: ".$this->_microtime_diff(self::$_start, microtime())."</h3>";
            switch ($t) {
                case "text":
                    echo "<ul>";
                    foreach(self::$_steps AS $step)
                    {
                        $parziale = ($step["delay"] > 0.04) ? "<span style='color:red;'>".$step["delay"]."</span>" : $step["delay"];
                        echo "<li>$parziale  sec. - " . $step['descrizione'] . " - (Progressivo: ".$step["progressivo"].")</li>";
                    }
                    echo "</ul>";
                    break;

                case "array":
                    echo print_r(self::$_steps);
                    break;
            }
            echo "</pre>";
        }
    }
    
    
}
