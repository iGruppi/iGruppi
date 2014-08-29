
<form id="prod_ordini_form" class="ordini" action="/gestione-ordini/prodotti/idordine/<?php echo $this->ordine->idordine;?>" method="post">
    
<div class="row">
  <div class="col-md-8">
    <h3>Prodotti:</h3>
    <p>Segue l'elenco dei <a href="/prodotti/list/idproduttore/<?php echo $this->produttore->idproduttore;?>">prodotti di <?php echo $this->produttore->ragsoc;?></a> inseriti in quest'ordine.<br />
        Puoi escludere i prodotti non disponibili cliccando sulla X a destra e modificare il prezzo nel caso di variazioni per quest'ordine.
    </p>

    <?php if(count($this->listProdotti) > 0): ?>
      <?php foreach ($this->listProdotti as $idcat => $cat): ?>
            <span id="cat_<?php echo $idcat; ?>" style="visibility: hidden;"><?php echo $this->listSubCat[$idcat]["categoria"]; ?></span>
        <?php foreach ($cat as $idsubcat => $prodotti): ?>
            <?php include $this->template('prodotti/subcat-title.tpl.php'); ?>
        <?php   foreach ($prodotti as $idprodotto): 
                $pObj = $this->lpObjs[$idprodotto];
            ?>

            <div class="row row-myig<?php echo ($pObj->isDisponibile()) ? "" : " box_row_dis" ; ?>" id="box_<?php echo $pObj->idprodotto;?>">
                <div class="col-md-9">
                    <h3 class="no-margin"><?php echo $pObj->descrizione;?></h3>
                    <p>
                        Codice: <strong><?php echo $pObj->codice; ?></strong><br />
                        <br />
                        <label>Prezzo:</label>
                        <input type="text" class="is_Number" id="prodotto_<?php echo $idprodotto;?>" name="prodotti[<?php echo $idprodotto;?>][costo]" value="<?php echo $pObj->getCostoOrdine();?>" size="10" /> <strong><?php echo $pObj->getUdmDescrizione(); ?></strong>
                        <input type="hidden" name="prodotti[<?php echo $idprodotto;?>][co]" value="<?php echo $pObj->getCostoOrdine();?>" />
                        <input type="hidden" id="prod_sel_<?php echo $idprodotto;?>" name="prodotti[<?php echo $idprodotto;?>][disponibile]" value="<?php echo ($pObj->isDisponibile()) ? "S" : "N" ; ?>" />
                    <?php if($pObj->hasPezzatura()): ?>
                        <br />[<b>**</b>] <small>Taglio/Pezzatura: <strong><?php echo $pObj->getDescrizionePezzatura(); ?></strong></small>
                    <?php endif; ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <a href="javascript:void(0)" onclick="jx_SelProdottoOrdine(<?php echo $idprodotto;?>)">
                    <?php if($pObj->isDisponibile()): ?>
                        <img data-toggle="tooltip" data-placement="bottom" title="Disabilita prodotto per quest'ordine!" class="btn_icon delete tip_info_prod" src="/images/icon/empty_32.png" id="img_sel_<?php echo $idprodotto;?>" />
                    <?php else: ?>
                        <img data-toggle="tooltip" data-placement="bottom" title="Rendi disponibile il prodotto" class="btn_icon ok tip_info_prod" src="/images/icon/empty_32.png" id="img_sel_<?php echo $idprodotto;?>" />
                    <?php endif; ?>
                    </a>
                </div>
            </div>
              <?php endforeach; ?>
            <?php endforeach; ?>
          <?php endforeach; ?>
        <div class="row bs-footer">
            <div class="col-md-12">&nbsp;</div>
        </div>
            
    <?php else: ?>
        <h3>Nessun prodotto disponibile!</h3>
        <p>Verifica l'elenco dei <a href="/prodotti/list/idproduttore/<?php echo $this->produttore->idproduttore;?>">prodotti a listino di <?php echo $this->produttore->ragsoc;?></a>.</p>
    <?php endif; ?>

  </div>
  <div class="col-md-4 col-right">
      <div class="bs-sidebar" data-spy="affix" data-offset-top="176" role="complementary">
        <div class="totale big-margin-top">
          <button type="submit" id="submit" class="btn btn-success btn-mylg"><span class="glyphicon glyphicon-<?php echo($this->updated) ? "saved" : "save"; ?>"></span> SALVA</button>
        </div>
        <?php echo $this->partial('prodotti/subcat-navigation.tpl.php', array('listSubCat' => $this->listSubCat)); ?>
      </div>
  </div>    
</div>
</form>
<script>    
    $('.tip_info_prod').tooltip('hide');
</script>