<h2>Produttore <strong><?php echo $this->produttore->ragsoc;?></strong></h2>
<h3>Ordine <strong class="<?php echo $this->statusObj->getStatus(); ?>"><?php echo $this->statusObj->getStatus(); ?></strong></h3>
<p>
    Data apertura: <strong><?php echo $this->date($this->ordine->data_inizio, '%d/%m/%Y');?></strong> alle <?php echo $this->date($this->ordine->data_inizio, '%H:%M');?></strong><br />
    Data chiusura: <strong><?php echo $this->date($this->ordine->data_fine, '%d/%m/%Y');?></strong> alle <?php echo $this->date($this->ordine->data_fine, '%H:%M');?></strong>
</p>    

<?php if(count($this->listProdotti) > 0): ?>
<form id="prod_ordini_form" action="/gestione-ordini/prodotti/idordine/<?php echo $this->ordine->idordine;?>" method="post">
    <div class="row">
      <div class="col-md-8">

    <?php if($this->updated): ?>
        <div class="alert alert-success alert-dismissable">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          La lista dei prodotti per quest'ordine è stata aggiornata con <strong>successo</strong>!
        </div>
    <?php endif; ?>   

    <h3>Prodotti:</h3>
    <p>Segue l'elenco di <b>tutti</b> i <a href="/prodotti/list/idproduttore/<?php echo $this->produttore->idproduttore;?>">prodotti di <?php echo $this->produttore->ragsoc;?></a>.<br />
        Puoi escludere i prodotti non disponibili cliccando sulla X a destra e modificare il prezzo nel caso di variazioni per quest'ordine.</p>

        <?php 
            foreach ($this->listProdotti as $idcat => $cat): ?>
            <span id="cat_<?php echo $idcat; ?>" style="visibility: hidden;"><?php echo $this->listSubCat[$idcat]["categoria"]; ?></span>
        <?php foreach ($cat as $idsubcat => $prodotti): ?>
            <?php include $this->template('prodotti/subcat-title.tpl.php'); ?>
        <?php   foreach ($prodotti as $idprodotto => $pObj): ?>

            <div class="row row-myig<?php echo ($pObj->isDisponibile()) ? "" : " box_row_dis" ; ?>" id="box_<?php echo $pObj->getIdprodotto();?>">
                <div class="col-md-8">
                    <h3 class="no-margin"><?php echo $pObj->getDescrizione();?></h3>
                    <p>
                        Codice: <strong><?php echo $pObj->getCodice(); ?></strong><br />
                        <br />
                        <label>Costo:</label>
                        <input type="text" id="prodotto_<?php echo $pObj->getIdprodotto();?>" name="prodotti[<?php echo $pObj->getIdprodotto();?>][costo]" value="<?php echo $pObj->getPrezzo();?>" size="10" /> <strong>&euro;</strong> / <strong><?php echo $pObj->getUdm(); ?></strong>
                        <input type="hidden" id="prod_sel_<?php echo $pObj->getIdprodotto();?>" name="prodotti[<?php echo $pObj->getIdprodotto();?>][disponibile]" value="<?php echo ($pObj->isDisponibile()) ? "S" : "N" ; ?>" />
                    </p>
                </div>
                <div class="col-md-4">
                    <a href="javascript:void(0)" onclick="jx_SelProdottoOrdine(<?php echo $pObj->getIdprodotto();?>)"><img class="btn_icon <?php echo ($pObj->isDisponibile()) ? "delete" : "ok" ; ?>" src="/images/icon/empty_32.png" id="img_sel_<?php echo $pObj->getIdprodotto();?>" /></a>
                </div>
            </div>
              <?php endforeach; ?>
            <?php endforeach; ?>
          <?php endforeach; ?>
      </div>
      <div class="col-md-4 col-right">
        <div class="bs-sidebar" data-spy="affix" role="complementary">
          <button type="submit" id="submit" class="btn btn-success btn-mylg"><span class="glyphicon glyphicon-<?php echo($this->updated) ? "saved" : "save"; ?>"></span> SALVA</button><br />
          <br />
          <?php echo $this->partial('prodotti/subcat-navigation.tpl.php', array('listSubCat' => $this->listSubCat)); ?>
        </div>
      </div>    
    </div>
</form>
<?php else: ?>
    <h3>Nessun prodotto disponibile!</h3>
    <p>Verifica l'elenco dei <a href="/prodotti/list/idproduttore/<?php echo $this->produttore->idproduttore;?>">prodotti a listino di <?php echo $this->produttore->ragsoc;?></a>.</p>
<?php endif; ?>
