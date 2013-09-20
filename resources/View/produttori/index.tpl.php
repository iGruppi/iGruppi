<h2>Elenco Produttori</h2>

<div class="row">
  <div class="col-md-8">
<?php if(count($this->list) > 0): ?>
    <?php foreach ($this->list as $key => $produttore): ?>
      
      <div class="row row-myig">
        <div class="col-md-8">
            <h3 class="no-margin<?php if($produttore->refObj->is_Referente()): ?> green<?php endif; ?>"><?php echo $produttore->ragsoc;?></h3>
            <p id="p_details_<?php echo $produttore->idproduttore;?>">
                Referente: <strong><?php echo $produttore->nome . " " . $produttore->cognome; ?></strong>
            </p>
        </div>
        <div class="col-md-4">
            <?php include $this->template('produttori/sub-menu.tpl.php'); ?>
        </div>
      </div>
    <?php endforeach; ?>

<?php else: ?>
    <h3>Nessun ordine disponibile!</h3>
<?php endif; ?>
      
  </div>
  <div class="col-md-4">
      <a class="btn btn-default btn-mylg" href="/produttori/add"><span class="glyphicon glyphicon-plus"></span> Nuovo produttore</a>
  </div>
</div>

