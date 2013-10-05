<h2>Gestione Ordini per <strong><?php echo $this->produttore->ragsoc; ?></strong></h2>

<div class="row">
  <div class="col-md-8">
      
<?php if($this->updated): ?>
    <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      L'ordine è stato aggiornato con <strong>successo</strong>!
    </div>
<?php endif; ?>
      
<?php if(count($this->list) > 0): ?>
    <?php foreach ($this->list as $key => $ordine): ?>

      <div class="row row-myig">
        <div class="col-md-8">
            <h3 class="no-margin">Ordine del <strong><?php echo $this->date($ordine->data_inizio, '%d %B %Y');?></strong></h3>
            <h4 class="ordine <?php echo $ordine->statusObj->getStatus(); ?>"><?php echo $ordine->statusObj->getStatus(); ?></h4>
            <p>
                <em>Apertura</em>: <strong><?php echo $this->date($ordine->data_inizio, '%d/%m/%Y');?></strong> alle <?php echo $this->date($ordine->data_inizio, '%H:%M');?></strong><br />
                <em>Chiusura</em>: <strong><?php echo $this->date($ordine->data_fine, '%d/%m/%Y');?></strong> alle <?php echo $this->date($ordine->data_fine, '%H:%M');?></strong>
            </p>
        </div>
        <div class="col-md-4">
            <div class="btn-group btn-group-myig">
                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">Gestisci Ordine <span class="caret"></span></button>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="/gestione-ordini/edit/idordine/<?php echo $ordine->idordine;?>">Modifica ordine</a></li>
                  <li><a href="/gestione-ordini/prodotti/idordine/<?php echo $ordine->idordine;?>">Prodotti</a></li>
                  <li><a href="/gestione-ordini/dettaglio/idordine/<?php echo $ordine->idordine;?>">Dettaglio ordine</a></li>
                </ul>
            </div>            
        </div>
      </div>
      
    <?php endforeach; ?>
<?php else: ?>
    <h3>Nessun ordine per questo produttore!</h3>
<?php endif; ?>
  </div>
  <div class="col-md-1">&nbsp;</div>
  <div class="col-md-3">
      <a class="btn btn-default btn-mylg" href="/gestione-ordini/new/idproduttore/<?php echo $this->produttore->idproduttore;?>"><span class="glyphicon glyphicon-plus"></span> Nuovo ordine</a>
  </div>    
</div>
