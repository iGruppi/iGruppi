<div class="row">
  <div class="col-md-8">
    
    <h3>Ordine <strong class="<?php echo $this->ordine->getStatusCSSClass(); ?>"><?php echo $this->ordine->getStateName(); ?></strong> il <?php echo $this->date($this->ordine->getDataInizio(), '%d/%m/%Y');?> alle <?php echo $this->date($this->ordine->getDataInizio(), '%H:%M');?></h3>
<?php if($this->ordine->is_Aperto()): ?>
    <p>
        Chiusura prevista il <strong><?php echo $this->date($this->ordine->getDataFine(), '%d/%m/%Y');?></strong> alle <?php echo $this->date($this->ordine->getDataFine(), '%H:%M');?></strong>
    </p>
<?php endif; ?>

<?php echo $this->partial('ordini/box-note.tpl.php', array('ordine' => $this->ordine)); ?>

<?php 
     $categorie = $this->ordine->getProdottiWithCategoryArray();
     if(count($categorie) > 0): 
        foreach ($categorie AS $cat): ?>
    <span id="cat_<?php echo $cat->getId(); ?>" style="visibility: hidden;"><?php echo $cat->getDescrizione(); ?></span>
<?php       foreach ($cat->getSubcat() AS $subcat): 
            echo $this->partial('prodotti/subcat-title.tpl.php', array('cat' => $cat, 'subcat' => $subcat));
                foreach ($subcat->getProdotti() AS $prodObj):
                    $prodotto = $prodObj->getProdotto(); 
                    $idprodotto = $prodotto->getIdProdotto();
                 //Zend_Debug::dump($prodotto);die;
    ?>
        
      <div class="row row-myig<?php if(!$prodotto->isDisponibile()) { echo " box_row_dis"; } ; ?>">
        <div class="col-md-9">
            <h3 class="no-margin"><?php echo $prodotto->getDescrizioneListino();?></h3>
            <p>
                Categoria: <strong><?php echo $prodotto->getSubCategoria(); ?></strong><br />
                <?php echo $this->partial('prodotti/price-box.tpl.php', array('prodotto' => $prodotto)); ?>
        <?php if($prodotto->getNoteListino() != ""): ?>
                <a href="javascript:void(0)" class="note" data-toggle="popover" title="" data-content="<?php echo $prodotto->getNoteListino(); ?>">Visualizza note</a>
        <?php endif; ?>
            </p>
        </div>
        <div class="col-md-3">
            <div class="sub_menu">
            <?php if($prodotto->isDisponibile()):
                    $qta_ordinata = $prodotto->getQta_ByIduser($this->iduser);
                ?>
<script>
    // Start these procedures always
	$(document).ready(function(){
        Trolley.initByParams(<?php echo $idprodotto;?>, <?php echo $prodotto->getIdListino(); ?>, <?php echo $prodotto->getCostoListino();?>, <?php echo $prodotto->getMoltiplicatore(); ?>, <?php echo $qta_ordinata;?>);
        Trolley_rebuildPartial(<?php echo $idprodotto;?>);
    });
</script>
                <a class="menu_icon" href="javascript:void(0)" onclick="Trolley_setQtaProdotto(<?php echo $idprodotto;?>, '+')">+</a>
                <input readonly class="prod_qta" type="text" id="prod_qta_<?php echo $idprodotto;?>" value="<?php echo $qta_ordinata;?>" />
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
<?php if(count($categorie) > 0): ?>      
    <div class="bs-sidebar" data-spy="affix" data-offset-top="76" role="complementary">
        <div class="totale">
            <h4>Totale: <strong id="totale">Loading...</strong></h4>
            <button id="auto_saver" class="btn btn-default" disabled="disabled"><span class="glyphicon glyphicon-ok"></span> Salvataggio automatico</button>
        </div>
        <?php echo $this->partial('prodotti/subcat-navigation.tpl.php', array('categorie' => $categorie)); ?>
    </div>
<?php endif; ?>
  </div>
</div>

<script>
    // Start these procedures always
	$(document).ready(function(){
        
        // SET idordine
        Trolley.idordine = <?php echo $this->ordine->getIdOrdine();?>;
        // Rebuils Totale after loading page
        Trolley_rebuildTotal();
        
        //enable POPUP
        $('.note').popover();
    });
</script>
