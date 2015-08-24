<?php
/**
 * This is a Concrete Product PRODOTTI for ANAGRAFICA
 */
class Model_AF_Anagrafica_Prodotti extends Model_AF_Prodotti
{
    /**
     * Build the correct Prodotto for this context
     * @return Model_Prodotto_Mediator_Mediator
     */
    protected function _buildProdotto()
    {
        $prodotto = new Model_Prodotto_Mediator_Mediator();
        $prodotto->setDefaultStrategyContext_Anagrafica();
        return $prodotto;
    }
}
