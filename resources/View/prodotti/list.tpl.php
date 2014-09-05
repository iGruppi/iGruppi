<h2>Prodotti di <strong><?php echo $this->produttore->ragsoc;?></strong></h2>

<div class="row">
  <div class="col-md-8">
      
<?php if($this->updated > 0): ?>
    <div class="alert alert-success alert-dismissable" id="alert_save_box">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      Prodotto aggiornato con <strong>successo</strong>!
    </div>
<?php endif; ?>

      
<?php if($this->prodotti->count() > 0): 
    foreach ($this->prodotti->getCategorie()->getChildren() AS $cat): ?>
    <span id="cat_<?php echo $cat->getId(); ?>" style="visibility: hidden;"><?php echo $cat->getDescrizione(); ?></span>
<?php foreach ($cat->getChildren() AS $subcat):
        // create Sub Cat Title
        echo $this->partial('prodotti/subcat-title.tpl.php', array('cat' => $cat, 'subcat' => $subcat));
        // get Prodotti List in this Subcat
        foreach ($subcat->getProdotti() AS $idprodotto): 
        $pObj = $this->prodotti->getProdottoById($idprodotto);
?>
      <div class="row row-myig" id="prod_<?php echo $pObj->getIdProdotto();?>">
        <div class="col-md-10">
            <h3 class="no-margin"><?php echo $pObj->getDescrizione();?></h3>
            <p>
                Codice: <strong><?php echo $pObj->getCodice(); ?></strong><br />
            <?php echo $this->partial('prodotti/price-box.tpl.php', array('prodotto' => $pObj)); ?>
            <?php if(!$pObj->getAttivo()): ?>
                <strong class="alert_red">Disabilitato</strong> (Non viene inserito quando crei un nuovo ordine)
            <?php endif; ?>
            </p>
        </div>
        <div class="col-md-2">
        <?php if($this->userSessionVal->refObject->canEditProdotti($this->produttore->idproduttore)): ?>
            <a class="btn btn-success" href="/prodotti/edit/idprodotto/<?php echo $pObj->getIdProdotto();?>">Modifica</a>
        <?php endif; ?>
        </div>
      </div>
      
      <?php endforeach; ?>
    <?php endforeach; ?>
  <?php endforeach; ?>
<?php else: ?>
    <h3>Nessun prodotto!</h3>
<?php endif; ?>
  </div>
  <div class="col-md-4 col-right">
    <div class="bs-sidebar" data-spy="affix" data-offset-top="76" role="complementary">
<?php if($this->userSessionVal->refObject->canAddProdotti($this->produttore->idproduttore)): ?>
      <a class="btn btn-default btn-mylg" href="/prodotti/add/idproduttore/<?php echo $this->produttore->idproduttore;?>"><span class="glyphicon glyphicon-plus"></span> Nuovo prodotto</a>
      <br />
      <br />
<?php endif; ?>
      <?php echo $this->partial('prodotti/subcat-navigation.tpl.php', array('categorie' => $this->prodotti->getCategorie())); ?>
    </div>
  </div>
</div>
<?php if($this->updated > 0): ?>
<script>
    $(function() {
        var myTag = "#prod_<?php echo $this->updated; ?>";
        $('html,body').animate({scrollTop: ($(myTag).offset().top - 100)});
        $('#alert_save_box').prependTo(myTag).fadeOut(10000);
    });
</script>
<?php endif; ?>