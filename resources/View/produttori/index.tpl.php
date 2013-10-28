<h2>Elenco Produttori</h2>

<div class="row">
  <div class="col-md-8">
      
<?php if(count($this->list) > 0): ?>
    <?php foreach ($this->list as $key => $produttore): ?>
      
      <div class="row row-myig">
        <div class="col-md-12">
            <h3 class="no-margin <?php echo ($produttore->refObj->is_Referente()) ? "green" : "text-dark"; ?>"><?php echo $produttore->ragsoc;?></h3>
        </div>
        <div class="col-md-8">
            <p>Referente: <strong><?php echo $produttore->nome . " " . $produttore->cognome; ?></strong></p>
        <?php if( isset($this->arCat[$produttore->idproduttore]) ): ?>
            <h4><span class="text-muted">Prodotti:</span> <?php echo implode(", ", $this->arCat[$produttore->idproduttore]); ?></h4>
        <?php endif; ?>
        </div>
        <div class="col-md-4">
            <?php include $this->template('produttori/sub-menu.tpl.php'); ?>
        </div>
      </div>
    <?php endforeach; ?>
<?php else: ?>
    <h3>Nessun produttore disponibile!</h3>
<?php endif; ?>
      
  </div>
  <div class="col-md-1">&nbsp;</div>
  <div class="col-md-3">
      <a class="btn btn-default btn-mylg" href="/produttori/add"><span class="glyphicon glyphicon-plus"></span> Nuovo produttore</a>
  </div>
</div>

