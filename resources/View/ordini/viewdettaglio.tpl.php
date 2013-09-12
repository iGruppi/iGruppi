    <h2>Produttore <strong><?php echo $this->produttore->ragsoc;?></strong></h2>
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

    <div id="content_list1" style="margin-top: 50px;">
<?php if(count($this->list) > 0): ?>
    <?php include $this->template('ordini/prodotti.dettaglio.tpl.php'); ?>
<?php else: ?>
    <h3>Nessun prodotto ordinato/disponibile!</h3>
<?php endif; ?>
    </div>
