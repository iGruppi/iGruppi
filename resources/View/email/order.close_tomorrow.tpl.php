
Domani <b><?php echo $this->date($this->ordine->data_fine, '%d/%m/%Y');?> alle <?php echo $this->date($this->ordine->data_fine, '%H:%M');?></b><br />
verrà <b>Chiuso</b> l'ordine per il produttore <b><?php echo $this->ordine->ragsoc; ?></b>.<br />
<br />
Note consegna:<br />
<?php echo $this->ordine->note_consegna; ?>
<br />
