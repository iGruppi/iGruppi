<?php
/**
 *  This coulb be an interface but I prefer an abstract class to leave the possibility
 *  to init methods directly in the subclasses
 */
abstract class Model_Builder_Prodotto_BuilderInterface
{
    /**
     * @var Parts\Prodotto
     * 
     */
    protected $prodotto;

    /**
     * @return void
     */
    public function createProdotto(){ }

    /**
     * @return void
     */
    public function addDatiProdotto()
    {
        $this->prodotto->setPart('idprodotto', new Model_Builder_Parts_Id());
        $this->prodotto->setPart('idproduttore', new Model_Builder_Parts_Id());
        $this->prodotto->setPart('iduser_creator', new Model_Builder_Parts_Id());
        $this->prodotto->setPart('codice', new Model_Builder_Parts_Text());
        $this->prodotto->setPart('descrizione', new Model_Builder_Parts_Text());
        $this->prodotto->setPart('udm', new Model_Builder_Parts_Text());
        $this->prodotto->setPart('costo', new Model_Builder_Parts_Float());
        $this->prodotto->setPart('moltiplicatore', new Model_Builder_Parts_Float());
        $this->prodotto->setPart('aliquota_iva', new Model_Builder_Parts_Int());
        $this->prodotto->setPart('attivo', new Model_Builder_Parts_FlagSN());
        $this->prodotto->setPart('production', new Model_Builder_Parts_FlagSN());
        $this->prodotto->setPart('note', new Model_Builder_Parts_Text());
    }

    /**
     * @return void
     */
    public function addCategorie()
    {
        $this->prodotto->setPart('idcat', new Model_Builder_Parts_Id());
        $this->prodotto->setPart('idsubcat', new Model_Builder_Parts_Id());
        $this->prodotto->setPart('categoria', new Model_Builder_Parts_Text());
        $this->prodotto->setPart('categoria_sub', new Model_Builder_Parts_Text());

    }
    
    /**
     * @return void
     */
    public function addDatiListino() {
        $this->prodotto->setPart('idlistino', new Model_Builder_Parts_Id());
        $this->prodotto->setPart('descrizione_listino', new Model_Builder_Parts_Text());
        $this->prodotto->setPart('costo_listino', new Model_Builder_Parts_Float());
        $this->prodotto->setPart('note_listino', new Model_Builder_Parts_Text());
        $this->prodotto->setPart('attivo_listino', new Model_Builder_Parts_FlagSN());
    }    

    /**
     * @return void
     */
    public function addDatiOrdine(){
        $this->prodotto->setPart('idordine', new Model_Builder_Parts_Id());
        $this->prodotto->setPart('costo_ordine', new Model_Builder_Parts_Float());
        $this->prodotto->setPart('offerta_ordine', new Model_Builder_Parts_FlagSN());
        $this->prodotto->setPart('sconto_ordine', new Model_Builder_Parts_Int());
        $this->prodotto->setPart('disponibile_ordine', new Model_Builder_Parts_FlagSN());
    }    

    
    /**
     * @return Parts\Prodotto
     */
    public function getProdotto()
    {
        return $this->prodotto;
    }
}
