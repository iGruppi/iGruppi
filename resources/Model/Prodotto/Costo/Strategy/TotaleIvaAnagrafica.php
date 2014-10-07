<?php
class Model_Prodotto_Costo_Strategy_TotaleIvaAnagrafica
    implements Model_Prodotto_Costo_Strategy_InterfaceCalculate
{
    public function calculate(Model_Builder_Prodotto_Parts_Product $prodotto)
    {
        return ($prodotto->getCosto() - $prodotto->CostoSenzaIva());
    }
}