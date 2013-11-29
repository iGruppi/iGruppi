<div class="row">
  <div class="col-md-12">
<?php if($this->ordCalcObj->getNum() > 0): ?>
    <h3 class="big-margin-top">Dettaglio Parziali per prodotto</h3>
    <?php foreach ($this->ordCalcObj->getElenco() AS $idprodotto => $prodRow): 
        $pObj = $prodRow["prodotto"];
        $utenti = $prodRow["utenti"];
        ?>
        <div class="sub_menu">
            <h3 class="totale big-margin-top">Totale prodotto: <strong><?php echo $this->valuta($pObj->getTotale()); ?></strong></h3>
        </div>                    
        <h3 class="big-margin-top"><strong><?php echo $pObj->getQta();?></strong> x <?php echo $pObj->getDescrizione();?></h3>
        <p>
            Codice: <strong><?php echo $pObj->getCodice();?></strong><br />
            Prezzo: <?php echo $pObj->getPrezzo();?> &euro; / <?php echo $pObj->getUdm(); ?><br />
            Ordinato da:
        </p>
        <ul>
        <?php foreach ($utenti AS $iduser => $user): ?>
            <li><?php echo $user["cognome"] . " " . $user["nome"]; ?> (<strong><?php echo $user["qta"]; ?></strong>)</li>
        <?php endforeach; ?>
        </ul>        
        <div class="my_clear" style="clear:both;">&nbsp;</div>
    <?php endforeach; ?>
        
<?php else: ?>
    <div class="lead">Nessun prodotto ordinato!</div>
<?php endif; ?>
    
  </div>
</div>
    