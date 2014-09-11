<?php
class Model_Prodotti_Costo_Strategy_CostoSenzaIvaAnagrafica
    implements Model_Prodotti_Costo_Strategy_InterfaceCalculate
{
    public function calculate(Model_Builder_Prodotto_Parts_Product $prodotto)
    {
        if($prodotto->getIva() > 0) {
            $cc =  ($prodotto->getCosto() / ($prodotto->getIva() / 100 + 1));
            return round( $cc, 2, PHP_ROUND_HALF_UP);
        } else {
            return $prodotto->getCosto();
        }
    }
}