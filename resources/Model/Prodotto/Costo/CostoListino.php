<?php
class Model_Prodotto_Costo_CostoListino
    implements Model_Prodotto_Costo_InterfaceCalculate
{
    public function calculate(Model_Prodotto_Mediator_Mediator $prodotto)
    {
        return $prodotto->getCostoListino();
    }
}