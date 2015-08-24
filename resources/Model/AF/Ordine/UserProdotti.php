<?php
/**
 * This is a Concrete Product USER-PRODOTTI for ORDINE
 */
class Model_AF_Ordine_UserProdotti extends Model_AF_Prodotti
{
    /**
     * Build the correct Prodotto for this context
     * @return Model_Prodotto_Mediator_Mediator
     */
    protected function _buildProdotto()
    {
        $prodotto = new Model_Prodotto_Mediator_Mediator();
        $prodotto->setDefaultStrategyContext_Ordine();
        // decorate Prodotto with QtaDecorator
        $prodottoQta = new Model_Prodotto_UserOrdine_QtaDecorator($prodotto);
        return $prodottoQta;
    }    
}
