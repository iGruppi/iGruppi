<h2>Listino Prodotti di <strong><?php echo $this->produttore->ragsoc;?></strong></h2>

<div class="row">
  <div class="col-md-8">
      
<?php if($this->updated): ?>
    <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      Prodotto aggiornato con <strong>successo</strong>!
    </div>
<?php endif; ?>

      
<?php if(count($this->listProdotti) > 0): 
    foreach ($this->listProdotti as $idcat => $cat): ?>
    <span id="cat_<?php echo $idcat; ?>" style="visibility: hidden;"><?php echo $this->listSubCat[$idcat]["categoria"]; ?></span>
<?php foreach ($cat as $idsubcat => $prodotti): ?>
        
        <?php include $this->template('prodotti/subcat-title.tpl.php'); ?>
        
<?php   foreach ($prodotti as $idprodotto): 
                $pObj = $this->lpObjs[$idprodotto];
            ?>
      
      <div class="row row-myig">
        <div class="col-md-10">
            <h3 class="no-margin"><?php echo $pObj->descrizione;?></h3>
            <p>
                Codice: <strong><?php echo $pObj->codice; ?></strong><br />
                Costo: <strong><?php echo $this->valuta($pObj->getPrezzoListino()); ?> / <?php echo $pObj->udm; ?></strong><br />
            <?php if(!$pObj->isAttivo()): ?>
                <strong class="alert_red">Disabilitato</strong> (Non viene inserito quando crei un nuovo ordine)
            <?php endif; ?>
            </p>
        </div>
        <div class="col-md-2">
        <?php if($this->produttore->refObj->is_Referente()): ?>
            <a class="btn btn-success" href="/prodotti/edit/idprodotto/<?php echo $idprodotto;?>">Modifica</a>
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
    <div class="bs-sidebar" data-spy="affix" role="complementary">
<?php if($this->produttore->refObj->is_Referente()): ?>    
      <a class="btn btn-default btn-mylg" href="/prodotti/add/idproduttore/<?php echo $this->produttore->idproduttore;?>"><span class="glyphicon glyphicon-plus"></span> Nuovo prodotto</a>
      <br />
      <br />
<?php endif; ?>
      <?php echo $this->partial('prodotti/subcat-navigation.tpl.php', array('listSubCat' => $this->listSubCat)); ?>
    </div>
  </div>

</div>
