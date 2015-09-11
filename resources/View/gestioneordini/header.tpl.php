<?php echo $this->partial('gestioneordini/header-title.tpl.php', array('ordine' => $this->ordine) ); ?>
<?php if($this->updated): ?>
<div class="row">
    <div class="col-md-12">
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

<div class="row">
    <div class="col-md-8">
        <?php echo $this->partial('gestioneordini/header-status-details.tpl.php', array('ordine' => $this->ordine) ); ?>
        <?php echo $this->partial('gestioneordini/header-menu.tpl.php', array('ordine' => $this->ordine) ); ?>
    </div>
    <div class="col-md-4">
    <?php if(!$this->ordine->isSupervisoreOrdine()): ?>
            <div class="alert alert-warning" role="alert">
                <b>Attenzione!</b><br />
                Alcuni campi sono disabilitati perchè solo il <b>Supervisore dell'ordine</b> (<?php echo $this->ordine->getSupervisore_Name(); ?>) può modificarli.
            </div>
    <?php else: ?>
            <div class="alert alert-info" role="alert">
                Sei il <b>Supervisore</b> di questo ordine.<br />
                <br />
        <?php if($this->ordine->canViewMultigruppoFunctions()): ?>
                <div class="btn-group">
                  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Riepilogo Multi Gruppi <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu">
                    <li><a href="/gestione-ordini/dettagliomg/idordine/<?php echo $this->ordine->getIdOrdine(); ?>/tipo/totali">Totale Ordinato</a></li>
                    <li><a href="#">Parziali Utenti/Prodotti</a></li>
                    <li><a href="#">Dettaglio Utenti</a></li>
                  </ul>
                </div>                
        <?php endif; ?>
            </div>
    <?php endif; ?>
    </div>
</div>