<h2>Gestione Ordini</h2>

<div class="row">
  <div class="col-md-8">
      
<?php if($this->updated): ?>
    <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      L'ordine è stato <strong>inserito con successo</strong>!
    </div>
<?php endif; ?>
      
<?php if(count($this->ordini) > 0): ?>
    <?php foreach ($this->ordini as $key => $ordine): ?>
      <div id="ordine_<?php echo $ordine->getIdOrdine();?>">
      <?php echo $this->partial('gestioneordini/index-ordine.tpl.php', array('ordine' => $ordine)); ?>
      </div>
    <?php endforeach; ?>
<?php else: ?>
    <h3>Nessun ordine per questo produttore!</h3>
<?php endif; ?>
  </div>
  <div class="col-md-1">&nbsp;</div>
  <div class="col-md-3">
      <a class="btn btn-default btn-mylg" href="/gestione-ordini/new"><span class="glyphicon glyphicon-plus"></span> Nuovo ordine</a>
  </div>    
</div>
