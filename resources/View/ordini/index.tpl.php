<h2>Elenco Ordini del <strong><?php echo "Gruppo"; ?></strong></h2>

<div class="row">
  <div class="col-md-8">
      
<?php if(count($this->list) > 0): ?>
    <?php foreach ($this->list as $key => $ordine): ?>

      <div class="row row-myig">
        <div class="col-md-8">
            <h3 class="no-margin">Ordine per <strong><?php echo $ordine->ragsoc;?></strong></h3>
        <?php if($ordine->statusObj->is_Pianificato()): ?>
            <h4 class="ordine <?php echo $ordine->statusObj->getStatus(); ?>">
                <?php echo $ordine->statusObj->getStatus(); ?>
                per il <strong><?php echo $this->date($ordine->data_inizio, '%d/%m/%Y');?></strong>
            </h4>
        <?php elseif($ordine->statusObj->is_Aperto()): ?>
            <h4 class="ordine <?php echo $ordine->statusObj->getStatus(); ?>"><?php echo $ordine->statusObj->getStatus(); ?></h4>            
            <p>
                Chiusura prevista il <strong><?php echo $this->date($ordine->data_fine, '%d/%m/%Y');?></strong> alle <?php echo $this->date($ordine->data_fine, '%H:%M:%S');?></strong>
            </p>
        <?php elseif($ordine->statusObj->is_Chiuso()): ?>
            <h4 class="ordine <?php echo $ordine->statusObj->getStatus(); ?>">
                <?php echo $ordine->statusObj->getStatus(); ?>
                il <strong><?php echo $this->date($ordine->data_fine, '%d/%m/%Y');?></strong>
            </h4>
        <?php elseif($ordine->statusObj->is_Archiviato()): ?>
            <h4 class="ordine <?php echo $ordine->statusObj->getStatus(); ?>"><?php echo $ordine->statusObj->getStatus(); ?></h4>            
            <p>
                <em>Apertura</em>: <strong><?php echo $this->date($ordine->data_inizio, '%d/%m/%Y');?></strong> alle <?php echo $this->date($ordine->data_inizio, '%H:%M:%S');?></strong><br />
                <em>Chiusura</em>: <strong><?php echo $this->date($ordine->data_fine, '%d/%m/%Y');?></strong> alle <?php echo $this->date($ordine->data_fine, '%H:%M:%S');?></strong>
            </p>
        <?php endif; ?>
        </div>
        <div class="col-md-4">
        <?php if(!$ordine->statusObj->is_Pianificato()): ?>
            <?php if($ordine->statusObj->is_Aperto()): ?>
                <a href="/ordini/ordina/idordine/<?php echo $ordine->idordine;?>" class="btn btn-success">Ordina ora!</a>
            <?php endif; ?>
                <a href="/ordini/viewdettaglio/idordine/<?php echo $ordine->idordine;?>" class="btn btn-default">Visualizza dettaglio</a>
        <?php endif; ?>
        </div>
      </div>
      
    <?php endforeach; ?>
<?php else: ?>
    <h3>Nessun ordine per questo produttore!</h3>
<?php endif; ?>
  </div>
  <div class="col-md-1">&nbsp;</div>
  <div class="col-md-3">
      <h3>Filtra per:</h3>
      <?php include $this->template('ordini/left-menu.tpl.php'); ?>      
  </div>    
</div>