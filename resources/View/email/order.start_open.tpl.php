
A breve (alle <?php echo $this->date($this->ordine->getDataInizio(), '%H:%M');?>)<br />
verrà <b>Aperto</b> il nuovo ordine:<br /> 
<b><?php echo $this->ordine->getDescrizione(); ?></b>.<br />
<br />
<?php 
    $categorie = $this->ordine->getListaDescrizioniCategorie();
    if( count($categorie) ): ?>
Prodotti disponibili: <b><?php echo implode(", ", $categorie); ?></b><br />
<?php endif; ?>
<?php 
    $produttori = $this->ordine->getProduttoriList();
    if( count($produttori) ): ?>
Produttori: <b><?php echo implode(", ", $produttori); ?></b><br />
<?php endif; ?>

<br />
Data chiusura ordine: <b><?php echo $this->date($this->ordine->getDataFine(), '%d/%m/%Y');?> alle <?php echo $this->date($this->ordine->getDataFine(), '%H:%M');?></b><br />
<br />
