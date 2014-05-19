    Prezzo: <strong><?php echo $this->prodotto->getDescrizionePrezzo();?></strong><br />
<?php if($this->prodotto->hasPezzatura()): ?>
    [<b>**</b>] <small>Taglio/Pezzatura: <strong><?php echo $this->prodotto->getDescrizionePezzatura(); ?></strong></small><br />
<?php endif; ?>
