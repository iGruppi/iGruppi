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

    <?php if( $this->ordine->canInviaNotificaOrdine()): ?>
        <br />
        <div class="btn-group">
            <a class="btn btn-success dropdown-toggle" data-toggle="dropdown">
              <span class="glyphicon glyphicon-envelope"></span> Invia notifica a <span class="caret"></span>
            </a>
            <ul class="dropdown-menu" role="menu">
              <li><a href="#" data-whatever="Amministratori" data-toggle="modal" data-target="#notificaModal">Amministratori</a></li>
              <li><a href="#" data-whatever="Incaricati" data-toggle="modal" data-target="#notificaModal">Incaricati</a></li>
              <li><a href="#" data-whatever="Utenti" data-toggle="modal" data-target="#notificaModal">Chi ha ordinato</a></li>
            </ul>
        </div>
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
        <form id="notifica_ordine" method="post">
        <div class="modal-body">
            <div class="form-group">
              <label for="notifica_ordine_oggetto" id="myNotificaTitle"></label><br />
        <?php foreach ($this->ordine->getAllIdgroups() AS $idgroup): 
                  $group = $this->ordine->getGroupByIdGroup($idgroup);
            ?>
              <label class="btn btn-success">
                  <input type="checkbox" autocomplete="off" checked="" name="groups[]" value="<?php echo $idgroup; ?>"> <?php echo $group->getGroupName(); ?>
              </label>
        <?php endforeach; ?>
            </div>
            <div class="form-group">
              <label for="oggetto">Oggetto</label>
              <input type="text" name="oggetto" class="form-control">
            </div>
            <div class="form-group">
              <label for="messaggio">Messaggio</label>
              <textarea name="messaggio" class="form-control" rows="12"></textarea>
            </div>
            <div class="checkbox">
              <label>
                  <input type="checkbox" name="send_to_me" value="1" checked=""> Invia una copia a me stesso
              </label>
            </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="recipient" id="recipient" value="" />
          <input type="hidden" name="idordine" value="<?php echo $this->ordine->getIdOrdine(); ?>" />
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button id="submit" type="button" class="btn btn-primary">INVIA</button>
        </div>
        </form>
        <div id="sender_response_OK" class="alert alert-success" role="alert" style="display: none;">
            Messaggio inviato con successo!
        </div>
        <div id="sender_response_FAIL" class="alert alert-danger" role="alert" style="display: none;">
            <b>Attenzione!</b> Si sono verificati dei problemi durante l'invio dell'email, ti preghiamo di riprovare più tardi.
        </div>
    </div>
  </div>
</div>
<script>
$(document).ready(function () {

    var idordine = <?php echo $this->ordine->getIdOrdine(); ?>;

    $('#notificaModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        var recipient = button.data('whatever') // Extract info from data-* attributes
        $('#myNotificaTitle').html("<b>" + recipient + "</b> dei gruppi:")
        $('#recipient').attr('value', recipient);
        $('#notifica_ordine').show();
        $('#sender_response_OK').hide();
        $('#sender_response_FAIL').hide();
    })
    
    $('#submit').click(function() {
        
        $(this).prop('disabled', true);
        
        $.post(
        '/gestione-ordini/invia/idordine/'+idordine,
        $('#notifica_ordine').serialize(),
        function(data) {
            // hide the form and re-enable the submit button
            $('#notifica_ordine').hide();
            $('#submit').prop('disabled', false);
            if(data.res) {
                $('#sender_response_OK').show();
                $('#sender_response_FAIL').hide();
            } else {
                $('#sender_response_OK').hide();
                $('#sender_response_FAIL').show();
            }
        },
        "json");
    });
    
});
</script>
