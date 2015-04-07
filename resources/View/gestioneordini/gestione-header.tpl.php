<h2>Ordine del <strong><?php echo $this->date($this->ordine->getDataInizio(), '%d %B %Y');?></strong></h2>
<h3>Produttori <strong><?php echo $this->arrayToString( $this->ordine->getProduttoriList() ); ?></strong></h3>

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

<div class="row">
  <div class="col-md-12" id="ordine_header_status">
      <?php echo $this->partial('gestioneordini/header-status-details.tpl.php', array('ordine' => $this->ordine) ); ?>
  </div>
</div>

<div class="row">
  <div class="col-md-12 hidden-print" id="ordine_header_menu">
      <?php echo $this->partial('gestioneordini/header-menu.tpl.php', array('ordine' => $this->ordine) ); ?>
  </div>   
</div>