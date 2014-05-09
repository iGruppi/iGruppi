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
    foreach ($this->listProdotti as $idcat => $cat): ?>
    <span id="cat_<?php echo $idcat; ?>" style="visibility: hidden;"><?php echo $this->listSubCat[$idcat]["categoria"]; ?></span>
<?php foreach ($cat as $idsubcat => $prodotti): ?>
        <?php include $this->template('prodotti/subcat-title.tpl.php'); ?>
<?php   foreach ($prodotti as $idprodotto): 
            // GET Prodotto object from cuObj (Model_Ordini_Calcoli_Utente)
            $prodotto = $this->cuObj->getProdotto($idprodotto);
    ?>
        
      <div class="row row-myig<?php if(!$prodotto->isDisponibile()) { echo " box_row_dis"; } ; ?>">
        <div class="col-md-9">
            <h3 class="no-margin"><?php echo $prodotto->descrizione;?></h3>
            <p>
                Categoria: <strong><?php echo $prodotto->categoria_sub; ?></strong><br />
                Prezzo: <strong><?php echo $this->valuta($prodotto->getPrezzo());?></strong> / <strong><?php echo $prodotto->udm; ?></strong><br />
        <?php if($prodotto->note != ""): ?>
                <a href="javascript:void(0)" class="note" data-toggle="popover" title="" data-content="<?php echo $prodotto->note; ?>">Visualizza note</a>
        <?php endif; ?>
            </p>
        </div>
        <div class="col-md-3">
            <div class="sub_menu">
            <?php if($prodotto->isDisponibile()): ?>
                <a class="menu_icon" href="javascript:void(0)" onclick="jx_SelQtaProdotto(<?php echo $idprodotto;?>, '<?php echo $prodotto->getPrezzo();?>', '+')">+</a>
                <input readonly class="prod_qta" type="text" id="prod_qta_<?php echo $idprodotto;?>" name="prod_qta[<?php echo $idprodotto;?>]" value="<?php echo $prodotto->getQtaReale();?>" />
                <a class="menu_icon" href="javascript:void(0)" onclick="jx_SelQtaProdotto(<?php echo $idprodotto;?>, '<?php echo $prodotto->getPrezzo();?>', '-')">-</a>
                <div class="sub_totale" id="subtotale_<?php echo $idprodotto;?>"><?php echo $this->valuta($prodotto->getTotale()) ?></div>
            <?php else: ?>
                <h4 class="non-disponibile">NON disponibile!</h4>
            <?php endif; ?>
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
    <div class="bs-sidebar" data-spy="affix" data-offset-top="76" role="complementary">
        <div class="totale">
            <input disabled id="f_totale" type="hidden" name="f_totale" value="<?php echo $this->cuObj->getTotale(); ?>" />
            <h4>Totale: <strong id="totale"><?php echo $this->valuta($this->cuObj->getTotale()); ?></strong></h4>
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
