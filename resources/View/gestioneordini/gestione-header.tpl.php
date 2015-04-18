<h2>Ordine del <strong><?php echo $this->date($this->ordine->getDataInizio(), '%d %B %Y');?></strong></h2>
<h3>Produttori <strong><?php echo $this->arrayToString( $this->ordine->getProduttoriList() ); ?></strong></h3>

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
    <div class="col-md-6">
        <?php echo $this->partial('gestioneordini/header-status-details.tpl.php', array('ordine' => $this->ordine) ); ?>
        <?php echo $this->partial('gestioneordini/header-menu.tpl.php', array('ordine' => $this->ordine) ); ?>
    </div>
    <div class="col-md-6">
        <div class="hint">
    <?php if(!$this->ordine->isSupervisoreOrdine()): ?>
            <div class="alert alert-warning" role="alert">
                <b>Attenzione!</b><br />
                Alcuni campi sono disabilitati perchè solo il <b>Supervisore dell'ordine</b> (<?php echo $this->ordine->getSupervisore_Name(); ?>) può modificarli.
            </div>
    <?php else: ?>
            <div class="alert alert-info" role="alert">
                Sei il <b>Supervisore</b> di questo ordine.
            </div>
    <?php endif; ?>        
        </div>
    </div>
</div>