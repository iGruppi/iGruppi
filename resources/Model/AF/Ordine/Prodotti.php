<?php
/**
 * This is a Concrete Product PRODOTTI for ORDINE
 */
class Model_AF_Ordine_Prodotti extends Model_AF_Prodotti
{
    
    
    public function addProdotto(stdClass $values)
    {
        $director = new Model_Builder_Prodotto_Director();
        $prodotto = $director->build( new Model_Builder_Prodotto_OrdineProdottoBuilder() );
        $prodotto->setDataByObject($values);
        $this->_setProdotto($prodotto);
        
        // call addProdotto subclass
        parent::addProdotto($values);
    }

    
}
