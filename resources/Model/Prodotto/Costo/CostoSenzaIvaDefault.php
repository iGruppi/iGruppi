<?php
class Model_Prodotto_Costo_CostoSenzaIvaDefault
    implements Model_Prodotto_Costo_InterfaceCalculate
{
    public function calculate(Model_Prodotto_Mediator_Mediator $prodotto)
    {
        if($prodotto->getIva() > 0) {
            $cc =  ($prodotto->getCosto() / ($prodotto->getIva() / 100 + 1));
            return round( $cc, 2, PHP_ROUND_HALF_UP);
        } else {
            return $prodotto->getCosto();
        }
    }
}