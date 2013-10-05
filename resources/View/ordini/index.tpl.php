<h2>Elenco Ordini</h2>

<div class="row">
  <div class="col-md-8">
      
<?php if(count($this->list) > 0): ?>
    <?php foreach ($this->list as $key => $ordine): ?>

      <div class="row row-myig">
        <div class="col-md-12">
            <h3 class="no-margin text-dark"><?php echo $ordine->ragsoc;?></h3>
        </div>
        <div class="col-md-8">
        <?php if( isset($this->arCat[$ordine->idproduttore]) ): ?>
            <h4><span class="text-muted">Prodotti:</span> <?php echo implode(", ", $this->arCat[$ordine->idproduttore]); ?></h4>
        <?php endif; ?>
        <?php if($ordine->statusObj->is_Pianificato()): ?>
            <h4 class="ordine">
                Ordine <span class="<?php echo $ordine->statusObj->getStatus(); ?>"><?php echo $ordine->statusObj->getStatus(); ?></span>
                per il <strong><?php echo $this->date($ordine->data_inizio, '%d/%m/%Y');?></strong>
            </h4>
        <?php elseif($ordine->statusObj->is_Aperto()): ?>
            <h4 class="ordine">Ordine <strong class="<?php echo $ordine->statusObj->getStatus(); ?>"><?php echo $ordine->statusObj->getStatus(); ?></strong></h4>            
            <p class="no-margin">Chiusura prevista il <strong><?php echo $this->date($ordine->data_fine, '%d/%m/%Y');?></strong> alle <?php echo $this->date($ordine->data_fine, '%H:%M');?></p>
        <?php elseif($ordine->statusObj->is_Chiuso()): ?>
            <h4 class="ordine">
                Ordine <span class="<?php echo $ordine->statusObj->getStatus(); ?>"><?php echo $ordine->statusObj->getStatus(); ?></span>
                il <strong><?php echo $this->date($ordine->data_fine, '%d/%m/%Y');?></strong>
            </h4>
        <?php elseif($ordine->statusObj->is_Archiviato()): ?>
            <h4 class="ordine">Ordine <span class="<?php echo $ordine->statusObj->getStatus(); ?>"><?php echo $ordine->statusObj->getStatus(); ?></span></h4>            
            <p class="no-margin">
                <em>Apertura</em>: <strong><?php echo $this->date($ordine->data_inizio, '%d/%m/%Y');?></strong> alle <?php echo $this->date($ordine->data_inizio, '%H:%M');?></strong><br />
                <em>Chiusura</em>: <strong><?php echo $this->date($ordine->data_fine, '%d/%m/%Y');?></strong> alle <?php echo $this->date($ordine->data_fine, '%H:%M');?></strong>
            </p>
        <?php endif; ?>
        </div>
        <div class="col-md-4">
        <?php if(!$ordine->statusObj->is_Pianificato()): ?>
            <?php if($ordine->statusObj->is_Aperto()): ?>
                <a href="/ordini/ordina/idordine/<?php echo $ordine->idordine;?>" class="btn btn-success" style="margin-bottom: 5px;"><span class="glyphicon glyphicon-shopping-cart"></span> Ordina ora!</a>
            <?php endif; ?>
                <a href="/ordini/viewdettaglio/idordine/<?php echo $ordine->idordine;?>" class="btn btn-default"><span class="glyphicon glyphicon-list"></span> Visualizza dettaglio</a>
        <?php endif; ?>
        </div>
      </div>
      
    <?php endforeach; ?>
<?php else: ?>
    <h3>Nessun ordine per questo produttore!</h3>
<?php endif; ?>
  </div>
  <div class="col-md-4 col-right">
      <?php include $this->template('ordini/left-menu.tpl.php'); ?>      
  </div>    
</div>