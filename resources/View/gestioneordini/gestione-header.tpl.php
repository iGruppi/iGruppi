<h2>Ordine del <strong><?php echo $this->date($this->ordine->getDataInizio(), '%d %B %Y');?></strong></h2>
<h3>Produttori <strong><?php echo $this->arrayToString( $this->ordine->getProduttoriList() ); ?></strong></h3>

<div class="row">
  <div class="col-md-12">
    <h4 class="ordine <?php echo $this->ordine->getStatusCSSClass(); ?>"><?php echo $this->ordine->getStateName(); ?></h3>
    <p>
        <em>Apertura</em>: <strong><?php echo $this->date($this->ordine->getDataInizio(), '%d/%m/%Y');?></strong> alle <?php echo $this->date($this->ordine->getDataInizio(), '%H:%M');?></strong><br />
        <em>Chiusura</em>: <strong><?php echo $this->date($this->ordine->getDataFine(), '%d/%m/%Y');?></strong> alle <?php echo $this->date($this->ordine->getDataFine(), '%H:%M');?></strong><br />
<?php if( $this->ordine->is_Consegnato() || $this->ordine->is_Archiviato() ): ?>
        <em>In consegna</em> dal <strong><?php echo $this->date($this->ordine->getDataInizio(), '%d/%m/%Y');?></strong> alle <?php echo $this->date($this->ordine->getDataFine(), '%H:%M');?></strong><br />
        <em>Consegnato</em> il <strong><?php echo $this->date($this->ordine->getDataInizio(), '%d/%m/%Y');?></strong> alle <?php echo $this->date($this->ordine->getDataFine(), '%H:%M');?></strong>
<?php endif; ?>
<?php if( $this->ordine->is_Inviato()): ?>
        <em>Inviato</em>: <strong><?php echo $this->date($this->ordine->getDataInizio(), '%d/%m/%Y');?></strong> alle <?php echo $this->date($this->ordine->getDataInizio(), '%H:%M');?></strong>
<?php endif; ?>
    </p>
  </div>
</div>

<div class="row">
  <div class="col-md-12 hidden-print">
    <a class="btn btn-default" role="button" href="/gestione-ordini/dashboard/idordine/<?php echo $this->ordine->getIdOrdine();?>"><span class="glyphicon glyphicon-th-large"></span></a>
    <div class="btn-group">
        <a class="btn btn-default dropdown-toggle" data-toggle="dropdown">
          <span class="glyphicon glyphicon-pencil"></span> Modifica <span class="caret"></span>
        </a>
        <ul class="dropdown-menu" role="menu">
          <li><a href="/gestione-ordini/edit/idordine/<?php echo $this->ordine->getIdOrdine();?>">Dati ordine</a></li>
          <li><a href="/gestione-ordini/sharing/idordine/<?php echo $this->ordine->getIdOrdine();?>">Condivisione</a></li>
          <li><a href="/gestione-ordini/prodotti/idordine/<?php echo $this->ordine->getIdOrdine();?>">Prodotti</a></li>
          <li><a href="/gestione-ordini/qtaordine/idordine/<?php echo $this->ordine->getIdOrdine(); ?>">Quantità ordinate</a></li>
        </ul>
    </div>
    <div class="btn-group">
        <a class="btn btn-default dropdown-toggle" data-toggle="dropdown">
          <span class="glyphicon glyphicon-th-list"></span> Riepilogo <span class="caret"></span>
        </a>
        <ul class="dropdown-menu" role="menu">
          <li><a href="/gestione-ordini/dettaglio/idordine/<?php echo $this->ordine->getIdOrdine(); ?>/tipo/totali">Totale ordinato</a></li>
          <li><a href="/gestione-ordini/dettaglio/idordine/<?php echo $this->ordine->getIdOrdine(); ?>/tipo/utenti">Parziali per utente</a></li>
          <li><a href="/gestione-ordini/dettaglio/idordine/<?php echo $this->ordine->getIdOrdine(); ?>/tipo/prodotti">Parziali per prodotto</a></li>
        </ul>
    </div>
<?php if( $this->ordine->is_Chiuso()): ?>
    <a id="a_mso" class="btn btn-warning" role="button" href="javascript:void(0)" onclick="jx_OrdineMoveStatus(<?php echo $this->ordine->getIdOrdine();?>, 'next')"><span class="glyphicon glyphicon-arrow-right"></span> Inviato</a>
<?php endif; ?>
<?php if( $this->ordine->is_Inviato()): ?>
    <div class="btn-group">
        <a id="a_mso" class="btn btn-warning" role="button" href="javascript:void(0)" onclick="jx_OrdineMoveStatus(<?php echo $this->ordine->getIdOrdine();?>, 'next')"><span class="glyphicon glyphicon-arrow-right"></span> Arrivato</a>
        <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
            <span class="caret"></span>
            <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu" role="menu">
            <li><a href="javascript:void(0)" onclick="jx_OrdineMoveStatus(<?php echo $this->ordine->getIdOrdine();?>, 'prev')"><span class="glyphicon glyphicon-arrow-left"></span> Riporta a Chiuso</a></li>
        </ul>
    </div>
<?php endif; ?>
<?php if( $this->ordine->is_Arrivato()): ?>
    <div class="btn-group">
        <a id="a_mso" class="btn btn-warning" role="button" href="javascript:void(0)" onclick="jx_OrdineMoveStatus(<?php echo $this->ordine->getIdOrdine();?>, 'next')"><span class="glyphicon glyphicon-arrow-right"></span> Consegnato</a>
        <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
            <span class="caret"></span>
            <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu" role="menu">
            <li><a href="javascript:void(0)" onclick="jx_OrdineMoveStatus(<?php echo $this->ordine->getIdOrdine();?>, 'prev')"><span class="glyphicon glyphicon-arrow-left"></span> Riporta a Inviato</a></li>
        </ul>
    </div>
<?php endif; ?>
<?php if( $this->ordine->is_Consegnato()): ?>
    <div class="btn-group">
        <a id="a_mso" class="btn btn-success" role="button" href="javascript:void(0)" onclick="jx_OrdineMoveStatus(<?php echo $this->ordine->getIdOrdine();?>, 'next')"><span class="glyphicon glyphicon-arrow-right"></span> Archivia</a>
        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
            <span class="caret"></span>
            <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu" role="menu">
            <li><a href="javascript:void(0)" onclick="jx_OrdineMoveStatus(<?php echo $this->ordine->getIdOrdine();?>, 'prev')"><span class="glyphicon glyphicon-arrow-left"></span> Riporta In Consegna</a></li>
        </ul>
    </div>
<?php endif; ?>
<?php if( $this->ordine->is_Archiviato()): ?>
    <a id="a_mso" class="btn btn-warning" role="button" href="javascript:void(0)" onclick="jx_OrdineMoveStatus(<?php echo $this->ordine->getIdOrdine();?>, 'prev')"><span class="glyphicon glyphicon-arrow-left"></span> Riporta a Consegnato</a>
<?php endif; ?>

<?php if( $this->ordine->canRef_InviaOrdine()): ?>
<!--      <a class="btn btn-success" href="/gestione-ordini/invia/idordine/<?php echo $this->ordine->getIdOrdine();?>"><span class="glyphicon glyphicon-envelope"></span> Invia al Produttore</a> -->
<?php endif; ?>
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