<?php

/**
 * Description of Produttori_Prodotti
 * 
 * @author Davide Gullo <gullo at m4ss.net>
 * 
 */
class Model_Prodotti 
    extends Model_Builder_Prodotti
{
    
    /**
     * add Prodotto to the array of the Product objects
     * @param stdClass $values
     */
    public function addProdotto(stdClass $values)
    {
        $builderProdotto = new Model_Builder_Prodotto_ProdottoBuilder();
        $director = new Model_Builder_Prodotto_Director();
        $prodotto = $director->build($builderProdotto);
        $prodotto->setDataByObject($values);
        $this->_prodotti[$prodotto->getIdProdotto()] = $prodotto;
        
        // call addProdotto from subclass
        parent::addProdotto($values);
    }
    
    
}
