<h2>Produttore <strong><?php echo $this->produttore->ragsoc;?></strong></h2>
<h3 class="no-margin">Ordine del <strong><?php echo $this->date($this->ordine->data_inizio, '%d %B %Y');?></strong></h3>
<h4 class="ordine <?php echo $this->statusObj->getStatus(); ?>"><?php echo $this->statusObj->getStatus(); ?></h4>

<div class="row">
  <div class="col-md-4">
    <p>
        <em>Apertura</em>: <strong><?php echo $this->date($this->ordine->data_inizio, '%d/%m/%Y');?></strong> alle <?php echo $this->date($this->ordine->data_inizio, '%H:%M');?></strong><br />
        <em>Chiusura</em>: <strong><?php echo $this->date($this->ordine->data_fine, '%d/%m/%Y');?></strong> alle <?php echo $this->date($this->ordine->data_fine, '%H:%M');?></strong>
    </p>
  </div>
  <div class="col-md-8 hidden-print">
    <a class="btn btn-default" role="button" href="/gestione-ordini/dashboard/idordine/<?php echo $this->ordine->idordine;?>"><span class="glyphicon glyphicon-th-large"></span></a>
    <div class="btn-group">
        <a class="btn btn-default dropdown-toggle" data-toggle="dropdown">
          <span class="glyphicon glyphicon-pencil"></span> Modifica <span class="caret"></span>
        </a>
        <ul class="dropdown-menu" role="menu">
          <li><a href="/gestione-ordini/edit/idordine/<?php echo $this->ordine->idordine;?>">Dati ordine</a></li>
          <li><a href="/gestione-ordini/prodotti/idordine/<?php echo $this->ordine->idordine;?>">Prodotti</a></li>
          <li><a href="/gestione-ordini/qtaordine/idordine/<?php echo $this->ordine->idordine; ?>">Quantità ordinate</a></li>
        </ul>
    </div>
    <div class="btn-group">
        <a class="btn btn-default dropdown-toggle" data-toggle="dropdown">
          <span class="glyphicon glyphicon-th-list"></span> Riepilogo <span class="caret"></span>
        </a>
        <ul class="dropdown-menu" role="menu">
          <li><a href="/gestione-ordini/dettaglio/idordine/<?php echo $this->ordine->idordine; ?>/tipo/totali">Totale ordinato</a></li>
          <li><a href="/gestione-ordini/dettaglio/idordine/<?php echo $this->ordine->idordine; ?>/tipo/utenti">Parziali per utente</a></li>
          <li><a href="/gestione-ordini/dettaglio/idordine/<?php echo $this->ordine->idordine; ?>/tipo/prodotti">Parziali per prodotto</a></li>
        </ul>
    </div>
    <?php if( $this->statusObj->canRef_InviaOrdine()): ?>
      <a class="btn btn-success" href="/gestione-ordini/invia/idordine/<?php echo $this->ordine->idordine;?>"><span class="glyphicon glyphicon-envelope"></span> Invia al Produttore</a>
    <?php endif; ?>
<!--  <a class="btn btn-default" role="button"  href="javascript:void(0)" onclick="jx_OrdineInConsegna(<?php echo $this->ordine->idordine;?>, <?php echo $this->ordine->idproduttore; ?>)"><span class="glyphicon glyphicon-arrow-right"></span> In consegna</a> -->
  </div>   
</div>

<?php if($this->updated): ?>
<div class="row">
  <div class="col-md-8">
        <div class="alert alert-success alert-dismissable">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <?php if(isset($this->updated_msg) && $this->updated_msg != ""): ?>
          <?php echo $this->updated_msg; ?>
    <?php else: ?>
          L'ordine è stato <strong>aggiornato con successo</strong>!
    <?php endif; ?>          
        </div>
  </div>
</div>
<?php endif; ?>