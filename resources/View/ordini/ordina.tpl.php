<h2>Produttore <strong><?php echo $this->produttore->ragsoc;?></strong></h2>
<form id="prod_ordini_form" class="ordini" action="/ordini/ordina/idordine/<?php echo $this->ordine->idordine;?>" method="post">

<div class="row">
  <div class="col-md-8">
<?php if($this->updated): ?>
    <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      La lista dei prodotti ordinati è stata aggiornata con <strong>successo</strong>!
    </div>
<?php endif; ?>
    
    <h3>Ordine <strong class="<?php echo $this->statusObj->getStatus(); ?>"><?php echo $this->statusObj->getStatus(); ?></strong> il <?php echo $this->date($this->ordine->data_inizio, '%d/%m/%Y');?> alle <?php echo $this->date($this->ordine->data_inizio, '%H:%M');?></h3>
<?php if($this->statusObj->is_Aperto()): ?>
    <p>
        Chiusura prevista il <strong><?php echo $this->date($this->ordine->data_fine, '%d/%m/%Y');?></strong> alle <?php echo $this->date($this->ordine->data_fine, '%H:%M');?></strong>
    </p>
<?php endif; ?>

<?php echo $this->partial('ordini/box-note.tpl.php', array('ordine' => $this->ordine, 'produttore' => $this->produttore)); ?>
    
<?php 
    if(count($this->listProdotti) > 0):
    $totale = 0;
    foreach ($this->listProdotti as $idcat => $cat): ?>
    <span id="cat_<?php echo $idcat; ?>" style="visibility: hidden;"><?php echo $this->listSubCat[$idcat]["categoria"]; ?></span>
<?php foreach ($cat as $idsubcat => $prodotti): ?>
        <h2 id="subcat_<?php echo $idsubcat; ?>" class="subcat-title"><?php echo $this->listSubCat[$idcat]["categoria"]; ?> - <?php echo $this->listSubCat[$idcat]["subcat"][$idsubcat]; ?></h2>
        
<?php   foreach ($prodotti as $idprodotto => $prodotto): ?>
        
      <div class="row row-myig">
        <div class="col-md-9">
            <h3 class="no-margin"><?php echo $prodotto->descrizione;?></h3>
            <p>
                Categoria: <strong><?php echo $prodotto->categoria_sub; ?></strong><br />
                Costo: <strong><?php echo $this->valuta($prodotto->costo_op);?></strong> / <strong><?php echo $prodotto->udm; ?></strong><br />
        <?php if($prodotto->note != ""): ?>
                <a href="javascript:void(0)" class="note" data-toggle="popover" title="" data-content="<?php echo $prodotto->note; ?>">Visualizza note</a>
        <?php endif; ?>
            </p>
        </div>
        <div class="col-md-3">
            <div class="sub_menu">
                <a class="menu_icon" href="javascript:void(0)" onclick="jx_SelQtaProdotto(<?php echo $prodotto->idprodotto;?>, '<?php echo $prodotto->costo_op;?>', '+')">+</a>
                <input readonly class="prod_qta" type="text" id="prod_qta_<?php echo $prodotto->idprodotto;?>" name="prod_qta[<?php echo $prodotto->idprodotto;?>]" value="<?php echo $prodotto->qta;?>" />
                <a class="menu_icon" href="javascript:void(0)" onclick="jx_SelQtaProdotto(<?php echo $prodotto->idprodotto;?>, '<?php echo $prodotto->costo_op;?>', '-')">-</a>
        <?php 
                $subtotale = ($prodotto->qta * $prodotto->costo_op);
                $totale += $subtotale;
        ?>
                <div class="sub_totale" id="subtotale_<?php echo $prodotto->idprodotto;?>"><?php echo $this->valuta($subtotale) ?></div>
            </div>
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
<?php endif; ?>
    
  </div>
  <div class="col-md-4 col-right">
<?php if(count($this->listProdotti) > 0): ?>      
    <div class="bs-sidebar" data-spy="affix" role="complementary">
        <div class="totale">
            <input disabled id="f_totale" type="hidden" name="f_totale" value="<?php echo $totale; ?>" />
            <h4>Totale: <b id="totale"><?php echo $this->valuta($totale) ?></b></h4>
            <button type="submit" id="submit" class="btn btn-success btn-mylg"><span class="glyphicon glyphicon-<?php echo($this->updated) ? "saved" : "save"; ?>"></span> SALVA ORDINE</button>
        </div>
        <?php echo $this->partial('prodotti/subcat-navigation.tpl.php', array('listSubCat' => $this->listSubCat)); ?>
    </div>
<?php endif; ?>
  </div>
</div>
</form>
<script>
    $('.note').popover();
</script>
