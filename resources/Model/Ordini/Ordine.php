<?php
/**
 * This is the Factory to manage the ORDINE
 * 
 * @author gullo
 */
class Model_Ordini_Ordine extends Model_AF_AbstractCoR 
{
    /**
     * set OrdineFactory as factory class
     * @return void
     */
    public function __construct(Model_AF_AbstractFactory $factoryClass) {
        $this->factoryClass = $factoryClass;
    }
    
    /**
     * Append States Pattern to the Chain
     * @return $this Model_AF_AbstractCoR
     */
    public function appendStates(Model_Ordini_State_OrderInterface $sof)
    {
        return $this->append("States", $sof );
    }

    
/*  **************************************************************************
 *  PERMISSION
 */    
    /**
     * 
     * @todo
     */
    private function isReferenteOrdine()
    {
        return true;
    }
    
    public function canManageOrdine()
    {
        return $this->isReferenteOrdine();
    }
    
    public function canManageCondivisione()
    {
        return $this->isReferenteOrdine();
    }
    
}
