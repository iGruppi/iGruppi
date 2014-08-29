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
        $builder->addProdotto();   // data comes from prodotti
        $builder->addCategories(); // data comes from categorie and categorie_sub
        $builder->addListino();    // data comes from listini_prodotti
        $builder->addOrdine();     // data comes from ordini_prodotti
        $builder->addUserOrdine(); // data comes from ordini_user_prodotti

        return $builder->getProdotto();
    }
}
