<?php
class Model_Prodotti_Costo_Strategy_TotaleIvaAnagrafica
    implements Model_Prodotti_Costo_Strategy_InterfaceCalculate
{
    public function calculate(Model_Builder_Prodotto_Parts_Product $prodotto)
    {
        return ($prodotto->getCosto() - $prodotto->CostoSenzaIva());
    }
}