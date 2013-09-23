<?php if($this->updated): ?>
    <div id="updated">
        <div class="isa_success">La lista dei prodotti per quest'ordine è stata aggiornata con successo!</div>
    </div>
<?php endif; ?>
    
<h3>Prodotti:</h3>
<p>Segue l'elenco di <b>tutti</b> i <a href="/prodotti/list/idproduttore/<?php echo $this->produttore->idproduttore;?>">prodotti di <?php echo $this->produttore->ragsoc;?></a>.<br />
    Puoi escludere i prodotti non disponibili cliccando sulla X a destra e modificare il prezzo nel caso di variazioni per quest'ordine.</p>

<form id="prod_ordini_form" action="/gestione-ordini/prodotti/idordine/<?php echo $this->ordine->idordine;?>" method="post">
    <?php foreach ($this->list as $key => $prodotto): ?>
        
    <div class="row row-myig<?php echo ($prodotto->selected) ? "" : " box_row_dis" ; ?>" id="box_<?php echo $prodotto->idprodotto;?>">
        <div class="col-md-8">
            <h3 class="no-margin"><?php echo $prodotto->descrizione;?></h3>
            <p>
                Categoria: <strong><?php echo $prodotto->categoria; ?></strong><br />
                <br />
                <label>Costo:</label>
                <input type="text" id="prodotto_<?php echo $prodotto->idprodotto;?>" name="prodotto[<?php echo $prodotto->idprodotto;?>]" value="<?php echo $prodotto->costo;?>" size="10" /> <strong>&euro;</strong> / <strong><?php echo $prodotto->udm; ?></strong>
                <input type="hidden" id="prod_sel_<?php echo $prodotto->idprodotto;?>" name="prod_sel[<?php echo $prodotto->idprodotto;?>]" value="<?php echo ($prodotto->selected) ? "S" : "N" ; ?>" />
            </p>
        </div>
        <div class="col-md-4">
            <a href="javascript:void(0)" onclick="jx_SelProdottoOrdine(<?php echo $prodotto->idprodotto;?>)"><img class="btn_icon <?php echo ($prodotto->selected) ? "delete" : "ok" ; ?>" src="/images/icon/empty_32.png" id="img_sel_<?php echo $prodotto->idprodotto;?>" /></a>
        </div>
    </div>
    <?php endforeach; ?>
    <fieldset>
    <button type="submit" id="submit" class="btn btn-success btn-mylg">SALVA</button>
    </fieldset>
</form>
