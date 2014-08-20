<h2>Listini</h2>

<div class="row">
  <div class="col-md-8">
      
<?php if(count($this->listini) > 0): ?>
    <?php foreach ($this->listini as $key => $lObj): ?>
      
      <div class="row row-myig">
        <div class="col-md-12">
            <h3 class="no-margin <?php echo ($lObj->is_Referente()) ? "green" : "text-dark"; ?>"><?php echo $lObj->getDescrizione();?></h3>
        </div>
        <div class="col-md-8">
            <p>Produttore: <strong>Example & Co.</strong></p>
            <h4><span class="text-muted">Prodotti:</span> Cat1, Cat2, Cat3, ecc.</h4>
        </div>
        <div class="col-md-4">
            <?php include $this->template('listini/sub-menu.tpl.php'); ?>
        </div>
      </div>
    <?php endforeach; ?>
<?php else: ?>
    <h3>Nessun listino disponibile!</h3>
<?php endif; ?>
      
  </div>
  <div class="col-md-1">&nbsp;</div>
  <div class="col-md-3">
      <a class="btn btn-default btn-mylg" href="/listini/add"><span class="glyphicon glyphicon-plus"></span> Nuovo listino</a>
  </div>
</div>