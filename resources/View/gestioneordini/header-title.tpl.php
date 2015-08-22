<div class="ordine_title">

<?php if( trim($this->ordine->getDescrizione()) != ""): ?>
    <h3 class="title-list"><strong><?php echo $this->ordine->getDescrizione();?></strong> <?php echo $this->createLabelCondivisione($this->ordine->getCondivisione()); ?></h3>    
<?php else: ?>
    <h3 class="title-list">Ordine del <strong><?php echo $this->date($this->ordine->getDataInizio(), '%d %B %Y');?></strong>  <?php echo $this->createLabelCondivisione($this->ordine->getCondivisione()); ?></h3>
<?php endif; ?>
    
<?php if($this->ordine->isMultiProduttore()): ?>
    <div class="btn-group">
        <button type="button" class="btn btn-warning btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Ordine multiproduttore <span class="badge"><?php echo count($this->ordine->getProduttoriList()); ?></span> &nbsp; <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
    <?php foreach($this->ordine->getProduttoriList() AS $produttore): ?>
          <li><a href="#" class="disabled"><?php echo $produttore; ?></a></li>
    <?php endforeach;?>
        </ul>
    </div>
<?php else: ?>
    <button type="button" class="btn btn-default btn-xs" disabled="disabled"><?php echo $this->arrayToString( $this->ordine->getProduttoriList() ); ?></strong></button>
<?php endif; ?>    
    
    <h5><span class="text-muted">Prodotti:</span> <?php 
        $categorie = $this->ordine->getListaDescrizioniCategorie();
        echo $this->arrayToString($categorie); 
        ?></h5>
    <h5>
</div>