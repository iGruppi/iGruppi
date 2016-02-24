
Domani <b><?php echo $this->date($this->ordine->getDataFine(), '%d/%m/%Y');?> alle <?php echo $this->date($this->ordine->getDataFine(), '%H:%M');?></b><br />
verrà <b>Chiuso</b> l'ordine <b><?php echo $this->ordine->getDescrizione(); ?></b>.<br />
<br />
Note consegna:<br />
<?php echo $this->note_consegna; ?>
<br />
