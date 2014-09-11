<?php
/**
 * This is a Concrete Product PRODOTTI for ANAGRAFICA
 */
class Model_AF_Anagrafica_Prodotti extends Model_AF_Prodotti
{
    
    public function addProdotto(stdClass $values)
    {
        $director = new Model_Builder_Prodotto_Director();
        $prodotto = $director->build( new Model_Builder_Prodotto_AnagraficaProdottoBuilder() );
        $prodotto->setDataByObject($values);
        $this->_setProdotto($prodotto);
    }

}
