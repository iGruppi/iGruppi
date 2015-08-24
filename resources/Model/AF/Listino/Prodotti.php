<?php
/**
 * This is a Concrete Product PRODOTTI for LISTINO
 */
class Model_AF_Listino_Prodotti extends Model_AF_Prodotti
{
    /**
     * Build the correct Prodotto for this context
     * @return Model_Prodotto_Mediator_Mediator
     */
    protected function _buildProdotto()
    {
        $prodotto = new Model_Prodotto_Mediator_Mediator();
        $prodotto->setDefaultStrategyContext_Listino();
        return $prodotto;
    }
    
    public function countOutOfListino()
    {
        $outOfListino = 0;
        if($this->countProdotti() > 0) 
        {
            foreach ($this->getProdotti() AS $prodotto) {
                if(!$prodotto->isInListino())
                {
                    $outOfListino++;
                }
            }
        }
        return $outOfListino;
    }
    
}
