<?php
/**
 * Strategy interface to calculate Costo
 */
interface Model_Prodotto_Costo_Strategy_InterfaceCalculate
{
    /**
     * @return float valore calcolato
     */
    public function calculate(Model_Builder_Prodotto_Parts_Product $prodotto);
}