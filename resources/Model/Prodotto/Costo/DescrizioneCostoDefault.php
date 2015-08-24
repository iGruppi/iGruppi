<?php
class Model_Prodotto_Costo_DescrizioneCostoDefault
    implements Model_Prodotto_Costo_InterfaceCalculate
{
    public function calculate(Model_Prodotto_Mediator_Mediator $prodotto)
    {
        return number_format($prodotto->getCosto(), 2, ",", ".") . " " . $prodotto->getUdmDescrizione();
    }
}