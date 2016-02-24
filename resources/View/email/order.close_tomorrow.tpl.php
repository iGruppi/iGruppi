
Domani <b><?php echo $this->date($this->ordine->getDataFine(), '%d/%m/%Y');?> alle <?php echo $this->date($this->ordine->getDataFine(), '%H:%M');?></b><br />
verrà <b>Chiuso</b> l'ordine <b><?php echo $this->ordine->getDescrizione(); ?></b>.<br />
<br />
<?php 
    $categorie = $this->ordine->getListaDescrizioniCategorie();
    if( count($categorie) ): ?>
Prodotti: <b><?php echo implode(", ", $categorie); ?></b><br />
<?php endif; ?>
<?php 
    $produttori = $this->ordine->getProduttoriList();
    if( count($produttori) ): ?>
Produttori: <b><?php echo implode(", ", $produttori); ?></b><br />
<?php endif; ?>
<br />
<br />
Note consegna:<br />
<?php echo $this->note_consegna; ?>
<br />
