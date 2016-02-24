<?php
/**
 * Class OrderFactory
 */
class Model_Ordini_State_OrderFactory
{
    private function __construct()
    {
        throw new Exception('Can not instance the OrderFactory class!');
    }
    
    
    public static function getOrderStatesArray()
    {
        return array(
            Model_Ordini_State_States_Pianificato::STATUS_NAME,
            Model_Ordini_State_States_Aperto::STATUS_NAME,
            Model_Ordini_State_States_Chiuso::STATUS_NAME,
            Model_Ordini_State_States_Inviato::STATUS_NAME,
            Model_Ordini_State_States_Arrivato::STATUS_NAME,
            Model_Ordini_State_States_Consegnato::STATUS_NAME,
            Model_Ordini_State_States_Archiviato::STATUS_NAME
        );
    }
    
    /**
     * @param int $id
     *
     * @return CreateOrder|ShippingOrder
     * @throws \Exception
     */
    public static function getOrder(stdclass $ordine)
    {

        if(!isset($ordine->archiviato) || $ordine->archiviato != "S") {
            $startObj = self::getDateObj($ordine->data_inizio);
            $endObj = self::getDateObj($ordine->data_fine);
            
            // set Timestamp for now
            $timestampNow = time();
            
            if( $timestampNow < $startObj->format("U") ) {
                return new Model_Ordini_State_States_Pianificato($ordine);
                
            } else if(
                $timestampNow >= $startObj->format("U") &&
                $timestampNow <= $endObj->format("U")
            ) {
                return new Model_Ordini_State_States_Aperto($ordine);
                
            } else if( 
                $timestampNow > $endObj->format("U")
            ) {
                
                $invObj = self::getDateObj($ordine->data_inviato);
                $arrObj = self::getDateObj($ordine->data_arrivato);
                $conObj = self::getDateObj($ordine->data_consegnato);
                
                if( is_null($ordine->data_inviato)) {
                    return new Model_Ordini_State_States_Chiuso($ordine);
                } else if(
                    $timestampNow >= $invObj->format("U") &&
                    is_null($ordine->data_arrivato)
                ) {
                    return new Model_Ordini_State_States_Inviato($ordine);
                } else if(
                    $timestampNow >= $arrObj->format("U") &&
                    is_null($ordine->data_consegnato)
                ) {
                    return new Model_Ordini_State_States_Arrivato($ordine);
                } else if(
                    $timestampNow >= $conObj->format("U")
                ) {
                    return new Model_Ordini_State_States_Consegnato($ordine);
                }
                    
            }
        } else {
            return new Model_Ordini_State_States_Archiviato($ordine);
        }
    }
    
    
    private static function getDateObj($dt) {
        return DateTime::createFromFormat("Y-m-d H:i:s", $dt);
    }
    
    
}
