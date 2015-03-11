<?php
/**
 * Description of DecoratorInterface
 * 
 * @author Davide Gullo <gullo at m4ss.net>
 * 
 */
interface Model_Prodotto_UserOrdine_QtaDecoratorInterface {
    
    /**
     * It CAN ONLY decorate a Mediator_Prodotto
     * @param Model_Prodotto_Mediator_Mediator $prodotto
     */
    public function __construct(Model_Prodotto_Mediator_Mediator $prodotto);
    
    /**
     * Route all other method calls directly to Prodotto_Mediator
     * @param type $method
     * @param type $args
     * @return mixed
     */
    public function __call($method, $args);    
    
    /**
     * set qta field per user
     * @param type $iduser
     * @param type $qta
     */
    public function setQta($iduser, $qta);
    
    /**
     * set qta_reale per user
     * @param type $iduser
     * @param type $qta_reale
     */
    public function setQtaReale($iduser, $qta_reale);
    
    
    
}
