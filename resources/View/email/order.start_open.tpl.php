
Domani <b><?php echo $this->date($this->ordine->data_inizio, '%d/%m/%Y');?> alle <?php echo $this->date($this->ordine->data_inizio, '%H:%M');?></b><br />
verrà <b>Aperto</b> un nuovo ordine per il produttore <b><?php echo $this->ordine->ragsoc; ?></b>.<br />
<br />
<?php if( isset($this->arCat) ): ?>
    Prodotti disponibili: <b><?php echo implode(", ", $this->arCat); ?></b><br />
<?php endif; ?>
<br />
Data chiusura ordine: <b><?php echo $this->date($this->ordine->data_fine, '%d/%m/%Y');?> alle <?php echo $this->date($this->ordine->data_fine, '%H:%M');?></b><br />
<br />
