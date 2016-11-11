<div class="hidden-print">
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
    
    <?php if( $this->ordine->canInviaNotificaOrdine()): ?>
        <div class="btn-group">
            <a class="btn btn-success dropdown-toggle" data-toggle="dropdown">
              <span class="glyphicon glyphicon-th-list"></span> Invia notifica <span class="caret"></span>
            </a>
            <ul class="dropdown-menu" role="menu">
              <li><a data-whatever="Amministratori" data-toggle="modal" data-target="#notificaModal">Amministratori</a></li>
              <li><a data-whatever="Incaricati" data-toggle="modal" data-target="#notificaModal">Incaticati</a></li>
              <li><a data-whatever="Utenti" data-toggle="modal" data-target="#notificaModal">Chi ha ordinato</a></li>
            </ul>
        </div>
    <?php endif; ?>
    
<?php if($this->ordine->canUpdateStato()): ?>
    
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
<?php endif; ?>
</div>

<!-- Modal -->
<div class="modal fade" id="notificaModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Invia notifica ordine #<?php echo $this->ordine->getIdOrdine(); ?><br />
            <b><?php echo trim($this->ordine->getDescrizione()); ?></b>
        </h4>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="notifica_ordine_oggetto" id="myNotificaTitle"></label><br />
            <span class="tag label label-info">
               <span>GAS Iqbal</span>
               <a><i class="remove glyphicon glyphicon-remove-sign glyphicon-white"></i></a> 
            </span>  
          </div>
          <div class="form-group">
            <label for="notifica_ordine_oggetto">Oggetto</label>
            <input type="email" class="form-control" id="notifica_ordine_oggetto" placeholder="Oggetto notifica...">
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Messaggio</label>
            <textarea class="form-control" rows="3" name="messaggio"></textarea>
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox"> Invia a me stesso
            </label>
          </div>
        </form>          
      </div>
      <div class="modal-footer">
        <input type="hidden" name="idordine" value="<?php echo $this->ordine->getIdOrdine(); ?>" />
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">INVIA</button>
      </div>
    </div>
  </div>
</div>
<script>
$(document).ready(function () {

    $('#notificaModal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget) // Button that triggered the modal
      var recipient = button.data('whatever') // Extract info from data-* attributes
      // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
      // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
      $('#myNotificaTitle').html("<b>" + recipient + "</b> dei gruppi:")
    })
    
});
</script>
