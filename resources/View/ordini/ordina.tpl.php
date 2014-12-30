<form id="prod_ordini_form" class="ordini" action="/ordini/ordina/idordine/<?php echo $this->ordine->getIdOrdine();?>" method="post">

<div class="row">
  <div class="col-md-8">
<?php if($this->updated): ?>
    <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      La lista dei prodotti ordinati è stata aggiornata con <strong>successo</strong>!
    </div>
<?php endif; ?>
    
    <h3>Ordine <strong class="<?php echo $this->ordine->getStatusCSSClass(); ?>"><?php echo $this->ordine->getStateName(); ?></strong> il <?php echo $this->date($this->ordine->getDataInizio(), '%d/%m/%Y');?> alle <?php echo $this->date($this->ordine->getDataInizio(), '%H:%M');?></h3>
<?php if($this->ordine->is_Aperto()): ?>
    <p>
        Chiusura prevista il <strong><?php echo $this->date($this->ordine->getDataFine(), '%d/%m/%Y');?></strong> alle <?php echo $this->date($this->ordine->getDataFine(), '%H:%M');?></strong>
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
            // GET Prodotto object from cuObj (Model_Ordini_Calcoli_Utenti)
            $prodotto = $this->cuObj->getProdotto($idprodotto);
    ?>
        
      <div class="row row-myig<?php if(!$prodotto->isDisponibile()) { echo " box_row_dis"; } ; ?>">
        <div class="col-md-9">
            <h3 class="no-margin"><?php echo $prodotto->getDescrizioneListino();?></h3>
            <p>
                Categoria: <strong><?php echo $prodotto->categoria_sub; ?></strong><br />
                <?php echo $this->partial('prodotti/price-box.tpl.php', array('prodotto' => $prodotto)); ?>
        <?php if($prodotto->note != ""): ?>
                <a href="javascript:void(0)" class="note" data-toggle="popover" title="" data-content="<?php echo $prodotto->getNoteListino(); ?>">Visualizza note</a>
        <?php endif; ?>
            </p>
        </div>
        <div class="col-md-3">
            <div class="sub_menu">
            <?php if($prodotto->isDisponibile()):
                    $qta_ordinata = isset($this->prodottiIduser[$idprodotto]) ? $this->prodottiIduser[$idprodotto]->getQta() : 0;
                ?>
<script>
    // Start these procedures always
	$(document).ready(function(){
        Trolley.initByParams(<?php echo $idprodotto;?>, <?php echo $prodotto->getCostoListino();?>, <?php echo $prodotto->moltiplicatore; ?>, <?php echo $qta_ordinata;?>);
        Trolley_rebuildPartial(<?php echo $idprodotto;?>);
    });
</script>
                <a class="menu_icon" href="javascript:void(0)" onclick="Trolley_setQtaProdotto(<?php echo $idprodotto;?>, '+')">+</a>
                <input readonly class="prod_qta" type="text" id="prod_qta_<?php echo $idprodotto;?>" name="prod_qta[<?php echo $idprodotto;?>]" value="<?php echo $qta_ordinata;?>" />
                <a class="menu_icon" href="javascript:void(0)" onclick="Trolley_setQtaProdotto(<?php echo $idprodotto;?>, '-')">-</a>
                <div class="sub_totale" id="subtotale_<?php echo $idprodotto;?>">...</div>
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
            <h4>Totale: <strong id="totale">Loading...</strong></h4>
            <button type="submit" id="submit" class="btn btn-success btn-mylg"><span class="glyphicon glyphicon-<?php echo($this->updated) ? "saved" : "save"; ?>"></span> SALVA ORDINE</button>
        </div>
        <?php echo $this->partial('prodotti/subcat-navigation.tpl.php', array('categorie' => $categorie)); ?>
    </div>
<?php endif; ?>
  </div>
</div>
</form>

<script>
    // Start these procedures always
	$(document).ready(function(){
        
        Trolley_rebuildTotal();
        
        //enable POPUP
        $('.note').popover();
    });
</script>
