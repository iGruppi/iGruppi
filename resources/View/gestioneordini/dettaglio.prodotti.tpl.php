<div class="row">
  <div class="col-md-12">
    <h3 class="big-margin-top">Dettaglio Parziali per prodotto</h3>
<?php if( $this->ordCalcObj->isThereSomeProductsOrdered()): ?>
    <?php foreach ($this->ordCalcObj->getProdotti() AS $idprodotto => $prodRow): 
        $pObj = $prodRow["prodotto"];
        $utenti = $prodRow["utenti"];
        if($pObj->getQta() > 0):
        ?>
        <div<?php if(!$pObj->isDisponibile()) { echo ' class="text-danger strike"';} ?>>
            <div class="sub_menu">
                <h3 class="totale">Totale prodotto: <strong<?php if(!$pObj->isDisponibile()) { echo ' class="text-danger strike"';} ?>><?php echo $this->valuta($pObj->getTotale()); ?></strong></h3>
            </div>
            <h3 class="big-margin-top"><strong><?php echo $pObj->getQta();?></strong> x <?php echo $pObj->descrizione;?></h3>
            <p>
                Codice: <strong><?php echo $pObj->codice;?></strong><br />
                Prezzo: <?php echo $pObj->getPrezzo();?> &euro; / <?php echo $pObj->udm; ?><br />
                Ordinato da:
            </p>
            <ul>
            <?php foreach ($utenti AS $iduser => $user): ?>
                <li><?php echo $user["cognome"] . " " . $user["nome"]; ?> (<strong><?php echo $user["qta"]; ?></strong>)</li>
            <?php endforeach; ?>
            </ul>        
            <div class="my_clear" style="clear:both;">&nbsp;</div>
        </div>
    <?php 
        endif;
        endforeach; ?>
        
<?php else: ?>
    <div class="lead">Nessun prodotto ordinato!</div>
<?php endif; ?>
    
  </div>
</div>
    