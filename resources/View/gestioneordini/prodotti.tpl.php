<div id="ordine_header">
    <?php include $this->template('gestioneordini/gestione-header.tpl.php'); ?>
</div>

<form id="prod_ordini_form" class="ordini" action="/gestione-ordini/prodotti/idordine/<?php echo $this->ordine->getIdOrdine();?>" method="post">
    
<div class="row">
  <div class="col-md-8">
    <h3>Prodotti inseriti in quest'ordine:</h3>

    <?php 
    $categorie = $this->ordine->getProdottiWithCategoryArray();
    if(count($categorie) > 0): 
        foreach ($categorie AS $cat): ?>
    <span id="cat_<?php echo $cat->getId(); ?>" style="visibility: hidden;"><?php echo $cat->getDescrizione(); ?></span>
<?php       foreach ($cat->getSubcat() AS $subcat): 
            echo $this->partial('prodotti/subcat-title.tpl.php', array('cat' => $cat, 'subcat' => $subcat));

                foreach ($subcat->getProdotti() AS $prodotto): 
                    $pObj = $prodotto->getProdotto(); 
                    $idprodotto = $pObj->getIdProdotto();
?>
            <div class="row row-myig<?php echo $pObj->isDisponibile() ? "" : " box_row_dis" ; ?>" id="box_<?php echo $idprodotto;?>">
                <div class="col-md-9">
                    <h3 class="no-margin"><?php echo $pObj->getDescrizioneListino();?></h3>
                    <p>
                        Codice: <strong><?php echo $pObj->getCodice(); ?></strong><br />
                        <?php echo $this->partial('prodotti/price-box.tpl.php', array('prodotto' => $pObj)); ?>
                    </p>
                </div>
                <div class="col-md-3">
            <?php if($this->canRef_ModificaProdotti()): ?>                    
                    <a class="btn btn-success" href="/gestione-ordini/editprodotto/idordine/<?php echo $this->ordine->getIdOrdine();?>/idprodotto/<?php echo $pObj->getIdProdotto();?>">Modifica</a>
            <?php endif; ?>                    
                </div>
            </div>
              <?php endforeach; ?>
            <?php endforeach; ?>
          <?php endforeach; ?>
        <div class="row bs-footer">
            <div class="col-md-12">&nbsp;</div>
        </div>
            
    <?php else: ?>
        <h3>Nessun prodotto inserito in questo ordine!</h3>
    <?php endif; ?>

  </div>
  <div class="col-md-4 col-right">
      <div class="bs-sidebar" data-spy="affix" data-offset-top="176" role="complementary">
          <a class="btn btn-default btn-mylg" href="/gestione-ordini/addprodotto/idordine/<?php echo $this->ordine->getIdOrdine();?>"><span class="glyphicon glyphicon-plus"></span> Aggiungi prodotto</a>
          <br><br>
          <?php echo $this->partial('prodotti/subcat-navigation.tpl.php', array('categorie' => $categorie)); ?>
      </div>
  </div>    
</div>
</form>
<script>    
    $('.tip_info_prod').tooltip('hide');
</script>
