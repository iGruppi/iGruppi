<?php
class Model_Prodotto_Costo_CostoOrdine
    implements Model_Prodotto_Costo_InterfaceCalculate
{
    public function calculate(Model_Prodotto_Mediator_Mediator $prodotto)
    {
        return $prodotto->getCostoOrdine();
    }
}