<h2>Listino Prodotti di <strong><?php echo $this->produttore->ragsoc;?></strong></h2>

<div class="row">
  <div class="col-md-8">
      
<?php if($this->updated): ?>
    <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      Prodotto aggiornato con <strong>successo</strong>!
    </div>
<?php endif; ?>

      
<?php if(count($this->listProdotti) > 0): ?>
    <?php 
    foreach ($this->listProdotti as $idcat => $cat): ?>
    <span id="cat_<?php echo $idcat; ?>" style="visibility: hidden;"><?php echo $this->listSubCat[$idcat]["categoria"]; ?></span>
<?php foreach ($cat as $idsubcat => $prodotti): ?>
        <h2 id="subcat_<?php echo $idsubcat; ?>" class="subcat-title"><?php echo $this->listSubCat[$idcat]["categoria"]; ?> - <?php echo $this->listSubCat[$idcat]["subcat"][$idsubcat]; ?></h2>
        
<?php   foreach ($prodotti as $idprodotto => $prodotto): ?>
      
      <div class="row row-myig">
        <div class="col-md-10">
            <h3 class="no-margin"><?php echo $prodotto->descrizione;?></h3>
            <p>
                Codice: <strong><?php echo $prodotto->codice; ?></strong><br />
                Categoria: <strong><?php echo $prodotto->categoria; ?></strong><br />
                Costo: <strong><?php echo $this->valuta($prodotto->costo); ?> / <?php echo $prodotto->udm; ?></strong><br />
            <?php if( !$this->yesnoToBool($prodotto->attivo)): ?>
                <strong class="alert_red">Disabilitato</strong> (Non disponibile negli ordini)
            <?php endif; ?>
            </p>
        </div>
        <div class="col-md-2">
        <?php if($this->produttore->refObj->is_Referente()): ?>
            <a class="btn btn-success" href="/prodotti/edit/idprodotto/<?php echo $prodotto->idprodotto;?>">Modifica</a>
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
<?php if($this->produttore->refObj->is_Referente()): ?>    
  <div class="col-md-4 col-right">
    <div class="bs-sidebar" data-spy="affix" role="complementary">
      <a class="btn btn-default btn-mylg" href="/prodotti/add/idproduttore/<?php echo $this->produttore->idproduttore;?>"><span class="glyphicon glyphicon-plus"></span> Nuovo prodotto</a>
      <br />
      <br />
      <?php echo $this->partial('prodotti/subcat-navigation.tpl.php', array('listSubCat' => $this->listSubCat)); ?>
    </div>
  </div>
<?php endif; ?>

</div>
