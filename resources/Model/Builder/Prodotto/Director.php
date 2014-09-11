<?php
/**
 * Director is part of the builder pattern. It knows the interface of the builder
 * and builds a complex object with the help of the builder. 
 */
class Model_Builder_Prodotto_Director
{
    /**
     * @param BuilderInterface $builder
     *
     * @return Parts\Prodotto
     */
    public function build(Model_Builder_Prodotto_BuilderInterface $builder)
    {
        $builder->createProdotto();
        $builder->addDatiProdotto();    // data comes from prodotti
        $builder->addCategorie();      // data comes from categorie and categorie_sub
        $builder->addDatiListino();    // data comes from listini_prodotti
        $builder->addDatiOrdine();     // data comes from ordini_prodotti

        return $builder->getProdotto();
    }
}
