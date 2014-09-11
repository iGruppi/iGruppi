<?php
/**
 * This is a Concrete Product PRODOTTI for LISTINO
 */
class Model_AF_Listino_Prodotti extends Model_AF_Prodotti
{
    
    public function countOutOfListino()
    {
        $outOfListino = 0;
        if($this->count() > 0) 
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
    
    public function addProdotto(stdClass $values)
    {
        $director = new Model_Builder_Prodotto_Director();
        $prodotto = $director->build( new Model_Builder_Prodotto_ListinoProdottoBuilder() );
        $prodotto->setDataByObject($values);
        $this->_setProdotto($prodotto);
    }

}
