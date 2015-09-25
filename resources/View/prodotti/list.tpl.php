<h2>Prodotti di <strong><?php echo $this->produttore->ragsoc;?></strong></h2>

<div class="row">
  <div class="col-md-8">
      
<?php if($this->updated > 0): ?>
    <div class="alert alert-success alert-dismissable" id="alert_save_box">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      Prodotto aggiornato con <strong>successo</strong>!
    </div>
<?php endif; ?>

<div class="row row-myig" id="search_no_result" style="display: none;">
    <div class="col-md-12">
        <div class="alert alert-danger" role="alert">Nessun prodotto trovato.</div>            
    </div>
</div>

<div id="search_num_result" style="display:none;">
    <h3>Trovati <strong>_</strong> prodotti</h3>
</div>      
      
<?php 
$categorie = $this->prodotti->getProdottiWithCategoryArray();
if(count($categorie) > 0): 
    foreach ($categorie AS $cat): 
?>
    <span class="categorie_hidden" id="cat_<?php echo $cat->getId(); ?>" style="visibility: hidden;"><?php echo $cat->getDescrizione(); ?></span>
<?php foreach ($cat->getSubcat() AS $subcat):
        // create Sub Cat Title
        echo $this->partial('prodotti/subcat-title.tpl.php', array('cat' => $cat, 'subcat' => $subcat));
        // get Prodotti List in this Subcat
        foreach ($subcat->getProdotti() AS $prodotto): 
            $pObj = $prodotto->getProdotto(); 
?>
      <div class="row row-myig" id="prod_<?php echo $pObj->getIdProdotto();?>">
        <div class="col-md-10">
            <h3 class="no-margin product_descrizione"><?php echo $pObj->getDescrizioneAnagrafica();?></h3>
            <p>
                Categoria: <strong><?php echo $pObj->getSubCategoria(); ?></strong><br />
                Codice: <strong><?php echo $pObj->getCodice(); ?></strong><br />
            <?php echo $this->partial('prodotti/price-box.tpl.php', array('prodotto' => $pObj)); ?>
            <?php if(!$pObj->getAttivoAnagrafica()): ?>
                <strong class="alert_red">NON ATTIVO</strong> (Non viene inserito quando crei un nuovo ordine)
            <?php endif; ?>
            </p>
        </div>
        <div class="col-md-2">
        <?php if(Zend_Registry::get("permsProduttori")->canEditProdotti($this->produttore->idproduttore)): ?>
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
  <div class="col-md-3 col-md-offset-1">
    <div class="bs-sidebar" data-spy="affix" data-offset-top="76" role="complementary">
<?php if(Zend_Registry::get("permsProduttori")->canAddProdotti($this->produttore->idproduttore)): ?>
      <a class="btn btn-default btn-mylg" href="/prodotti/add/idproduttore/<?php echo $this->produttore->idproduttore;?>"><span class="glyphicon glyphicon-plus"></span> Nuovo prodotto</a>
      <br />
      <br />
      <input type="text" name="search_products" id="search_products" placeholder="Cerca prodotto..." oninput="searchProducts(this.value);" />
      <br />
<?php endif; ?>
      <?php echo $this->partial('prodotti/subcat-navigation.tpl.php', array('categorie' => $categorie )); ?>
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