<h2>Gestione Ordini</h2>

<div class="row" id="listwithsidebar">
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
    <h3>Nessun ordine in questo stato.</h3>
<?php endif; ?>
  </div>
  <div class="col-md-3 col-md-offset-1 leftbar">
        <a class="btn btn-default btn-mylg" href="/gestione-ordini/new"><span class="glyphicon glyphicon-plus"></span> Nuovo ordine</a>
        <div class="panel-group" id="accordion">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a class="accordion-toggle" data-toggle="collapse" href="#collapseOne"><span class="glyphicon glyphicon-filter"></span> Filtra ordini</a>
              </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse in">
              <div class="panel-body">
                <a <?php if($this->filter == "PRI"){ echo 'class="selected"'; } ?> href="/gestione-ordini/index/filter/PRI">Ordini privati</a>
                <a <?php if($this->filter == "SHA"){ echo 'class="selected"'; } ?> href="/gestione-ordini/index/filter/SHA">Ordini condivisi</a>
                <a <?php if($this->filter == "PUB"){ echo 'class="selected"'; } ?> href="/gestione-ordini/index/filter/PUB">Ordini pubblici</a>
              </div>
            </div>
          </div>
        </div>
  </div>
  
</div>
