<div class="ordine_title">

<?php if( trim($this->ordine->getDescrizione()) != ""): ?>
    <h3 class="title-list"><strong><?php echo $this->ordine->getDescrizione();?></strong></h3>    
<?php else: ?>
    <h3 class="title-list">Ordine del <strong><?php echo $this->date($this->ordine->getDataInizio(), '%d %B %Y');?></strong></h3>
<?php endif; ?>

<?php if($this->ordine->isMultiProduttore()):
    // isMultiProduttore > 1
    ?>
    <div class="btn-group">
        <button type="button" class="btn btn-warning btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Ordine multiproduttore <span class="badge"><?php echo count($this->ordine->getProduttoriList()); ?></span> &nbsp; <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
    <?php foreach($this->ordine->getProduttoriList() AS $idproduttore => $produttore): ?>
          <li><a href="/produttori/view/idproduttore/<?php echo $idproduttore; ?>"><?php echo $produttore; ?></a></li>
    <?php endforeach;?>
        </ul>
    </div>
<?php else:
        // isMultiProduttore <= 1
        $produttoriList = $this->ordine->getProduttoriList();
        if(count($produttoriList) > 0):
            $idproduttore = key($produttoriList);
            $produttore = $produttoriList[$idproduttore];
    ?>
    <a role="button" class="btn btn-default btn-xs" href="/produttori/view/idproduttore/<?php echo $idproduttore; ?>"><?php echo $produttore; ?></a>
<?php
        endif;
    endif; ?>

    <h5><span class="text-muted">Prodotti:</span> <?php 
        $categorie = $this->ordine->getListaDescrizioniCategorie();
        echo $this->arrayToString($categorie); 
        ?></h5>
    <h5>
</div>