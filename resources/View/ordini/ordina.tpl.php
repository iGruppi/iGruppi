<h2>Produttore <strong><?php echo $this->produttore->ragsoc;?></strong></h2>
<div class="row">
  <div class="col-md-8">
<?php if($this->updated): ?>
    <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      La lista dei prodotti ordinati è stata aggiornata con <strong>successo</strong>!
    </div>
<?php endif; ?>
      
    <h3>Ordine <strong class="<?php echo $this->statusObj->getStatus(); ?>"><?php echo $this->statusObj->getStatus(); ?></strong></h3>
<?php if($this->statusObj->is_Aperto()): ?>
    <p>
        Chiusura prevista il <strong><?php echo $this->date($this->ordine->data_fine, '%d/%m/%Y');?></strong> alle <?php echo $this->date($this->ordine->data_fine, '%H:%M:%S');?></strong>
    </p>
<?php endif; ?>
    <div class="box_note">
        <h4>Note consegna:</h4>
        <p><?php echo $this->ordine->note_consegna; ?></p>
    </div>

<?php if(count($this->list) > 0): ?>
    <?php if($this->statusObj->is_Aperto()): ?>
        <?php include $this->template('ordini/prodotti.aperto.tpl.php'); ?>
    <?php elseif( $this->statusObj->is_Chiuso() || $this->statusObj->is_Archiviato() ): ?>
        <?php include $this->template('ordini/prodotti.chiuso.tpl.php'); ?>
    <?php endif; ?>
<?php else: ?>
    <h3>Nessun prodotto ordinato/disponibile!</h3>
<?php endif; ?>
    
  </div>
</div>
