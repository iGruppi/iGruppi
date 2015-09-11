<?php
/**
 * Description of DecoratorInterface
 * 
 * @author Davide Gullo <gullo at m4ss.net>
 * 
 */
interface Model_Ordini_CalcoliDecoratorInterface {
    
    /**
     * It CAN ONLY decorate the Model_Ordini_Ordine
     * @param Model_Ordini_Ordine $ordine
     */
    public function __construct(Model_Ordini_Ordine $ordine);
    
    /**
     * Route all other method calls directly to Model_Ordini_Ordine
     * @param type $method
     * @param type $args
     * @return mixed
     */
    public function __call($method, $args);
    
    /**
     * Set Prodotti Ordinati by every Iduser
     * @param array $prodotti
     */
    public function setProdottiOrdinati(array $prodotti);
    
    
    
}
