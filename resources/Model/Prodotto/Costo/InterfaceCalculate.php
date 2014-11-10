<?php
/**
 * Strategy interface to calculate Costo
 */
interface Model_Prodotto_Costo_InterfaceCalculate
{
    /**
     * @return float valore calcolato
     */
    public function calculate(Model_Prodotto_Mediator_Mediator $prodotto);
}