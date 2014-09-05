<?php

/**
 * Description of Listini_Prodotti
 * 
 * @author Davide Gullo <gullo at m4ss.net>
 * 
 */
class Model_Listini_Prodotti 
    extends Model_Builder_Prodotti
{
    
    public function countOutOfListino()
    {
        $outOfListino = 0;
        if($this->count() > 0) 
        {
            foreach ($this->_prodotti as $idprodotto => $prodotto) {
                if(!$prodotto->isInListino())
                {
                    $outOfListino++;
                }
            }
        }
        return $outOfListino;
    }
    
    public function addProdotto(stdClass $values)
    {
        $builderProdotto = new Model_Builder_Prodotto_ListinoProdottoBuilder();
        $director = new Model_Builder_Prodotto_Director();
        $prodotto = $director->build($builderProdotto);
        $prodotto->setDataByObject($values);
        $this->_prodotti[$prodotto->getIdProdotto()] = $prodotto;
        
        // call addProdotto from subclass
        parent::addProdotto($values);
    }
    

}
