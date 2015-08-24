<?php
class Model_Prodotto_Costo_TotaleIvaDefault
    implements Model_Prodotto_Costo_InterfaceCalculate
{
    public function calculate(Model_Prodotto_Mediator_Mediator $prodotto)
    {
        return ($prodotto->getCosto() - $prodotto->getCostoSenzaIva());
    }
}