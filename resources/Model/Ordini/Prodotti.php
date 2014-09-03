<?php

/**
 * Description of Ordini_Prodotti
 * 
 * @author Davide Gullo <gullo at m4ss.net>
 * 
 */
class Model_Ordini_Prodotti 
    extends Model_Builder_Prodotti
{
    
    
    public function addProdotto(stdClass $values)
    {
        $builderProdotto = new Model_Builder_Prodotto_ProdottoBuilder();
        $director = new Model_Builder_Prodotto_Director();
        $prodotto = $director->build($builderProdotto);
        $prodotto->setDataByObject($values);
        $this->_prodotti[$prodotto->getIdProdotto()] = $prodotto;
        
        // call addProdotto subclass
        parent::addProdotto($values);
    }
    
    
}
