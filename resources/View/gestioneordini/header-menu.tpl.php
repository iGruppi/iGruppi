    <a class="btn btn-default" role="button" href="/gestione-ordini/dashboard/idordine/<?php echo $this->ordine->getIdOrdine();?>"><span class="glyphicon glyphicon-th-large"></span></a>
    <div class="btn-group">
        <a class="btn btn-default dropdown-toggle" data-toggle="dropdown">
          <span class="glyphicon glyphicon-pencil"></span> Modifica <span class="caret"></span>
        </a>
        <ul class="dropdown-menu" role="menu">
          <li><a href="/gestione-ordini/edit/idordine/<?php echo $this->ordine->getIdOrdine();?>">Dati ordine</a></li>
          <li><a href="/gestione-ordini/speseextra/idordine/<?php echo $this->ordine->getIdOrdine();?>">Spese extra</a></li>
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
        <a id="a_mso" class="btn btn-success" role="button" disabled="disabled" href="#"><span class="glyphicon glyphicon-arrow-unchecked"></span> In attesa di Archiviazione</a>
        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
            <span class="caret"></span>
            <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu" role="menu">
            <li><a href="javascript:void(0)" onclick="jx_OrdineMoveStatus(<?php echo $this->ordine->getIdOrdine();?>, 'prev')"><span class="glyphicon glyphicon-arrow-left"></span> Riporta ad Arrivato</a></li>
        </ul>
    </div>
<?php endif; ?>
<?php if( $this->ordine->is_Archiviato()): ?>
    
    <button id="a_mso" class="btn btn-default" role="button" href="#" disabled="disabled"><span class="glyphicon glyphicon-check"></span> Archiviato</button>
<?php endif; ?>

<?php if( $this->ordine->canInviaOrdineByEmail()): ?>
<!--      <a class="btn btn-success" href="/gestione-ordini/invia/idordine/<?php echo $this->ordine->getIdOrdine();?>"><span class="glyphicon glyphicon-envelope"></span> Invia al Produttore</a> -->
<?php endif; ?>
