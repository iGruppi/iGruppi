<?php
/**
 * Class OrderFactory
 */
class Model_Ordini_State_OrderFactory
{
    private function __construct()
    {
        throw new \Exception('Can not instance the OrderFactory class!');
    }

    /**
     * @param int $id
     *
     * @return CreateOrder|ShippingOrder
     * @throws \Exception
     */
    public static function getOrder(stdclass $ordine)
    {

        if($ordine->archiviato != "S") {
            $startObj = self::getDateObj($ordine->data_inizio);
            $endObj = self::getDateObj($ordine->data_fine);

            $timestampNow = Zend_Date::now()->toString("U");
            if( $timestampNow < $startObj->toString("U") ) {
                return new Model_Ordini_State_States_Pianificato($ordine);
                
            } else if(
                $timestampNow >= $startObj->toString("U") &&
                $timestampNow <= $endObj->toString("U")
            ) {
                return new Model_Ordini_State_States_Aperto($ordine);
                
            } else if( 
                $timestampNow > $endObj->toString("U")
            ) {
                
                $invObj = self::getDateObj($ordine->data_inviato);
                $arrObj = self::getDateObj($ordine->data_arrivato);
                $conObj = self::getDateObj($ordine->data_consegnato);
                
                if( is_null($ordine->data_inviato)) {
                    return new Model_Ordini_State_States_Chiuso($ordine);
                } else if(
                    $timestampNow >= $invObj->toString("U") &&
                    is_null($ordine->data_arrivato)
                ) {
                    return new Model_Ordini_State_States_Inviato($ordine);
                } else if(
                    $timestampNow >= $arrObj->toString("U") &&
                    is_null($ordine->data_consegnato)
                ) {
                    return new Model_Ordini_State_States_Arrivato($ordine);
                } else if(
                    $timestampNow >= $conObj->toString("U")
                ) {
                    return new Model_Ordini_State_States_Consegnato($ordine);
                }
                    
            }
        } else {
            return new Model_Ordini_State_States_Archiviato($ordine);
        }
    }
    
    
    private static function getDateObj($dt) {
        return new Zend_Date($dt, "y-MM-dd HH:mm:ss");
    }
    
    
}
